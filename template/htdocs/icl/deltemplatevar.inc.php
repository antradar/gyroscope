<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function deltemplatevar(){
	$templatetypeid=GETVAL('templatetypeid');
	$templatevarid=GETVAL('templatevarid');
	
	
	global $db;
	
	gsguard($templatetypeid,'templatetypes','templatetypeid');

	$query="delete from templatevars where templatevarid=$templatevarid and templatetypeid=$templatetypeid";
	sql_query($query,$db);	
	
	listtemplatetypetemplatevars($templatetypeid);
}