<?php

function deltemplatetype(){
	$user=userinfo();
	if (!$user['groups']['systemplate']) apperror('Access denied');
	
	$templatetypeid=SGET('templatetypeid');
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('deltemplatetype_'.$templatetypeid);
	
	$query="select * from templatetypes where templatetypeid=? and gsid=?";
	$rs=sql_prep($query,$db,array($templatetypeid,$gsid));
	if (!$myrow=sql_fetch_array($rs)) apperror('Invalid templatetype record');
	
	$templatetypename=$myrow['templatetypename'];
	
	$query="delete from templatetypes where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	$query="delete from templatevars where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	$query="delete from templates where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	logaction("deleted Template Class #$templatetypeid $templatetypename",
		array('templatetypeid'=>$templatetypeid,'templatetypename'=>$templatetypename),
		array('rectype'=>'templatetype','recid'=>$templatetypeid));
}
