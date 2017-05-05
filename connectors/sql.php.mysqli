<?php
// Gyroscope SQL Wrapper
// MySQLi implementation
// (c) Antradar Software

if (!defined('MYSQLI_STORE_RESULT')) define('MYSQLI_STORE_RESULT',0);
if (!defined('MYSQLI_ASYNC')) define('MYSQLI_ASYNC',8);

$SQL_ENGINE="MySQLi";

function sql_escape($str){
	global $db;
	return mysqli_real_escape_string($db,$str);	
}

function sql_get_db($dbhost,$dbsource,$dbuser,$dbpass){
	$db=mysqli_connect($dbhost,$dbuser,$dbpass,$dbsource) or die ('failed to connect to database');
	return $db;
}

function sql_query($query,$db,$mode=MYSQLI_STORE_RESULT){
	$rs=mysqli_query($db,$query,$mode);
			
	if ((!$rs)&&$mode==MYSQLI_STORE_RESULT) echo "sql_error: ".$query.' '.mysqli_error();
	return $rs;
}

function sql_fetch_array($rs){
	return mysqli_fetch_array($rs);

}

function sql_fetch_assoc($rs){
	return mysqli_fetch_assoc($rs);
}

function sql_insert_id($db,$rs=null){

	if (!isset($rs)) return mysql_insert_id();
	return mysqli_insert_id($db);
}

function sql_affected_rows($db,$rs){
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
