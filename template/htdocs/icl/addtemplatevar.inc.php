<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function addtemplatevar(){
	$templatetypeid=GETVAL('templatetypeid');
	$varname=GETSTR('varname');
	if ($varname[0]=='u') $varname='_'.$varname;
	$vardesc=GETSTR('vardesc');
	
	global $db;
	
	$query="select * from templatevars where templatevarname='$varname' and templatetypeid=$templatetypeid";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_assoc($rs)) apperror('A variable of this name already exists. Use a different name.');
	
	$query="insert into templatevars(templatevarname, templatevardesc, templatetypeid) values ('$varname','$vardesc',$templatetypeid)";
	sql_query($query,$db);
	
	listtemplatetypetemplatevars($templatetypeid);
}