<?php
include 'udf/showfunc.inc.php';

function updatefunc(){
	global $db;
	
	checkgskey('updatefunc');
	
	$dbname=GETSTR('dbname');
	$func=GETSTR('func');
	
	$args=QETSTR('args');
	$pre=$_POST['pre'];
	$text=$_POST['text'];
	
	$query="select * from mysql.proc where db='$dbname' and name='$func' and type='function' ";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)){
	
		$functext=$myrow['body'];
		$funcpre=$myrow['param_list'];
		$returns=$myrow['returns'];
		$returns=preg_replace('/charset (\S+)/i','',$returns);
		
		$access=str_replace('_',' ',strtolower($myrow['sql_data_access']));
		$dtm=strtolower($myrow['is_deterministic']);
		$ddtm=$dtm=='yes'?'deterministic':'not deterministic';
		
	}
		
	$query="drop function if exists $dbname.$func";
	sql_query($query,$db);
	
	$query="create function $dbname.$func ($args) $pre $text";
	
	ob_start();
	if (!$rs=@sql_query($query,$db)) {
		apperror('Syntax error');
		
		$query="create function $dbname.$func ($funcpre) returns $returns $ddtm $access $functext ";
		sql_query($query,$db);
		
		die();
	}
		
	$c=ob_get_contents();
	ob_end_flush();
	

	showfunc($dbname,$func);	
	
}
	
	
	