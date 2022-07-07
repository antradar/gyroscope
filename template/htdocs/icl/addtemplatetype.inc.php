<?php

include 'icl/showtemplatetype.inc.php';

function addtemplatetype(){
	
	$templatetypename=SQET('templatetypename');
	$templatetypekey=SQET('templatetypekey');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('addtemplatetype');
	
	$query="select * from ".TABLENAME_TEMPLATETYPES." where templatetypekey=? and ".COLNAME_GSID."=? ";
	$rs=sql_prep($query,$db,array($templatetypekey,$gsid));
	if ($myrow=sql_fetch_assoc($rs)) apperror('Duplicate key. Pick a different key.');
	
	$query="insert into ".TABLENAME_TEMPLATETYPES." (".COLNAME_GSID.",templatetypename,templatetypekey,templatetypegroup,plugins,classes) values (?,?,?,?,?,?) ";
	ob_start();
	$rs=sql_prep($query,$db,array($gsid,$templatetypename,$templatetypekey,'','',''));
	$err=ob_get_clean();
	
	$templatetypeid=sql_insert_id($db,$rs);
	
	if (!$templatetypeid) {
		apperror(_tr('error_creating_record '.$err));
	}
	
	logaction("added Template Class #$templatetypeid $templatetypename",array('templatetypeid'=>$templatetypeid,'templatetypename'=>"$templatetypename"));
	
	header('newrecid:'.$templatetypeid);
	header('newkey:templatetype_'.$templatetypeid);
	header('newparams:showtemplatetype&templatetypeid='.$templatetypeid);
	
	showtemplatetype($templatetypeid);
}

