<?php

function delreportsetting(){
	$reportid=SGET('reportid');
	global $db;
	
	$user=userinfo();
	if (!$user['groups']['devreports']) apperror('access denied');
	$gsid=$user['gsid'];
	
	checkgskey('delreportsetting_'.$reportid);
	
	$query="select * from ".TABLENAME_REPORTS." where reportid=? and gyrosys=0 and gsid=? ";
	$rs=sql_prep($query,$db,array($reportid,$gsid));
	if (!$myrow=sql_fetch_array($rs)) die('Invalid reportsetting record');
	
	$reportname=$myrow['reportname'];
	
	$query="delete from ".TABLENAME_REPORTS." where reportid=? and gyrosys=0 and gsid=?";
	sql_prep($query,$db,array($reportid,$gsid));
	
	logaction("deleted Report Settings #$reportid $reportname",
		array('reportid'=>$reportid,'reportname'=>$reportname),
		array('rectype'=>'reportsetting','recid'=>$reportid));
}
