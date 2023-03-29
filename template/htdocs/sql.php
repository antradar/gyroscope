<?php
// Gyroscope SQL Wrapper
// MySQLi implementation
// (c) Antradar Software

if (!defined('MYSQLI_STORE_RESULT')) define('MYSQLI_STORE_RESULT',0);
if (!defined('MYSQLI_ASYNC')) define('MYSQLI_ASYNC',8);

$SQL_ENGINE="MySQLi"; mysqli_report(MYSQLI_REPORT_OFF);


//$SQL_READONLY=1; //uncomment to trigger readonly mode

class LazyMySQLi extends mysqli{
	public $lazyname;
	public function __construct($host,$user,$pass,$db,$lazyname=null,$port=null,$socket=null){
		$this->lazyname=$lazyname;
		@parent::__construct($host,$user,$pass,$db,$port,$socket);
	}
}

function sql_escape($str){
	global $db;
	return mysqli_real_escape_string($db,$str);	
}

function sql_select_db($db,$name){
	mysqli_select_db($db,$name);	
}

function sql_get_db($dbhost,$dbsource,$dbuser,$dbpass,$lazyname=null,$rlazyname=null){
	if (isset($lazyname)){
		global $dbdefers;
		if (!isset($dbdefers)) $dbdefers=array();
		$dbdefers[$lazyname]=array(
			'host'=>$dbhost,'source'=>$dbsource,
			'user'=>$dbuser,'pass'=>$dbpass,'lazyname'=>$lazyname
		);
		return $lazyname;
	}

	$db=new LazyMySQLi($dbhost,$dbuser,$dbpass,$dbsource,$rlazyname);
	return $db;
}

function sql_prep($query,&$db,$params=null){
	global $gsdbprofile;
	global $SQL_READONLY;
	global $dbdefers;
	
	if ($SQL_READONLY){
		$tokens=explode(' ',trim($query));
		$verb=strtolower($tokens[0]);
		if ($verb!='select'&&$verb!='show') return;
	}
	
	if (is_string($db)){
		$dbinfo=$dbdefers[$db];
		$db=sql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass'],null,$dbinfo['lazyname']);
	}	
	
	if (is_object($db)&& ((isset($db->stat)&&$db->stat==null)||(isset($db->connect_error)&&$db->connect_error!='')||$db->sqlstate=='HY000') &&isset($db->lazyname)){
		error_log("mysql disconnected, reconnecting...");
		
		$dbinfo=$dbdefers[$db->lazyname];
		$maxtry=5;
		$tryidx=0;
		do{
			usleep(1000000*($tryidx*2));
			error_log("reconnect #".($tryidx+1));
			$db=sql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass'],null,$db->lazyname);
			$tryidx++;
		} while ( ((isset($db->connect_error)&&$db->connect_error!='') || (isset($db->error)||$db->error!='') || $db->sqlstate=='HY000' ) &&$tryidx<$maxtry);
	}
	
	
	if (isset($params)){
		if (!is_array($params)) $params=array($params);
	} else $params=array();
		
	$a=microtime(1);
	
	$typestr=str_pad('',count($params),'s');
			
	$stmt=mysqli_stmt_init($db);
	mysqli_stmt_prepare($stmt,$query);
		
	if ($stmt->error!=''){
		if (stripos($stmt->error,'keyring')!==false) echo "Keyring error";
		else echo "sql syntax error: ".$query.' '.$stmt->error;
		return;
	}
	

	if ($typestr!=''){
		
	///////// [[[ ////////	< PHP 5.6
		
	/*
	$cparams=array($stmt,$typestr);
	foreach ($params as $param) array_push($cparams,isset($param)?$param.'':null);
	$func=new ReflectionFunction('mysqli_stmt_bind_param');
	@$func->invokeArgs($cparams);
	*/
	////////// ]]] ///////	>= PHP 5.6
		
	mysqli_stmt_bind_param($stmt,$typestr,...$params);

	}//need to bind
	
	///////////////////////

	
	if (!isset($stmt)||!is_object($stmt)||!isset($stmt->id)||!$stmt->id){
		error_log('sql connection interrupted and an atom was split. reconnecting...');
		$dbinfo=$dbdefers[$db->lazyname];
		$db=sql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass'],null,$db->lazyname);
		return;	
	}
			
	if (!mysqli_stmt_execute($stmt)) {
		
		$backtrace=debug_backtrace();
		$file=basename($backtrace[0]['file']);
		$line=$backtrace[0]['line'];

		echo "@$file [$line] sql query error: ".$query.' '.$stmt->error." \r\n";
		return;
	}
	
	$rs=mysqli_stmt_get_result($stmt);
	
	if ($rs==null){
		$rs=array(
			'type'=>'stmt',
			'insert_id'=>$stmt->insert_id,
			'affected_rows'=>$stmt->affected_rows
		);
	}
	
	$b=microtime(1);
			
	if (is_array($gsdbprofile)) array_push($gsdbprofile,array('query'=>$query,'time'=>$b-$a));

	return $rs;
		
}

function sql_query($query,&$db,$mode=MYSQLI_STORE_RESULT){
	global $gsdbprofile;
	global $SQL_READONLY;
	global $dbdefers;
	
	if ($SQL_READONLY){
		$tokens=explode(' ',trim($query));
		$verb=strtolower($tokens[0]);
		if ($verb!='select'&&$verb!='show') return;
	}
		
	if (is_string($db)){
		$dbinfo=$dbdefers[$db];
		$db=sql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass'],null,$dbinfo['lazyname']);
	}	
	
	if (is_object($db)&& ((isset($db->stat)&&$db->stat==null)||(isset($db->connect_error)&&$db->connect_error!='')||$db->sqlstate=='HY000') &&isset($db->lazyname)){	
		error_log("disconnected, reconnecting...\r\n");
		$dbinfo=$dbdefers[$db->lazyname];
		$maxtry=5;
		$tryidx=0;
		do{
			usleep(1000000*($tryidx+1));
			error_log("reconnect #".($tryidx+1));
			$db=sql_get_db($dbinfo['host'],$dbinfo['source'],$dbinfo['user'],$dbinfo['pass'],null,$db->lazyname);
			$tryidx++;
		} while ( ((isset($db->connect_error)&&$db->connect_error!='') || (isset($db->error)||$db->error!='') || $db->sqlstate=='HY000' ) &&$tryidx<$maxtry);
				
	}
		
	$a=microtime(1);
	$rs=mysqli_query($db,$query,$mode);
	$b=microtime(1);
			
	if ((!$rs)&&$mode==MYSQLI_STORE_RESULT) {
		$backtrace=debug_backtrace();
		$file=basename($backtrace[0]['file']);
		$line=$backtrace[0]['line'];
				
		echo "@$file [$line] sql_error: ".$query.' '.mysqli_error($db)."\r\n";
	}
	if (is_array($gsdbprofile)) array_push($gsdbprofile,array('query'=>$query,'time'=>$b-$a));
	return $rs;
}

function sql_copy_from_query($query,$db,$omits,$table,$negs=null,$resets=null){ //resets is key-val
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$fields=array();
	$values=array();
	$params=array();
	foreach ($myrow as $k=>$v){
		if (in_array(strtolower($k),$omits)) continue;
		array_push($fields,$k);
		array_push($values,'?');
		if(isset($negs) && in_array($k, $negs) && is_numeric($v) ) $v = -1*$v;
		if(isset($resets) && isset($resets[$k])) $v=$resets[$k];
		array_push($params,$v);
	}
	
	$query="insert into $table (".implode(',',$fields).") values (".implode(',',$values).")";
	$rs=sql_prep($query,$db,$params);
	$id=sql_insert_id($db,$rs);
	return $id;
}

function sql_save_chunks($db,$table,$chunks){
	
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
		
		sql_prep($query,$db,$vs);
				
}

function sql_fetch_array($rs){
	return mysqli_fetch_array($rs);

}

function sql_fetch_assoc($rs){
	if (!isset($rs)||!is_object($rs)) return null;
	return mysqli_fetch_assoc($rs);
}

function sql_insert_id($db,$rs=null){
	global $SQL_READONLY;
	
	if ($SQL_READONLY) return 0;	

	if (is_array($rs)&&$rs['type']=='stmt'&&isset($rs['insert_id'])) return $rs['insert_id'];
	//if (!isset($rs)) return mysqli_insert_id();
	return mysqli_insert_id($db);
}

function sql_affected_rows($db,$rs){
	if (is_array($rs)&&$rs['type']=='stmt'&&isset($rs['affected_rows'])) return $rs['affected_rows'];
	if (is_object($rs)&&isset($rs->num_rows)) return $rs->num_rows;	
	return mysqli_affected_rows($db);
}

function sql_begin_transaction($db){
	$query="begin";
	mysqli_query($query,$db);	
	
}

function sql_commit($db){
	$query="commit";
	mysqli_query($query,$db);	
}

function sql_rollback(){
	$query="rollback";
	mysqli_query($query,$db);	
}

/* Sample Connection

$db=sql_get_db("localhost","mnhydra","root","mnstudio");
*/
