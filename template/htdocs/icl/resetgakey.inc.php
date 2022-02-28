<?php
include 'icl/showaccount.inc.php';

function resetgakey(){
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	checkgskey('resetgakey');
	
	global $db;
	
	$query="update ".TABLENAME_USERS." set gakey='' where userid=? and ".COLNAME_GSID."=?";
	sql_prep($query,$db,array($userid,$gsid));
	
	showaccount();
		
}