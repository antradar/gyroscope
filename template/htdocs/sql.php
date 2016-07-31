<?php
// Macronetic Hydra
// Common SQL Interface
// MySQLi implementation
// (c) Antradar Software 1995-2013

if (!defined('MYSQLI_STORE_RESULT')) define('MYSQLI_STORE_RESULT',0);
if (!defined('MYSQLI_ASYNC')) define('MYSQLI_ASYNC',8);

$SQL_ENGINE="MySQLi";

function sql_escape($str){
	global $db;
	return mysqli_real_escape_string($db,$str);	
}

function sql_get_db($dbhost,$dbsource,$dbuser,$dbpass){
	$db=mysqli_connect($dbhost,$dbuser,$dbpass,$dbsource);
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

function sql_begin_transaction(){
	die("sql.ard: not implemented!");
}

function sql_commit(){
	die("sql.ard: not implemented!");
}

function sql_rollback(){
	die("sql.ard: not implemented!");
}

/* Sample Use

$db=sql_get_db("localhost","mnhydra","root","mnstudio");
$query="select * from pages";
$rs=sql_query($query,$db);
echo sql_affected_rows($db)." rows affected <br>";


  while ($myrow=sql_fetch_array($rs)){
	$pagefilename=$myrow['pagefilename'];
	$pageid=$myrow['pageid'];
	$pagetype=$myrow['pagetype'];
	$idepath="./ide/";
	if ($pagetype>1) $pagefilename=$idepath.$pagefilename;

	echo $pageid." ".$pagefilename."<br>";
  }

*/
