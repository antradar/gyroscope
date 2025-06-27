<?php

include 'icl/showtemplate.inc.php';

function updatetemplate($ctx=null){
	$templateid=SGET('templateid',1,$ctx);	
	$templatename=SQET('templatename',1,$ctx);
	$templatetext=SQET('templatetext',1,$ctx);
	
	gsguard($ctx,$templateid,array(TABLENAME_TEMPLATETYPES,TABLENAME_TEMPLATES),array('templatetypeid-templatetypeid','templateid'));
	
	checkgskey('updatetemplate_'.$templateid,$ctx);

	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$query="select * from ".TABLENAME_TEMPLATES." where templateid=?";
	$rs=sql_prep($query,$db,$templateid);
	$before=sql_fetch_assoc($rs);	

	$query="update ".TABLENAME_TEMPLATES." set templatename=?,templatetext=? where templateid=?";
	sql_prep($query,$db,array($templatename,$templatetext,$templateid));
	
	$query="select * from ".TABLENAME_TEMPLATES." where templateid=?";
	$rs=sql_prep($query,$db,$templateid);
	$after=sql_fetch_assoc($rs);

	$dbchanges=array('templateid'=>$templateid,'templatename'=>"$templatename");
	$diffs=diffdbchanges($before,$after,array('templatetext'));
	$dbchanges=array_merge($dbchanges,$diffs);
	
	$trace=array(
		'table'=>'templates',
		'recid'=>$templateid,
		'after'=>$after,
		'diffs'=>$diffs
	);

			
	logaction($ctx, "updated Template #$templateid $templatename",
		$dbchanges,
		array('rectype'=>'template','recid'=>$templateid),0,$trace);

	showtemplate($ctx, $templateid);
}
