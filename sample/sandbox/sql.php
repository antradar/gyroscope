<?
// Macronetic Hydra
// Common SQL Interface
// MySQL implementation
// (c) Antradar Software 1995-2006

$SQL_ENGINE="MySQL";

function sql_get_db($dbhost,$dbsource,$dbuser,$dbpass){
	$db=mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbsource,$db);
	return $db;
}

function sql_query($query,$db){
	$rs=mysql_query($query,$db);
	if (!$rs) echo "sql_error: ".$query;
	return $rs;
}

function sql_fetch_array($rs){
	return mysql_fetch_array($rs);
}

function sql_fetch_assoc($rs){
	return mysql_fetch_assoc($rs);
}

function sql_insert_id($db,$rs=null){

	if (!isset($rs)) return mysql_insert_id();
	return mysql_insert_id($db);
}

function sql_affected_rows($db,$rs){
	return mysql_affected_rows($db);
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


?>