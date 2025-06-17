<?php

include_once 'icl/listtemplatetypetemplatevars.inc.php';

function deltemplatevar($ctx=null){
	$templatetypeid=SGET('templatetypeid');
	$templatevarid=SGET('templatevarid');
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	checkgskey('deltemplatevar-'.$templatevarid,$ctx);
	
	gsguard($ctx,$templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');

	$query="delete from ".TABLENAME_TEMPLATEVARS." where templatevarid=? and templatetypeid=?";
	sql_prep($query,$db,array($templatevarid,$templatetypeid));	
	
	listtemplatetypetemplatevars($ctx,$templatetypeid);
}