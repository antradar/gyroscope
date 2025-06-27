<?php

function deltemplatetype($ctx=null){
	$user=userinfo($ctx);
	if (!$user['groups']['systemplate']) apperror('Access denied',null,null,$ctx);
	
	$templatetypeid=GETVAL('templatetypeid',$ctx);
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('deltemplatetype_'.$templatetypeid, $ctx);
	
	$query="select * from templatetypes where templatetypeid=? and gsid=?";
	$rs=sql_prep($query,$db,array($templatetypeid,$gsid));
	if (!$myrow=sql_fetch_array($rs)) apperror('Invalid templatetype record',null,null,$ctx);
	
	$templatetypename=$myrow['templatetypename'];
	
	$query="delete from templatetypes where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	$query="delete from templatevars where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	$query="delete from templates where templatetypeid=?";
	sql_prep($query,$db,$templatetypeid);
	
	logaction($ctx, "deleted Template Class #$templatetypeid $templatetypename",
		array('templatetypeid'=>$templatetypeid,'templatetypename'=>$templatetypename),
		array('rectype'=>'templatetype','recid'=>$templatetypeid),0,null,4);
}
