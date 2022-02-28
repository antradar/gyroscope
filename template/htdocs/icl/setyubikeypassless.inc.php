<?php

function setyubikeypassless(){
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	$keyid=GETVAL('keyid');
	$passless=GETVAL('passless');
	
	$query="update ".TABLENAME_YUBIKEYS." set passless=? where userid=? and keyid=?";
	$rs=sql_prep($query,$db,array($passless,$userid,$keyid));
	
	echo "passless settings changed";

}