<?php

function setaccountpass(){
	global $dbsalt;
	global $db;
	
	$user=userinfo();
	

	$oldpass=md5($dbsalt.QETSTR('oldpass'));
	$pass=md5($dbsalt.QETSTR('pass'));
			

	$userid=$user['userid']+0;

	$query="select * from users where userid=$userid and password='$oldpass'";
	$rs=sql_query($query,$db);

	if (!$myrow=sql_fetch_array($rs)) die('Invalid password');

	$query="update users set password='$pass', passreset=0 where userid=$userid";
	sql_query($query,$db);

	echo 'Password changed'; 
}