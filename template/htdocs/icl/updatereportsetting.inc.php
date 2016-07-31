<?php

include 'icl/showreportsetting.inc.php';

function updatereportsetting(){
	$reportid=GETVAL('reportid');	
	$reportname=QETSTR('reportname');
	$reportgroup=QETSTR('reportgroup');
	$reportkey=QETSTR('reportkey');
	$reportdesc=QETSTR('reportdesc');

	$reportgroupnames=QETSTR('reportgroupnames');
	
	$user=userinfo();
	if (!$user['groups']['reportsettings']) apperror('access denied');	

	global $db;
		
	$query="select * from ".TABLENAME_REPORTS." where reportkey='$reportkey' and reportid!=$reportid";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique');

		

	$query="update ".TABLENAME_REPORTS." set reportname='$reportname',reportgroup='$reportgroup',reportkey='$reportkey',reportdesc='$reportdesc',reportgroupnames='$reportgroupnames' where reportid=$reportid";
	sql_query($query,$db);

	logaction("updated Report Settings #$reportid <u>$reportname</u>",
		array('reportid'=>$reportid,'reportname'=>"$reportname"),
		array('rectype'=>'reportsetting','recid'=>$reportid));

	showreportsetting($reportid);
}
