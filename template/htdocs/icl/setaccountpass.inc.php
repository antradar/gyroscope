<?php

function setaccountpass(){
	global $dbsalt;
	global $db;
	
	$user=userinfo();
	

	$needkeyfile=GETVAL('needkeyfile');
	$usesms=GETVAL('usesms');
	$smscell=GETSTR('smscell');
	
	$oldpass=md5($dbsalt.$_POST['oldpass']);
	$pass=encstr(md5($dbsalt.$_POST['pass']),$_POST['pass'].$dbsalt);
			

	$userid=$user['userid']+0;

	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$oldpass_=decstr($myrow['password'],$_POST['oldpass'].$dbsalt);
	

	if ($oldpass!=$oldpass_) die(_tr('invalid_password'));

	$query="update users set password='$pass', passreset=0, needkeyfile=$needkeyfile,usesms=$usesms,smscell='$smscell' where userid=$userid";
	sql_query($query,$db);

	tr('password_changed'); 
}