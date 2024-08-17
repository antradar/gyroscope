<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function deltemplatevar(){
	$templatetypeid=SGET('templatetypeid');
	$templatevarid=SGET('templatevarid');
	
	global $db;
	
	checkgskey('deltemplatevar-'.$templatevarid);
	
	gsguard($templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');

	$query="delete from ".TABLENAME_TEMPLATEVARS." where templatevarid=? and templatetypeid=?";
	sql_prep($query,$db,array($templatevarid,$templatetypeid));	
	
	listtemplatetypetemplatevars($templatetypeid);
}