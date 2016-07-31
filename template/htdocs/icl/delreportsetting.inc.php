<?php

function delreportsetting(){
	$reportid=GETVAL('reportid');
	global $db;
	
	$user=userinfo();
	if (!$user['groups']['reportsettings']) apperror('access denied');	
	
	$query="select * from ".TABLENAME_REPORTS." where reportid=$reportid and gyrosys=0";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid reportsetting record');
	
	$reportname=$myrow['reportname'];
	
	$query="delete from ".TABLENAME_REPORTS." where reportid=$reportid and gyrosys=0";
	sql_query($query,$db);
	
	logaction("deleted Report Settings #$reportid <u>$reportname</u>",
		array('reportid'=>$reportid,'reportname'=>$reportname),
		array('rectype'=>'reportsetting','recid'=>$reportid));
}
