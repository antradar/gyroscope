<?php

function setaccountpass(){
	global $dbsalt;
	global $db;
	
	$user=userinfo();

	$oldpass=md5($dbsalt.GETSTR('oldpass'));
	$pass=md5($dbsalt.GETSTR('pass'));

	$userid=$user['userid']+0;

	$query="select * from users where userid=$userid and password='$oldpass'";
	$rs=sql_query($query,$db);

	if (!$myrow=sql_fetch_array($rs)) die('Invalid password');

	$query="update users set password='$pass' where userid=$userid";
	sql_query($query,$db);

	echo 'Password changed'; 
}