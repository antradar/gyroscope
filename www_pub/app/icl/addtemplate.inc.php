<?php

include 'icl/showtemplate.inc.php';

function addtemplate($ctx=null){
	$templatetypeid=SGET('templatetypeid',1,$ctx);
	$templatename=SQET('templatename',1,$ctx);
		
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	checkgskey('addtemplate_'.$templatetypeid,$ctx);
	gsguard($ctx,$templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
	
	$query="insert into ".TABLENAME_TEMPLATES." (templatetypeid,templatename) values (?,?) ";
	$rs=sql_prep($query,$db,array($templatetypeid,$templatename));
	$templateid=sql_insert_id($db,$rs);
	
	if (!$templateid) {
		apperror(_tr('error_creating_record'),null,null,$ctx);
	}
	
	$query="select * from ".TABLENAME_TEMPLATETYPES." where templatetypeid=? and activetemplateid>0";
	$rs=sql_prep($query,$db,$templatetypeid);
	if (!$myrow=sql_fetch_assoc($rs)){
		$query="update ".TABLENAME_TEMPLATETYPES." set activetemplateid=? where templatetypeid=?";
		sql_prep($query,$db,array($templateid,$templatetypeid));	
	}

	logaction($ctx, "added Template #$templateid $templatename",
		array('templateid'=>$templateid,'templatename'=>"$templatename"),
		array('rectype'=>'templatetypetemplates','recid'=>$templatetypeid),0,array(
			'table'=>'templates',
			'recid'=>$templateid,
			'after'=>array(
				'templatename'=>$templatename
			),
			'diffs'=>array(
				'templatename'=>$templatename
			)
		));
	
	gs_header($ctx, 'newrecid', $templateid);
	gs_header($ctx, 'newkey', 'template_'.$templateid);
	gs_header($ctx, 'newloadfunc', 'inittemplatetexteditor("'.$templateid.'");reloadview("codegen.templates","templatelist");refreshtab("templatetype_'.$templatetypeid.'",1);');
	gs_header($ctx, 'newparams', 'showtemplate&templateid='.$templateid);
	
	showtemplate($ctx, $templateid);
}

