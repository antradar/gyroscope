<?php

include 'icl/showtemplatetype.inc.php';

function updatetemplatetype(){
	$templatetypeid=SGET('templatetypeid');	
	$templatetypename=SQET('templatetypename');
	$templatetypekey=SQET('templatetypekey');
	$activetemplateid=SQET('activetemplateid');
	$plugins=SQET('templatetypeplugins');
	$classes=SQET('templatetypeclasses');

	if ($activetemplateid==-1) {
		$activetemplateid=null;
		if (!is_numeric($templatetypeid)) $activetemplateid=NULL_UUID;
	}

	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('updatetemplatetype_'.$templatetypeid);
	
	global $db;

	$query="select * from templatetypes where templatetypekey=? and templatetypeid!=? and gsid=? ";
	$rs=sql_prep($query,$db,array($templatetypekey,$templatetypeid,$gsid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.');
	
	$query="select * from templatetypes where templatetypeid=?";
	$rs=sql_prep($query,$db,array($templatetypeid));
	$before=sql_fetch_assoc($rs);
	
	
	$query="update templatetypes set templatetypename=? ";
	$params=array($templatetypename);
	if ($user['groups']['systemplate']){
		$query.=", templatetypekey=?, plugins=?, classes=? ";
		array_push($params,$templatetypekey,$plugins,$classes);
	}
	if ($activetemplateid>0||$activetemplateid==null) {
		$query.=", activetemplateid=? ";
		array_push($params,$activetemplateid);
	}
	
	$query.=" where templatetypeid=? and gsid=? ";
	array_push($params,$templatetypeid,$gsid);
	$rs=sql_prep($query,$db,$params);

	if (sql_affected_rows($db,$rs)){
		
		$query="select * from templatetypes where templatetypeid=?";
		$rs=sql_prep($query,$db,array($templatetypeid));
		$after=sql_fetch_assoc($rs);
		
		$dbchanges=array('templatetypeid'=>$templatetypeid,'templatetypename'=>"$templatetypename");
		$diffs=diffdbchanges($before,$after);
		$dbchanges=array_merge($dbchanges,$diffs);

		$trace=array(
			'table'=>'templatetypes',
			'recid'=>$templatetypeid,
			'after'=>$after,
			'diffs'=>$diffs
		);
		
		logaction("updated Template Class #$templatetypeid $templatetypename",
			$dbchanges,
			array('rectype'=>'templatetype','recid'=>$templatetypeid),0,$trace);
	}
	
	showtemplatetype($templatetypeid);
}
