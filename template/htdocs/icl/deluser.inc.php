<?php

function deluser(){
	$userid=GETVAL('userid');
	global $db;
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
		
	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid user record');
	
	$login=$myrow['login'];
	
	$query="delete from users where userid=$userid";
	sql_query($query,$db);
	
	logaction("deleted User #$userid <u>$login</u>",array('userid'=>$userid,'login'=>$login));
}
