<?php

include 'icl/showtemplate.inc.php';

function addtemplate(){
	$templatetypeid=SGET('templatetypeid');
	$templatename=SQET('templatename');
		
	global $db;
	
	checkgskey('addtemplate_'.$templatetypeid);
	gsguard($templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
	
	$query="insert into ".TABLENAME_TEMPLATES." (templatetypeid,templatename) values (?,?) ";
	$rs=sql_prep($query,$db,array($templatetypeid,$templatename));
	$templateid=sql_insert_id($db,$rs);
	
	if (!$templateid) {
		apperror(_tr('error_creating_record'));
	}
	
	$query="select * from ".TABLENAME_TEMPLATETYPES." where templatetypeid=? and activetemplateid>0";
	$rs=sql_prep($query,$db,$templatetypeid);
	if (!$myrow=sql_fetch_assoc($rs)){
		$query="update ".TABLENAME_TEMPLATETYPES." set activetemplateid=? where templatetypeid=?";
		sql_prep($query,$db,array($templateid,$templatetypeid));	
	}

	logaction("added Template #$templateid $templatename",
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
	
	header('newrecid:'.$templateid);
	header('newkey:template_'.$templateid);
	header('newloadfunc: inittemplatetexteditor("'.$templateid.'");reloadview("codegen.templates","templatelist");refreshtab("templatetype_'.$templatetypeid.'",1);');
	header('newparams:showtemplate&templateid='.$templateid);
	
	showtemplate($templateid);
}

