<?php

function updatetemplate_rectitle($ctx=null, $templateid=null){
	if (!isset($templateid)) $templateid=GETVAL('templateid',$ctx);
	if (isset($ctx)) $db=$ctx->db; else global $db;

	gsguard($ctx, $templateid,array('templatetypes','templates'),array('templatetypeid-templatetypeid','templateid'));

	$templatename=SGET('templatename',1,$ctx);
	
	$query="select templatename from templates where templateid=?";
	$rs=sql_prep($query,$db,array($templateid));
	$before=sql_fetch_assoc($rs);
	
	if ($before['templatename']!=$templatename){
		$query="update templates set templatename=? where templateid=?";
		sql_prep($query,$db,array($templatename,$templateid));
	
		$dbchanges=array('templateid'=>$templateid);	
		$after=array('templatename'=>$templatename);
		$diffs=diffdbchanges($before,$after);
		
		$dbchanges=array_merge($dbchanges,$diffs);
		$trace=array(
			'table'=>'templates',
			'recid'=>$templateid,
			'after'=>$after,
			'diffs'=>$diffs
		);
		gs_header($ctx, 'newtitle', tabtitle($templatename));
		logaction($ctx, "changed templatename of template_$templateid",$dbchanges,array('rectype'=>'template','recid'=>$templateid),0,$trace);
	
	} else {
		echo "No changes made";
	}
	
}

