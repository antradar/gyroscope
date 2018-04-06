<?php

include 'icl/showreportsetting.inc.php';

function updatereportsetting(){
	$reportid=GETVAL('reportid');	
	$reportname=QETSTR('reportname');
	$reportgroup=QETSTR('reportgroup');
	$reportfunc=QETSTR('reportfunc');
	$reportkey=QETSTR('reportkey');
	$reportdesc=QETSTR('reportdesc');

	$reportgroupnames=QETSTR('reportgroupnames');
	
	$user=userinfo();
	if (!$user['groups']['reportsettings']) apperror('access denied');
	$gsid=$user['gsid']+0;

	global $db;
	global $lang;
		
	$query="select * from ".TABLENAME_REPORTS." where (gsid=$gsid or gsid=0) and reportkey='$reportkey' and reportid!=$reportid";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique');

		

	$query="update ".TABLENAME_REPORTS." set reportname_$lang='$reportname',reportgroup_$lang='$reportgroup',reportfunc='$reportfunc',reportkey='$reportkey',reportdesc_$lang='$reportdesc',reportgroupnames='$reportgroupnames' where reportid=$reportid";
	sql_query($query,$db);

	logaction("updated Report Settings #$reportid <u>$reportname</u>",
		array('reportid'=>$reportid,'reportname'=>"$reportname"),
		array('rectype'=>'reportsetting','recid'=>$reportid));

	showreportsetting($reportid);
}
