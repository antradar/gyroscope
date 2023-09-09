<?php
// Gyroscope SQL Wrapper
// ClickHouse implementation
// (c) Antradar Software

$SQL_ENGINE2="ClickHouse-V";
//$SQL_READONLY=1; //uncomment to trigger readonly mode

function vsql_escape($str){
	return addslashes($str);	
}

function vsql_get_db($dbhost,$dbsource,$dbuser,$dbpass,$lazyname=null){
	if (isset($lazyname)){
		global $dbdefers;
		if (!isset($dbdefers)) $dbdefers=array();
		$dbdefers[$lazyname]=array(
			'host'=>$dbhost,'source'=>$dbsource,
			'user'=>$dbuser,'pass'=>$dbpass
		);
		return $lazyname;
	}	

	$db=array(
		'host'=>$dbhost,
		'database'=>$dbsource,
		'user'=>$dbuser,
		'pass'=>$dbpass,
		'raw'=>'dnu',//do not use
	);

	return $db;
}

function vsql_prep($query,&$db,$params=array(),$quickinsert=0){
	global $gsdbprofile;
	global $vsql_READONLY;
	
	$tokens=explode(' ',trim($query));
	$verb=strtolower($tokens[0]);
		
	if ($vsql_READONLY){
		if ($verb!='select') return;
	}

	$lastids=array();

	if ($verb=='insert'&&$quickinsert==0){//try to understand the query structure and rewrite the query
		$table=null;
		$flist=null;
		$mvlist=null;
		$vlists=array();
		$paramgroups=array();
		$pkey=null;
		
		if (preg_match('/insert\s*into\s*(\S+)\s*\(([\S\s]+?)\)\s*values([\S\s]+?)$/i',$query,$matches)) {
			$table=$matches[1];
			$flist=explode(',',$matches[2]);
			$mvlist=trim($matches[3]);
		}
		if (preg_match('/insert\s*into\s*(\S+)\s*values([\S\s]+?)$/i',$query,$matches)) {
			$table=$matches[1];
			$mvlist=trim($matches[2]);
		}
		if (!isset($table)){
			die("unable to parse insertion request: ".htmlspecialchars($query));
		}

		$reg = '/[^(,]*(?:\([^)]+\))?[^),]*/'; //todo: support multi-level commas

		preg_match_all($reg,$mvlist,$matches);
		foreach ($matches[0] as $seg){
			$seg=trim($seg);
			if ($seg=='') continue;
			$seg=trim($seg,'(');
			$seg=trim($seg,')');
		 	array_push($vlists,explode(',',$seg));
		}

		$ppos=0;
		foreach ($vlists as $gidx=>$vlist){
			$listsize=count($vlist);

			foreach ($vlist as $vidx=>$item){
				if (!isset($paramgroups[$gidx])) $paramgroups[$gidx]=array();
				if (count($params)>0) $paramgroups[$gidx][$vidx]=$params[$ppos];
				$ppos++;
			}
		}

		//print_r($paramgroups); die();

		$myrs=vsql_prep("describe table $table",$db);

		if (!isset($myrs['data'])) die("Failed to get metrics for table ".htmlspecialchars($table));
		$tfields=array($myrs['data']);

		$uuids=array();
		foreach ($tfields[0] as $tfield){
			if (strtolower($tfield['type'])=='uuid'&&strtolower($tfield['default_expression'])=='generateuuidv4()') array_push($uuids,$tfield['name']);
			if (strtolower($tfield['comment'])=='identity') $pkey=$tfield['name'];
		}

		if (!isset($pkey)&&count($uuids)==1 && strtolower($uuids[0])==strtolower($tfields[0][0]['name'])){
			$pkey=$uuids[0];
		}

		$firstfield=strtolower(trim($flist[0]));

		if (isset($pkey)&&$pkey!=$firstfield){
			array_unshift($flist,$pkey);
			foreach ($vlists as $vidx=>$vlist){
				$myrs=vsql_prep('select generateUUIDv4() as uuid',$db);
				$uuid=$myrs['data'][0]['uuid'];
				array_push($lastids,$uuid);

				array_unshift($vlists[$vidx],'?');
				array_unshift($paramgroups[$vidx],$uuid);

			}//vlist

			$params=array();
			foreach ($paramgroups as $paramgroup){
				foreach ($paramgroup as $param) array_push($params,$param);
			}
			
			$vlines=array();
			foreach ($vlists as $vlist){
				array_push($vlines,'('.implode(',',$vlist).')');
			}

			$query="insert into $table (".implode(',',$flist).") values ".implode(', ',$vlines);

		} //if no primary key is present, no pre-insertion will be made

	}

	if ($verb=='update'){
		$query=trim($query);
		$tokens=explode(' ',$query);
		$tableparts=explode('.',$tokens[1]);

		$table=$tableparts[0];

		array_shift($tokens); //drop update
		array_shift($tokens); //drop table
		array_shift($tokens); //drop set

		$rest=implode(' ',$tokens);

		$query="alter table $table update $rest";
		//echo $query; die();
	}

	if ($verb=='delete'){
		$query=trim($query);
		$tokens=explode(' ',$query);
		$tableparts=explode('.',$tokens[2]);

		$table=$tableparts[0];

		array_shift($tokens); //drop delete
		array_shift($tokens); //drop from
		array_shift($tokens); //drop table

		$rest=implode(' ',$tokens);

		$query="alter table $table delete $rest";
		//echo $query; die();
	}
	
	if (is_string($db)){
		global $dbdefers;
		$dbinfo=$dbdefers[$db];
		$db=vsql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass']);
	}	
	
	if (!is_array($params)) $params=array($params);
	
	$a=microtime(1);
	
	$qidx=0;
	$query=preg_replace_callback('/\?/',function($matches) use (&$qidx,$params){
		$r="'".addslashes($params[$qidx])."'";
		if ($params[$qidx]===null) $r='NULL';
		$qidx++;
		return $r;
	},$query);

	$headers=array(
		'X-ClickHouse-User: '.$db['user'],
		'X-ClickHouse-Key: '.$db['pass'],
		'X-ClickHouse-Database: '.$db['database'],
		'X-ClickHouse-Format: JSON',
	);
	
	$url=$db['host'].'/';
	if ($quickinsert==0){
		if ($verb=='update') $url.="?mutations_sync=1";
		if ($verb=='delete') $url.="?mutations_sync=2";
	}
	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$query);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	
	$res=curl_exec($curl);
	curl_close($curl);

	//echo $res;
	//echo "\r\n======================\r\n";

	$rs=json_decode($res,1);

	if (!isset($rs)&&$res!='') {
		$rs=array('error'=>'json_parse','raw'=>$res);
		echo 'SQL error: '.$res;
		return;
	} else {
		if (count($lastids)>0) $rs['lastids']=$lastids;
	}

	if ($verb=='update'||$verb=='delete') {
		$rs['rows']=1;
	}

	// $query=preg_replace('/ limit\s*(\d+),(\d+)/i',' offset ${1} limit ${2}', $query);
	
	$b=microtime(1);
	$querytime=isset($rs)&&isset($rs['statistics'])&&isset($rs['statistics']['elapsed'])?$rs['statistics']['elapsed']:null;
		
	if (is_array($gsdbprofile)) array_push($gsdbprofile,array('query'=>$query,'time'=>$querytime,'nettime'=>$b-$a,'overhead'=>$b-$a-$querytime));

	return $rs;
		
}


function vsql_query($query,$db){
	return vsql_prep($query,$db,array());
}

function vsql_copy_from_query($query,$db,$omits,$table){
	$rs=vsql_query($query,$db);
	$myrow=vsql_fetch_assoc($rs);
	$fields=array();
	$values=array();
	$params=array();
	foreach ($myrow as $k=>$v){
		if (in_array(strtolower($k),$omits)) continue;
		array_push($fields,$k);
		array_push($values,'?');
		array_push($params,$v);
	}
	
	$query="insert into $table (".implode(',',$fields).") values (".implode(',',$values).")";
	$rs=vsql_prep($query,$db,$params);
	$id=vsql_insert_id($db,$rs);
	return $id;
}

function vsql_save_chunks($db,$table,$chunks){
	
		/*
		//example:
		$chunks=array(
			array('carname'=>$carname, 'carmake'=>$carmake,... ),
			...
		);
		*/
	
		$query="insert into $table (".implode(',',array_keys($chunks[0])).") values ";
		
		$qs=array();
		$vs=array();
		
		foreach ($chunks as $chunk){
			$qqs=array();
			foreach ($chunk as $v) {
				array_push($qqs,'?');
				array_push($vs,$v);
			}
			array_push($qs,'('.implode(',',$qqs).')');			
			
		}//foreach chunk
		
		$query.=implode(',',$qs);
		
		vsql_prep($query,$db,$vs,1);
		
				
}

/*
create table products(productid UInt64,productname String,cat String,sign Int8 default 1,ver UInt64 default 0) engine=CollapsingMergeTree(sign) primary key (productid) order by productid;

select productid,productname, ...,  max(ver) as ver from products group by productid,productname, ... having sum(sign)>0;

*/

function vsql_patch($db,$table,$pkey,$recs,$wtable=null,$signfield='sign',$verfield='ver'){
/*
	$recs=array(
		123=>array(//primary key value
			'name'=>'New Name'
		),
		222=>null, //delete	
	
*/

	$recmap=array();
	$recids=array();
	foreach ($recs as $pval=>$rec) $recids[$pval]=$pval;

	//if (count($fcounts)>1) die('all records must have the same number of fields. ');

	$query="describe $table";
	$rs=vsql_prep($query,$db);
	$fields=array();
	while ($myrow=vsql_fetch_assoc($rs)){
		if (!in_array($myrow['name'],array($signfield,$verfield))) array_push($fields,$myrow['name']);
	}//while

	$glist=implode(',',$fields);
	$qs=array('?','?'); //prepop with sign and ver
	foreach ($fields as $f) array_push($qs,'?');

	$query="select $glist,max($verfield) as $verfield from $table where $pkey in (".implode(',',$recids).") group by $glist having sum($signfield)>0";
	
	$rs=vsql_prep($query,$db);
	while ($myrow=vsql_fetch_assoc($rs)){
		$recinfo=$myrow;
		//$recinfo['ver']=intval($recinfo['ver'])+1;
		$recmap[$myrow[$pkey]]=$recinfo;
	}
	
	//echo $query;

	//print_r($recmap);

	$recs=array_intersect_key($recs,$recmap);

	//batch delete the old records
	$bqs=array();
	$params=array();
	$uqs=array();
	$uparams=array();
	foreach ($recs as $recid=>$rec) {
		array_push($bqs,'('.implode(',',$qs).')');
		if (isset($rec)) array_push($uqs,'('.implode(',',$qs).')');

		foreach ($fields as $f){
			array_push($params,$recmap[$recid][$f]);
			if (isset($rec)) {
				$recval=$recmap[$recid][$f];
				if (in_array($f,array_keys($rec))) $recval=$rec[$f];
				array_push($uparams,$recval);
			}
		}
		array_push($params,-1,$recmap[$recid]['ver']+1);
		if (isset($rec)) array_push($uparams,1,$recmap[$recid]['ver']+2);
	}

	$usetable=$table;
	if (isset($wtable)) $usetable=$wtable; //use a buffer to write if applicable	

	$query="insert into $usetable ($glist,$signfield,$verfield) values ".implode(',',$bqs);
	//echo $query."\r\n"; print_r($params);
	vsql_prep($query,$db,$params,1);


	//batch insert updates
	$query="insert into $usetable ($glist,$signfield,$verfield) values ".implode(',',$uqs);
	//echo $query."\r\n"; print_r($uparams);
	vsql_prep($query,$db,$uparams,1);
	

}

/*
$db=vsql_get_db('127.0.0.1:8123','mydb','default','mnstudio','db');
vsql_patch($db,'products','productid',array(
	123=>null,
	222=>array('productname'=>'C'.time(),'cat'=>'Toy'),
));
*/


function vsql_fetch_assoc(&$rs){
	if (!isset($rs['cursoridx'])) $rs['cursoridx']=0;

	$cursoridx=$rs['cursoridx'];
	$rs['cursoridx']++;

	if (isset($rs['data'])&&isset($rs['data'][$cursoridx])) return $rs['data'][$cursoridx];
	else return null;
	
}

function vsql_fetch_array(&$rs){
	$combo=array();
	$assoc=vsql_fetch_assoc($rs);
	$idx=0;
	if (is_array($assoc)){
		foreach ($assoc as $k=>$v){
			$combo[$idx]=$v;
			$combo[$k]=$v;
			$idx++;
		}
	}
	return $combo;
}

function vsql_insert_id($db,$rs,$multi=0){

	if (!isset($rs['lastids'])) return null;

	if ($multi) return $rs['lastids'];

	if (count($rs['lastids'])==0) return null;
	return $rs['lastids'][0];
}

function vsql_affected_rows($db,$rs){

	return $rs['rows'];
}

function vsql_begin_transaction(){
	die("not implemented!");
}

function vsql_commit(){
	die("not implemented!");
}

function vsql_rollback(){
	die("not implemented!");
}

/* Sample Connection

$db=vsql_get_db('127.0.0.1:8123','gyrostart','default','mnstudio','db');

*/
