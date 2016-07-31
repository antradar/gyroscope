<?php

function deltemplate(){
	$templateid=GETVAL('templateid');
	global $db;
	
	$query="select * from templates where templateid=$templateid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid template record');
	
	$templatename=$myrow['templatename'];
	
	$query="delete from templates where templateid=$templateid";
	sql_query($query,$db);
	
	logaction("deleted Template #$templateid <u>$templatename</u>",
		array('templateid'=>$templateid,'templatename'=>$templatename),
		array('rectype'=>'templatetypetemplates','recid'=>$templatetypeid)
		);
}
