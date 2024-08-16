<?php

include 'icl/listyubikeys.inc.php';

function delyubikey(){
	global $db;

	$user=userinfo();
	$userid=$user['userid'];
	
	$keyid=GETVAL('keyid');

	$query="delete from ".TABLENAME_YUBIKEYS." where keyid=? and userid=?";
	sql_prep($query,$db,array($keyid,$userid));
	
	//set useyubi to 0 if no devices are enrolled
	$query="select count(*) as kcount from ".TABLENAME_YUBIKEYS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$kcount=$myrow['kcount'];
	if ($kcount==0){//unset useyubi key
		$query="update ".TABLENAME_USERS." set useyubi=0 where userid=?";
		sql_prep($query,$db,array($userid));
		header('disableyubi:1');
		logaction("disabled Yubikey/2FA because all devices were removed",array('userid'=>$userid));
	}
		
	listyubikeys();
}
