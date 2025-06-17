<?php

include_once 'icl/listtemplatetypetemplatevars.inc.php';

function addtemplatevar($ctx=null){
	$templatetypeid=SGET('templatetypeid');
	$varname=strip_tags(SGET('varname'));
	if ($varname[0]=='u') $varname='_'.$varname;
	$vardesc=strip_tags(SGET('vardesc'));
	
	checkgskey('addtemplatevar_'.$templatetypeid,$ctx);
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	gsguard($ctx,$templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
		
	$query="select * from ".TABLENAME_TEMPLATEVARS." where templatevarname=? and templatetypeid=?";
	$rs=sql_prep($query,$db,array($varname,$templatetypeid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('A variable of this name already exists. Use a different name.',null,null,$ctx);
	
	$query="insert into ".TABLENAME_TEMPLATEVARS." (templatevarname, templatevardesc, templatetypeid) values (?,?,?)";
	sql_prep($query,$db,array($varname,$vardesc,$templatetypeid));
	
	listtemplatetypetemplatevars($ctx,$templatetypeid);
}