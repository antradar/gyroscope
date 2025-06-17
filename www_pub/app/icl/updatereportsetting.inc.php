<?php

include 'icl/showreportsetting.inc.php';

function updatereportsetting($ctx=null){
	global $userrolelocks;
	
	$reportid=SGET('reportid',1,$ctx);	
	$reportname=SQET('reportname',1,$ctx);
	$reportgroup=SQET('reportgroup',1,$ctx);
	$reportfunc=SQET('reportfunc',1,$ctx);
	$reportkey=SQET('reportkey',1,$ctx);
	$reportdesc=SQET('reportdesc',1,$ctx);

	$reportgroupnames=SQET('reportgroupnames',1,$ctx);
	
	$user=userinfo($ctx);
	if (!$user['groups']['reportsettings']&&!$user['groups']['devreports']) apperror('access denied');
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	checkgskey('updatereportsetting_'.$reportid,$ctx);

	if ($ctx) $db=$ctx->db; else global $db;
	global $lang;
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) and reportkey=? and reportid!=?";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=?) and reportkey=? and reportid!=?";
	
	$rs=sql_prep($query,$db,array($gsid,$syslevel,$reportkey,$reportid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique');

	//gsid=0 can no longer be edited - it's locked and can be only changed from the database
	
	$query="select reportgroupnames from ".TABLENAME_REPORTS." where reportid=?";
	$rs=sql_prep($query,$db,$reportid);
	$myrow=sql_fetch_assoc($rs);
	$oldgroups=explode('|',$myrow['reportgroupnames']);
	$newgroups=explode('|',$reportgroupnames);
		
	foreach ($oldgroups as $idx=>$oldgroup){
		if ($oldgroup!=''&&!in_array($oldgroup,$newgroups)&&!$user['groups'][$oldgroup]){
			if (in_array($oldgroup,$userrolelocks)) array_push($newgroups,$oldgroup);
		}
	}
	
	$reportgroupnames=implode('|',$newgroups);
			
	$query="update ".TABLENAME_REPORTS." set reportname_$lang=?,reportgroup_$lang=?,";
	$params=array($reportname,$reportgroup);
	
	if ($user['groups']['devreports']) {
		$query.="reportfunc=?,reportkey=?,";
		array_push($params,$reportfunc,$reportkey);
	}
	$query.="reportdesc_$lang=?,reportgroupnames=? where reportid=? and ".COLNAME_GSID."=?";
	array_push($params,$reportdesc,$reportgroupnames,$reportid,$gsid);
	
	sql_prep($query,$db,$params);

	cache_inc_entity_ver('reports_list_'.$gsid);
	
	logaction($ctx,"updated Report Settings #$reportid $reportname",
		array('reportid'=>$reportid,'reportname'=>"$reportname"),
		array('rectype'=>'reportsetting','recid'=>$reportid),0,null,2);

	showreportsetting($ctx,$reportid);
}
