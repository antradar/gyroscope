<?php

include 'icl/showtemplate.inc.php';

function addtemplate(){
	$templatetypeid=GETVAL('templatetypeid');
	$templatename=QETSTR('templatename');
		
	global $db;
	
	$query="insert into templates (templatetypeid,templatename) values ($templatetypeid,'$templatename') ";
	$rs=sql_query($query,$db);
	$templateid=sql_insert_id($db,$rs)+0;
	
	if (!$templateid) {
		header('apperror:'._tr('error_creating_record'));die();
	}
	

	logaction("added Template #$templateid <u>$templatename</u>",
		array('templateid'=>$templateid,'templatename'=>"$templatename"),
		array('rectype'=>'templatetypetemplates','recid'=>$templatetypeid));
	
	header('newrecid:'.$templateid);
	header('newkey:template_'.$templateid);
	header('newloadfunc: inittemplatetexteditor('.$templateid.');reloadview("codegen.templates","templatelist");ajxpgn("templatetypetemplates_'.$templatetypeid.'",document.appsettings.codepage+"?cmd=listtemplatetypetemplates&templatetypeid='.$templatetypeid.'");');
	header('newparams:showtemplate&templateid='.$templateid);
	
	showtemplate($templateid);
}

