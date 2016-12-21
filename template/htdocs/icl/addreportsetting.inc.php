<?php

include 'icl/showreportsetting.inc.php';

function addreportsetting(){
	
	$reportname=QETSTR('reportname');
	$reportgroup=QETSTR('reportgroup');
	$reportfunc=QETSTR('reportfunc');
	$reportkey=QETSTR('reportkey');
	$reportdesc=QETSTR('reportdesc');
	
	global $db;
	
	$query="select * from ".TABLENAME_REPORTS." where reportkey='$reportkey'";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique');
	
	
	$query="insert into ".TABLENAME_REPORTS." (reportname,reportgroup,reportfunc,reportkey,reportdesc) values ('$reportname','$reportgroup','$reportfunc','$reportkey','$reportdesc') ";
	$rs=sql_query($query,$db);
	$reportid=sql_insert_id($db,$rs)+0;

	if (!$reportid) {
		header('apperror:'._tr('error_creating_record'));die();
	}
	
	logaction("added Report Settings #$reportid <u>$reportname</u>",array('reportid'=>$reportid,'reportname'=>"$reportname"));
	
	header('newrecid:'.$reportid);
	header('newkey:reportsetting_'.$reportid);
	header('newparams:showreportsetting&reportid='.$reportid);
	
	showreportsetting($reportid);
}

