<?php

include_once 'icl/showtemplatetype.inc.php';

function updatetemplatetype($ctx=null){
	$templatetypeid=SGET('templatetypeid',1,$ctx);	
	$templatetypename=SQET('templatetypename',1,$ctx);
	$templatetypekey=SQET('templatetypekey',1,$ctx);
	$activetemplateid=SQET('activetemplateid',1,$ctx);
	$plugins=SQET('templatetypeplugins',1,$ctx);
	$classes=SQET('templatetypeclasses',1,$ctx);

	if ($activetemplateid==-1) {
		$activetemplateid=null;
		if (!is_numeric($templatetypeid)) $activetemplateid=NULL_UUID;
	}

	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('updatetemplatetype_'.$templatetypeid,$ctx);
	
	if (isset($ctx)) $db=&$ctx->db; else global $db;

	$query="select * from templatetypes where templatetypekey=? and templatetypeid!=? and gsid=? ";
	$rs=sql_prep($query,$db,array($templatetypekey,$templatetypeid,$gsid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.',null,null,$ctx);
	
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
		
		logaction($ctx, "updated Template Class #$templatetypeid $templatetypename",
			$dbchanges,
			array('rectype'=>'templatetype','recid'=>$templatetypeid),0,$trace,4);
	}
	
	showtemplatetype($ctx, $templatetypeid);
}
