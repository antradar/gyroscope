<?php

function deltemplate(){
	$templateid=SGET('templateid');
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('deltemplate_'.$templateid);
	
	$query="select * from ".TABLENAME_TEMPLATES.",".TABLENAME_TEMPLATETYPES." where ".TABLENAME_TEMPLATES.".templateid=? and ".TABLENAME_TEMPLATES.".templatetypeid=".TABLENAME_TEMPLATETYPES.".templatetypeid and ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($templateid,$gsid));
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Access denied');
			
	$query="select * from ".TABLENAME_TEMPLATES." where templateid=?";
	$rs=sql_prep($query,$db,$templateid);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid template record');
	
	$templatename=$myrow['templatename'];
	
	$query="delete from ".TABLENAME_TEMPLATES." where templateid=?";
	sql_prep($query,$db,$templateid);

	$query="update ".TABLENAME_TEMPLATETYPES." set activetemplateid=null where activetemplateid=?";
	sql_prep($query,$db,$templateid);
	
	logaction("deleted Template #$templateid $templatename",
		array('templateid'=>$templateid,'templatename'=>$templatename),
		array('rectype'=>'templatetypetemplates','recid'=>$templatetypeid)
		);
}
