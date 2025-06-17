<?php
include 'icl/reauth.inc.php';

function deluser($ctx=null){
	$userid=SGET('userid');
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	if (!$user['groups']['accounts']) die('Access denied');
	
	checkgskey('deluser_'.$userid,$ctx);
		
	$query="select * from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	if (!$myrow=sql_fetch_array($rs)) die('Invalid user record');
	
	$login=$myrow['login'];
	
	$query="delete from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	sql_prep($query,$db,array($userid,$gsid));
	
	logaction($ctx, "deleted User #$userid $login",array('userid'=>$userid,'login'=>"$login"),array('rectype'=>'reauth','recid'=>$userid),0,null,1);
	cache_delete(TABLENAME_GSS.'_'.$userid.'-'.$gsid);
	cache_inc_entity_ver('user_'.$gsid);
	
	reauth($ctx);
	
	cache_delete(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid);
	cache_delete(TABLENAME_GSS.'gyroscopebinblockedids_'.$gsid);
	
}
