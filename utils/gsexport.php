<?php
include '../template/htdocs/connect.php';
include 'gsexport_config.php';

$gsobj=array();

$gsid=1; //todo: get from parameter

$outputpath='export/';

//fetch
//attach
//snapshot files

//todo: scan all tables

//todo: sort tables by type

//checktables($tables);

foreach ($tables as $tablekey=>$table){
	$type=$table['type'];
	
	if ($type=='ignore') continue;

	if ($tablekey=='gss'){//special treatment
		$query="select * from gss where gsid=?";
		$rs=sql_prep($query,$db,$gsid);
		$myrow=sql_fetch_assoc($rs);
		savejson('gss',$myrow);
		
		if (is_array($table['files'])){
			foreach ($table['files'] as $fkey=>$file){
				$ofn=str_replace('#',$gsid,$file);
				$ofn=preg_replace_callback('/%%(\S+?)%%/',function($matches) use ($myrow){
					return $myrow[$matches[1]];
				},$ofn);
				savefile($tablekey.'.'.$gsid.'.'.$fkey,$ofn);
			}
		}
					
		continue;
	}
	
	echo "exporting $tablekey\r\n";
	
	$mnt=$table['mnt'];
	$mntid=$table['mntid'];
	
	$mnts=explode('/',$table['mnt']);
	
	if (!isset($mntid)) die("Missing primary key (mntid) in $tablekey\r\n");
	
		
	switch ($type){
	case 'base':
		$query="select * from $tablekey where gsid=?";
		$rs=sql_prep($query,$db,$gsid);
		$recs=array();
		while ($myrow=sql_fetch_assoc($rs)){
			$pid=$myrow[$mntid];
			if (!is_array($gsobj[$mnt])) $gsobj[$mnt]=array();
			$gsobj[$mnt][$pid]=array('pid'=>$pid);
			
			if (is_array($table['refs'])&&!is_array($myrow['refs'])){
				$refs=array();
				foreach ($table['refs'] as $refidx=>$ref){
					$found=0;
					
					$reftable=$ref['reftable'];
					if (isset($ref['func'])){
						$rfunc='resolve_'.$ref['func'];
						if (!is_callable($rfunc)) die("Undefined resolve function [$rfunc]\r\n");
						$reftable=$rfunc($myrow);						
					}					
					
					if (!isset($reftable)) {
						$refs=null;
					} else {
					
						if (isset($gsobj[$reftable][$myrow[$refidx]])) $found=1;
						
						if (!$found){//comment out to ignore referential resolution
							if (!isset($gsobj[$reftable])&&$myrow[$refidx]>0) die("Missing cross reference source [$reftable] \r\n");	
						}
						$refs[$refidx]=array('reftable'=>$reftable,'srcid'=>$myrow[$refidx],'found'=>$found);//gsobj[$ref[$reftable]][$rec[$refidx]]);
					}//has reftable
				}
			}			
			
			$recs[$pid]=$myrow;
			if (is_array($refs)){
				$gsobj[$mnt][$pid]['refs']=$refs;
			}
			
			if (is_array($table['files'])){
				foreach ($table['files'] as $fkey=>$file){
					$ofn=str_replace('#',$pid,$file);
					$ofn=preg_replace_callback('/%%(\S+?)%%/',function($matches) use ($myrow){
						return $myrow[$matches[1]];
					},$ofn);
					savefile($tablekey.'.'.$pid.'.'.$fkey,$ofn);
				}
			}			
			
			
		}//while
		if (strlen(json_encode($recs,JSON_INVALID_UTF8_IGNORE))==0){
			echo json_last_error_msg();
			die();
		}
		savejson($tablekey,$recs);
	break;
	case '1-n':
	
		$chaintables=$table['chaintables'];
		$chainlinks=$table['chainlinks'];
		
		if (!is_array($chaintables)) die("Missing chain tables for $tablekey\r\n");
		if (!is_array($chainlinks)) die("Missing chain links for $tablekey\r\n");
		
		$recs=gsfetch($gsid,$chaintables,$chainlinks);		
	
		foreach ($recs as $rec){
			$pid=$rec[$mntid];
			//echo "$pid\r\n";
			
			$pmnt=&$gsobj; //mount point
			foreach ($mnts as $midx=>$mmnt){
				//echo "$mmnt\r\n";
				if ($mmnt[0]=='#'){//numeric mount
					$opmnt=&$pmnt;
					$pmnt=&$pmnt[$rec[str_replace('#','',$mmnt)]];
					if (!is_array($pmnt)) {
						echo ("Warn: missing mount parent [$mmnt] on $tablekey. Orphan record?\r\n");
						$opmnt[$rec[str_replace('#','',$mmnt)]]=array('pid'=>$rec[str_replace('#','',$mmnt)]);
						$pmnt=&$opmnt[$rec[str_replace('#','',$mmnt)]];
					}
				} else {
					$opmnt=&$pmnt;
					$pmnt=&$pmnt[$mmnt];
					if (!is_array($pmnt)){
						if ($midx<count($mnts)-1){
							die("Missing mount container [$mmnt] on $tablekey. Make sure the parent is declared first.\r\n");
						} else {

							if (!is_array($opmnt[$mmnt])) $opmnt[$mmnt]=array();
							$pmnt=&$opmnt[$mmnt];
														
						}//create mount container
					}
					
					
				}//	
			}//foreach mount point

			$newobj=array('pid'=>$pid);
			if (is_array($table['refs'])){
				$newobj['refs']=array();
				foreach ($table['refs'] as $refidx=>$ref){
					$found=0;
					$reftable=$ref['reftable'];
					if (isset($ref['func'])){
						$rfunc='resolve_'.$ref['func'];
						if (!is_callable($rfunc)) die("Undefined resolve function [$rfunc]\r\n");
						$reftable=$rfunc($rec);						
					}					
					
					
					if (isset($gsobj[$reftable][$rec[$refidx]])) $found=1;
					
					if (!$found){//comment out to ignore referential resolution
						if (!isset($gsobj[$reftable])&&$rec[$refidx]>0) die("Missing cross reference source [$reftable] \r\n");	
					}
											
					$newobj['refs'][$refidx]=array('reftable'=>$reftable,'srcid'=>$rec[$refidx],'found'=>$found);//gsobj[$ref[$reftable]][$rec[$refidx]]);
				}
				
			}
			$pmnt[$pid]=$newobj;
			
			if (is_array($table['files'])){
				foreach ($table['files'] as $fkey=>$file){
					$ofn=str_replace('#',$pid,$file);
					$ofn=preg_replace_callback('/%%(\S+?)%%/',function($matches) use ($rec){
						return $rec[$matches[1]];
					},$ofn);
					
					savefile($tablekey.'.'.$pid.'.'.$fkey,$ofn);
				}
			}
			
		}//foreach rec
		
		savejson($tablekey,$recs);
	
	break;


	}
	

}

//app domain migration
//load balancer temporary cname verification
//migration tool - ref integrity and portability


//print_r($gsobj['loans'][33276]);
//print_r($gsobj['loanclients'][49213]);
//print_r($gsobj['payments']);

savejson('toc',$gsobj);


//////////////////////
function gsfetch($gsid,$tables,$keys){
	
	global $db;
		
	
	if (!is_array($tables)) $tables=array($tables);
	if (!is_array($keys)) $keys=array($keys);
	
	if (count($tables)!=count($keys)+1) die("gsfetch: parameter count mismatch");	
		
	$maintable=$tables[0];
	$mainkeys=explode('-',$keys[0]);
	$mainkey=$mainkeys[0];
	
	$tailtable=$tables[count($tables)-1];
	$tailkey=$keys[count($keys)-1];
		
	if ($extfields!='') $extfields=','.trim($extfields,',');

	$query="select $tailtable.* from $maintable";
	
	for ($i=1;$i<count($tables);$i++) $query.=', '.$tables[$i];
	
	$query.=" where ${maintable}.gsid=$gsid";
	
	for ($i=1;$i<=count($keys);$i++) {
		$kparts=explode('-',$keys[$i-1]);
		$keya=$kparts[0];
		$keyb=$kparts[1];
		$query.=' and '.$tables[$i-1].'.'.$keya.'='.$tables[$i].'.'.$keyb;
	}
	
	$rs=sql_query($query,$db); //don't convert this to sql_prep; params are carefully filtered
	$recs=array();
	while ($myrow=sql_fetch_assoc($rs)) array_push($recs,$myrow);
	
	return $recs;
		
}

function savefile($fn,$ofn){
	if (!file_exists($ofn)) {
		//echo "Warn: [$ofn] not found\r\n";
		return;
	}

	global $gsid;
	global $outputpath;
	
	$basedir=$outputpath.$gsid;
	if (!is_dir($basedir)) mkdir($basedir);
	
	$basedir=$outputpath.$gsid.'/files';
	if (!is_dir($basedir)) mkdir($basedir);
	
	$f=fopen($basedir.'/'.$fn.'.bin','wb');
	fwrite($f,file_get_contents($ofn));
	fclose($f);
			
}

function savejson($fn,$obj){
	global $gsid;
	global $outputpath;
	
	//return; //for now

	$basedir=$outputpath.$gsid;
	if (!is_dir($basedir)) mkdir($basedir);
	
	$f=fopen($basedir.'/'.$fn.'.txt','wt');
	fwrite($f,json_encode($obj,JSON_INVALID_UTF8_IGNORE|JSON_PRETTY_PRINT));
	fclose($f);
}

function checktables($tables){
	global $db;
	
	$errors=array();
	
	$query="show tables";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		$tablekey=$myrow[0];
		if (!isset($tables[$tablekey])) array_push($errors,$tablekey);
	}
	
	if (count($errors)>0){
		echo "The following tables are undefined in gsexport_config.php\r\n";
		echo "a table can be base, 1-n or ignore\r\n\r\n";
		foreach ($errors as $error){
			echo "    $error\r\n";	
		}
		die();	
	}
}
