<?php

include 'icl/showreportsetting.inc.php';

function addreportsetting($ctx=null){
	
	$reportname=SQET('reportname',1,$ctx);
	$reportgroup=SQET('reportgroup',1,$ctx);
	$reportfunc=SQET('reportfunc',1,$ctx);
	$reportkey=SQET('reportkey',1,$ctx);
	$reportdesc=SQET('reportdesc',1,$ctx);
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	global $lang;
	
	checkgskey('addreportsetting',$ctx);
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	if (!$user['groups']['devreports']) apperror('access denied',null,null,$ctx);
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) and reportkey=?";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=? and reportid=?";
	
	$rs=sql_prep($query,$db,array($gsid,$syslevel,$reportkey));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Report key must be unique',null,null,$ctx);
	
	
	$query="insert into ".TABLENAME_REPORTS." (".COLNAME_GSID.",reportname_$lang,reportgroup_$lang,reportfunc,reportkey,reportdesc_$lang) values (?,?,?,?,?,?) ";
	ob_start();
	$rs=sql_prep($query,$db,array($gsid,$reportname,$reportgroup,$reportfunc,$reportkey,$reportdesc));
	$err=ob_get_clean();
	
	$reportid=sql_insert_id($db,$rs);

	if (!$reportid) {
		apperror(_tr('error_creating_record '.$err),null,null,$ctx);
	}
	
	cache_inc_entity_ver('reports_list_'.$gsid);	
	
	logaction($ctx, "added Report Settings #$reportid $reportname",array('reportid'=>$reportid,'reportname'=>"$reportname"));
	
	gs_header($ctx, 'newrecid', $reportid);
	gs_header($ctx, 'newkey', 'reportsetting_'.$reportid);
	gs_header($ctx, 'newparams', 'showreportsetting&reportid='.$reportid);
	
	showreportsetting($ctx,$reportid);
}

