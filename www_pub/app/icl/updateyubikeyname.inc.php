<?php
include 'icl/listyubikeys.inc.php';

function updateyubikeyname(){

	global $db;
	$user=userinfo();
	$userid=$user['userid'];
	
	$keyid=GETVAL('keyid');
	$keyname=SQET('keyname');
	
	$query="update ".TABLENAME_YUBIKEYS." set keyname=? where keyid=? and userid=?";
	sql_prep($query,$db,array($keyname,$keyid,$userid));
	
	listyubikeys();	
}