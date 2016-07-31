<?php

function deltemplatetype(){
	$user=userinfo();
	if (!$user['groups']['systemplate']) apperror('Access denied');
	
	$templatetypeid=GETVAL('templatetypeid');
	global $db;
	
	$query="select * from templatetypes where templatetypeid=$templatetypeid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid templatetype record');
	
	$templatetypename=$myrow['templatetypename'];
	
	$query="delete from templatetypes where templatetypeid=$templatetypeid";
	sql_query($query,$db);
	
	$query="delete from templatevars where templatetypeid=$templatetypeid";
	sql_query($query,$db);
	
	$query="delete from templates where templatetypeid=$templatetypeid";
	sql_query($query,$db);
	
	logaction("deleted Template Class #$templatetypeid <u>$templatetypename</u>",
		array('templatetypeid'=>$templatetypeid,'templatetypename'=>$templatetypename),
		array('rectype'=>'templatetype','recid'=>$templatetypeid));
}
