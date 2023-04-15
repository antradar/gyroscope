<?php
include 'icl/showdefleftcontent.inc.php';

function setmyquicklist(){
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	$quicklist=GETVAL('quicklist');
	$silent=intval(SGET('silent'));
	
	global $db;
	
	$query="update ".TABLENAME_USERS." set quicklist=? where userid=?";
	
	sql_prep($query,$db,array($quicklist,$userid));
	
	if (!$silent) showdefleftcontent();
		
}