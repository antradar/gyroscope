<?php
include 'udf/newfunc.inc.php';

function delfunc(){
	global $db;
	
	$dbname=GETSTR('dbname');
	$func=GETSTR('func');
	
		
	$query="drop function if exists $dbname.$func";
	sql_query($query,$db);
	

	newfunc();	
}
	
	
	