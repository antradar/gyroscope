<?php

function updatetemplate_rectitle($templateid=null){
	if (!isset($templateid)) $templateid=GETVAL('templateid');
	global $db;

	gsguard($templateid,array('templatetypes','templates'),array('templatetypeid-templatetypeid','templateid'));

	$templatename=SGET('templatename');
	
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
		header('newtitle:'.tabtitle($templatename));
		logaction("changed templatename of template_$templateid",$dbchanges,array('rectype'=>'template','recid'=>$templateid),0,$trace);
	
	} else {
		echo "No changes made";
	}
	
}

