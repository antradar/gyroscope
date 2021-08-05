<?php
include 'bcrypt.php';
include 'passtest.php';

function setaccountpass(){
	global $dbsalt;
	global $db;
	
	$user=userinfo();
	

	$needkeyfile=GETVAL('needkeyfile');
	$usesms=GETVAL('usesms');
	$smscell=SGET('smscell');
	
	$usega=GETVAL('usega');
	$usegamepad=GETVAL('usegamepad');
	
	
	$rawpass=$_POST['pass'];
	
	if ($_POST['oldpass']!=''){
		$passcheck=passtest($rawpass);
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.');	
	}
	
	$userid=$user['userid'];

	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	
	if ($_POST['oldpass']!=''&&!password_verify($dbsalt.$_POST['oldpass'],$myrow['password'])) die('invalid password');

	$params=array();
	$query="update ".TABLENAME_USERS." set ";
	if ($_POST['oldpass']!='') {
		$pass=password_hash($dbsalt.$_POST['pass'],PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));		
		$query.=" password=?, passreset=0, ";
		array_push($params,$pass);
	}
	$query.=" needkeyfile=?,usesms=?,smscell=?, usega=?, usegamepad=? where userid=?";
	array_push($params,$needkeyfile,$usesms,$smscell,$usega,$usegamepad, $userid);
	sql_prep($query,$db,$params);

	if ($_POST['oldpass']=='') echo 'Account settings updated'; else tr('password_changed'); 
}