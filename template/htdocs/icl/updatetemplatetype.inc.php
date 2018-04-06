<?php

include 'icl/showtemplatetype.inc.php';

function updatetemplatetype(){
	$templatetypeid=GETVAL('templatetypeid');	
	$templatetypename=QETSTR('templatetypename');
	$templatetypekey=QETSTR('templatetypekey');
	$activetemplateid=QETVAL('activetemplateid');
	$plugins=QETSTR('templatetypeplugins');
	$classes=QETSTR('templatetypeclasses');

	if ($activetemplateid==-1) $activetemplateid='null';

	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	global $db;

	$query="select * from templatetypes where templatetypekey='$templatetypekey' and templatetypeid!=$templatetypeid and gsid=$gsid ";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.');
	
	$query="update templatetypes set templatetypename='$templatetypename' ";
	if ($user['groups']['systemplate']) $query.=", templatetypekey='$templatetypekey', plugins='$plugins', classes='$classes' ";
	if ($activetemplateid>0 or $activetemplateid=='null') $query.=", activetemplateid='$activetemplateid' ";
	$query.=" where templatetypeid=$templatetypeid and gsid=$gsid ";
	$rs=sql_query($query,$db);

	if (sql_affected_rows($db,$rs)){
		logaction("updated Template Class #$templatetypeid <u>$templatetypename</u>",
			array('templatetypeid'=>$templatetypeid,'templatetypename'=>"$templatetypename"),
			array('rectype'=>'templatetype','recid'=>$templatetypeid));
	}
	
	showtemplatetype($templatetypeid);
}
