<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function addtemplatevar(){
	$templatetypeid=SGET('templatetypeid');
	$varname=strip_tags(SGET('varname'));
	if ($varname[0]=='u') $varname='_'.$varname;
	$vardesc=strip_tags(SGET('vardesc'));
	
	checkgskey('addtemplatevar_'.$templatetypeid);
	
	global $db;
	
	gsguard($templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
		
	$query="select * from ".TABLENAME_TEMPLATEVARS." where templatevarname=? and templatetypeid=?";
	$rs=sql_prep($query,$db,array($varname,$templatetypeid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('A variable of this name already exists. Use a different name.');
	
	$query="insert into ".TABLENAME_TEMPLATEVARS." (templatevarname, templatevardesc, templatetypeid) values (?,?,?)";
	sql_prep($query,$db,array($varname,$vardesc,$templatetypeid));
	
	listtemplatetypetemplatevars($templatetypeid);
}