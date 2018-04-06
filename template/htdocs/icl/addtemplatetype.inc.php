<?php

include 'icl/showtemplatetype.inc.php';

function addtemplatetype(){
	
	$templatetypename=QETSTR('templatetypename');
	$templatetypekey=QETSTR('templatetypekey');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	$query="select * from templatetypes where templatetypekey='$templatetypekey' and gsid=$gsid ";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.');
	
	$query="insert into templatetypes (gsid,templatetypename,templatetypekey) values ($gsid,'$templatetypename','$templatetypekey') ";
	$rs=sql_query($query,$db);
	$templatetypeid=sql_insert_id($db,$rs)+0;

	if (!$templatetypeid) {
		header('apperror:'._tr('error_creating_record'));die();
	}
	
	logaction("added Template Class #$templatetypeid <u>$templatetypename</u>",array('templatetypeid'=>$templatetypeid,'templatetypename'=>"$templatetypename"));
	
	header('newrecid:'.$templatetypeid);
	header('newkey:templatetype_'.$templatetypeid);
	header('newparams:showtemplatetype&templatetypeid='.$templatetypeid);
	
	showtemplatetype($templatetypeid);
}

