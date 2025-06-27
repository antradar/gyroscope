<?php

include_once 'icl/listtemplatetypetemplatevars.inc.php';

function batchsavetemplatevars($ctx=null){
	
	$templatetypeid=SGET('templatetypeid',1,$ctx);
	
	$quickvars=explode("\n",$_POST['quickvars']);
		
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	checkgskey('batchsavetemplatevars_'.$templatetypeid,$ctx);

	gsguard($ctx,$templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
			
	$query="delete from ".TABLENAME_TEMPLATEVARS." where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	foreach ($quickvars as $vars){
		$vars=trim($vars);
		if ($vars=='') continue;
		$vars=explode('|',$vars);
		if (count($vars)!=2) continue;
		$varname=strip_tags($vars[0]);
		if ($varname[0]=='u') $varname='_'.$varname;	
		$vardesc=strip_tags($vars[1]);
		
		$query="insert into ".TABLENAME_TEMPLATEVARS." (templatetypeid,templatevarname,templatevardesc) values(?,?,?)";
		sql_prep($query,$db,array($templatetypeid,$varname,$vardesc));
			
	}
	


	listtemplatetypetemplatevars($ctx,$templatetypeid);
}