<?php

function updatetemplatetype_rectitle($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=GETVAL('templatetypeid');
	global $db;

	gsguard($templatetypeid,'templatetypes','templatetypeid');

	$templatetypename=SGET('templatetypename');
	
	$query="select templatetypename from templatetypes where templatetypeid=?";
	$rs=sql_prep($query,$db,array($templatetypeid));
	$before=sql_fetch_assoc($rs);
	
	if ($before['templatetypename']!=$templatetypename){
		$query="update templatetypes set templatetypename=? where templatetypeid=?";
		sql_prep($query,$db,array($templatetypename,$templatetypeid));
	
		$dbchanges=array('templatetypeid'=>$templatetypeid);	
		$after=array('templatetypename'=>$templatetypename);
		$diffs=diffdbchanges($before,$after);
		
		$dbchanges=array_merge($dbchanges,$diffs);
		$trace=array(
			'table'=>'templatetypes',
			'recid'=>$templatetypeid,
			'after'=>$after,
			'diffs'=>$diffs
		);
		header('newtitle:'.tabtitle($templatetypename));
		logaction("changed templatetypename of templatetype_$templatetypeid",$dbchanges,array('rectype'=>'templatetype','recid'=>$templatetypeid),0,$trace);
	
	} else {
		echo "No changes made";
	}
	
}

