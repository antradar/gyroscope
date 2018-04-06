<?php

function maketemplate($templatetypekey,$reps,$preprocessor=null,$gsid=null){
	global $db;
	
	$query="select * from templatetypes,templates where templatetypekey='$templatetypekey' and activetemplateid=templateid ";
	if (is_numeric($gsid)) $query.=" and gsid=$gsid ";
	
	if (is_numeric($templatetypekey)) $query="select * from templates where templateid=$templatetypekey";
	
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) return null;
	
	$templatename=$myrow['templatename'];
	
	$templatepn=$myrow['templatepn'];
	$templateinit=$myrow['templateinit'];
	
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	
	$c=$myrow['templatetext'];
	
	if (isset($preprocessor)&&is_callable($preprocessor)){
		$c=$preprocessor($c);
	}
	
	foreach ($reps as $k=>$v){
		$c=str_replace('%%'.$k.'%%',$v,$c);	
	}
	
	return array('name'=>$templatename,'content'=>$c,'pn'=>$templatepn,'init'=>$templateinit);	
}

/*
Sample Preprocessor:

, function($c){
return str_replace('<li>%%optional_clause%%</li>','%%optional_clause%%',$c);
}

*/