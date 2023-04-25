<?php
include 'icl/reauth.inc.php';

function deluser(){
	$userid=SGET('userid');
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['accounts']) die('Access denied');
	
	checkgskey('deluser_'.$userid);
		
	$query="select * from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	if (!$myrow=sql_fetch_array($rs)) die('Invalid user record');
	
	$login=$myrow['login'];
	
	$query="delete from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	sql_prep($query,$db,array($userid,$gsid));
	
	logaction("deleted User #$userid $login",array('userid'=>$userid,'login'=>"$login"),array('rectype'=>'reauth','recid'=>$userid));
	reauth();
	
	cache_delete(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid);
	cache_delete(TABLENAME_GSS.'gyroscopebinblockedids_'.$gsid);
	
}
