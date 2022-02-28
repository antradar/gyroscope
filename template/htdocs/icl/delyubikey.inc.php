<?php

include 'icl/listyubikeys.inc.php';

function delyubikey(){
	global $db;

	$user=userinfo();
	$userid=$user['userid'];
	
	$keyid=GETVAL('keyid');

	$query="delete from ".TABLENAME_YUBIKEYS." where keyid=? and userid=?";
	sql_prep($query,$db,array($keyid,$userid));		
		
	listyubikeys();
}
