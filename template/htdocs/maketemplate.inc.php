<?php

function maketemplate($templatetypekey,$reps,$preprocessor=null,$gsid=null){
	global $db;
	
	if (is_numeric($templatetypekey)) {
		$query="select * from templates where templateid=?";
		$rs=sql_prep($query,$db,$templatetypekey);
	} else {
		$params=array($templatetypekey);
		$query="select * from templatetypes,templates where templatetypekey=? and activetemplateid=templateid ";
		if (is_numeric($gsid)) {
			$query.=" and gsid=? ";
			array_push($params,$gsid);
		}
		$rs=sql_prep($query,$db,$params);
	}
	
	
	if (!$myrow=sql_fetch_assoc($rs)) return null;
	
	$templatename=$myrow['templatename'];
	
	foreach ($reps as $k=>$v){
		$templatename=str_replace('%%'.$k.'%%',$v,$templatename);	
	}	
	
	$templatepn=$myrow['templatepn'];
	$templateinit=$myrow['templateinit'];
	
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