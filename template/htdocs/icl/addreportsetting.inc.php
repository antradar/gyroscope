<?php

include 'icl/showreportsetting.inc.php';

function addreportsetting(){
	
	$reportname=SQET('reportname');
	$reportgroup=SQET('reportgroup');
	$reportfunc=SQET('reportfunc');
	$reportkey=SQET('reportkey');
	$reportdesc=SQET('reportdesc');
	
	global $db;
	global $lang;
	
	checkgskey('addreportsetting');
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	if (!$user['groups']['devreports']) apperror('access denied');
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) and reportkey=?";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=? and reportid=?";
	
	$rs=sql_prep($query,$db,array($gsid,$syslevel,$reportkey));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique');
	
	
	$query="insert into ".TABLENAME_REPORTS." (".COLNAME_GSID.",reportname_$lang,reportgroup_$lang,reportfunc,reportkey,reportdesc_$lang) values (?,?,?,?,?,?) ";
	ob_start();
	$rs=sql_prep($query,$db,array($gsid,$reportname,$reportgroup,$reportfunc,$reportkey,$reportdesc));
	$err=ob_get_clean();
	
	$reportid=sql_insert_id($db,$rs);

	if (!$reportid) {
		apperror(_tr('error_creating_record '.$err));
	}
	
	logaction("added Report Settings #$reportid $reportname",array('reportid'=>$reportid,'reportname'=>"$reportname"));
	
	header('newrecid:'.$reportid);
	header('newkey:reportsetting_'.$reportid);
	header('newparams:showreportsetting&reportid='.$reportid);
	
	showreportsetting($reportid);
}

