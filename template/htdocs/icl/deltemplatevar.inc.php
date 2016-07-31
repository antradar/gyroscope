<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function deltemplatevar(){
	$templatetypeid=GETVAL('templatetypeid');
	$templatevarid=GETVAL('templatevarid');
	
	
	global $db;

	$query="delete from templatevars where templatevarid=$templatevarid and templatetypeid=$templatetypeid";
	sql_query($query,$db);	
	
	listtemplatetypetemplatevars($templatetypeid);
}