<?php
include 'icl/showdefleftcontent.inc.php';

function setmyquicklist(){
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	$quicklist=GETVAL('quicklist');
	
	global $db;
	
	$query="update ".TABLENAME_USERS." set quicklist=? where userid=?";
	
	sql_prep($query,$db,array($quicklist,$userid));
	
	showdefleftcontent();
		
}