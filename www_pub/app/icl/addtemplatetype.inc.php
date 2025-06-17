<?php

include 'icl/showtemplatetype.inc.php';

function addtemplatetype($ctx=null){
	
	$templatetypename=SQET('templatetypename',1,$ctx);
	$templatetypekey=SQET('templatetypekey'1,$ctx);
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('addtemplatetype',$ctx);
	
	$query="select * from ".TABLENAME_TEMPLATETYPES." where templatetypekey=? and ".COLNAME_GSID."=? ";
	$rs=sql_prep($query,$db,array($templatetypekey,$gsid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.',null,null,$ctx);
	
	$query="insert into ".TABLENAME_TEMPLATETYPES." (".COLNAME_GSID.",templatetypename,templatetypekey,templatetypegroup,plugins,classes) values (?,?,?,?,?,?) ";
	ob_start();
	$rs=sql_prep($query,$db,array($gsid,$templatetypename,$templatetypekey,'','',''));
	$err=ob_get_clean();
	
	$templatetypeid=sql_insert_id($db,$rs);
	
	if (!$templatetypeid) {
		apperror(_tr('error_creating_record '.$err),null,null,$ctx);
	}
	
	logaction($ctx, "added Template Class #$templatetypeid $templatetypename",array('templatetypeid'=>$templatetypeid,'templatetypename'=>"$templatetypename"),null,0,null,4);
	
	gs_header($ctx, 'newrecid: '.$templatetypeid);
	gs_header($ctx, 'newkey: templatetype_'.$templatetypeid);
	gs_header($ctx, 'newparams: showtemplatetype&templatetypeid='.$templatetypeid);
	
	showtemplatetype($ctx, $templatetypeid);
}

