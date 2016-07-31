<?php

function maketemplate($templatetypekey,$reps){
	global $db;
	
	$query="select * from templatetypes,templates where templatetypekey='$templatetypekey' and activetemplateid=templateid";
	
	if (is_numeric($templatetypekey)) $query="select * from templates where templateid=$templatetypekey";
	
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) return null;
	
	$templatename=$myrow['templatename'];
	
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	
	$c=$myrow['templatetext'];
	
	foreach ($reps as $k=>$v){
		$c=str_replace('%%'.$k.'%%',$v,$c);	
	}
	
	return array('name'=>$templatename,'content'=>$c);	
}