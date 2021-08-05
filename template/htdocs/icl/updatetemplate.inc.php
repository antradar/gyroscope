<?php

include 'icl/showtemplate.inc.php';

function updatetemplate(){
	$templateid=SGET('templateid');	
	$templatename=SQET('templatename');
	$templatetext=SQET('templatetext');
	
	gsguard($templateid,array(TABLENAME_TEMPLATETYPES,TABLENAME_TEMPLATES),array('templatetypeid-templatetypeid','templateid'));
	
	checkgskey('updatetemplate_'.$templateid);

	global $db;

	$query="update ".TABLENAME_TEMPLATES." set templatename=?,templatetext=? where templateid=?";
	sql_prep($query,$db,array($templatename,$templatetext,$templateid));
	
	logaction("updated Template #$templateid $templatename",
		array('templateid'=>$templateid,'templatename'=>"$templatename"),
		array('rectype'=>'template','recid'=>$templateid));

	showtemplate($templateid);
}
