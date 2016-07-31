<?php

function setaccountpass(){
	global $dbsalt;
	global $db;
	
	$user=userinfo();
	

	$oldpass=md5($dbsalt.QETSTR('oldpass'));
	$pass=encstr(md5($dbsalt.QETSTR('pass')),$dbsalt);
			

	$userid=$user['userid']+0;

	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$oldpass_=decstr($myrow['password'],$dbsalt);
	

	if ($oldpass!=$oldpass_) die(_tr('invalid_password'));

	$query="update users set password='$pass', passreset=0 where userid=$userid";
	sql_query($query,$db);

	tr('password_changed'); 
}