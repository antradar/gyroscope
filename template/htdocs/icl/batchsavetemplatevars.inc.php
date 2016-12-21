<?php

include 'icl/listtemplatetypetemplatevars.inc.php';

function batchsavetemplatevars(){
	
	$templatetypeid=GETVAL('templatetypeid');
	
	$quickvars=explode("\n",$_POST['quickvars']);
		
	global $db;
	
	$query="delete from templatevars where templatetypeid=$templatetypeid";
	sql_query($query,$db);
	
	foreach ($quickvars as $vars){
		$vars=trim($vars);
		if ($vars=='') continue;
		$vars=explode('|',$vars);
		if (count($vars)!=2) continue;
		$varname=noapos($vars[0]);
		if ($varname[0]=='u') $varname='_'.$varname;	
		$vardesc=noapos($vars[1]);
		
		$query="insert into templatevars(templatetypeid,templatevarname,templatevardesc) values($templatetypeid,'$varname','$vardesc')";
		sql_query($query,$db);
			
	}
	


	listtemplatetypetemplatevars($templatetypeid);
}