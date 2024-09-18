<?php

function setyubikeypassless(){
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	$keyid=GETVAL('keyid');
	$passless=GETVAL('passless');

	checkgskey('setyubikeypassless_'.$userid.'_'.$keyid);
	
	$query="update ".TABLENAME_YUBIKEYS." set passless=? where userid=? and keyid=?";
	$rs=sql_prep($query,$db,array($passless,$userid,$keyid));
	
	echo "passless settings changed";

}