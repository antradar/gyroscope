<?php

function delreportsetting(){
	$reportid=GETVAL('reportid');
	global $db;
	
	$user=userinfo();
	if (!$user['groups']['reportsettings']) apperror('access denied');
	$gsid=$user['gsid']+0;	
	
	$query="select * from ".TABLENAME_REPORTS." where reportid=$reportid and gyrosys=0 and gsid=$gsid ";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid reportsetting record');
	
	$reportname=$myrow['reportname'];
	
	$query="delete from ".TABLENAME_REPORTS." where reportid=$reportid and gyrosys=0 and gsid=$gsid";
	sql_query($query,$db);
	
	logaction("deleted Report Settings #$reportid <u>$reportname</u>",
		array('reportid'=>$reportid,'reportname'=>$reportname),
		array('rectype'=>'reportsetting','recid'=>$reportid));
}
