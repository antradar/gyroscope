<?php
include 'bcrypt.php';
include 'passtest.php';

function setaccountpass(){
	global $dbsalt;
	global $db;
	global $usehttps;
		
	$user=userinfo();
	$userid=$user['userid'];
	

	$needkeyfile=GETVAL('needkeyfile');
	$usesms=GETVAL('usesms');
	$smscell=SGET('smscell');
	
	$usega=GETVAL('usega');
	$usegamepad=GETVAL('usegamepad');
	$useyubi=GETVAL('useyubi');
	$yubimode=GETVAL('yubimode');
	
	$quicklist=GETVAL('quicklist');
	$darkmode=GETVAL('darkmode');
	$dowoffset=GETVAL('dowoffset');
	
	setcookie('dowoffset',$dowoffset,time()+3600*24*30*6,null,null,$usehttps,true); //6 months
	
	//set useyubi to 0 if no devices are enrolled
	$query="select count(*) as kcount from ".TABLENAME_YUBIKEYS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$kcount=$myrow['kcount'];
	if (!$kcount) $useyubi=0;	
	
	$rawpass=$_POST['pass'];
	
	if ($_POST['oldpass']!=''){
		$passcheck=passtest($rawpass);
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.');	
	}
	

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
	$query.=" needkeyfile=?,usesms=?,smscell=?, usega=?, usegamepad=?, useyubi=?, yubimode=?, quicklist=?, darkmode=?, dowoffset=? where userid=?";
	array_push($params,$needkeyfile,$usesms,$smscell,$usega,$usegamepad,$useyubi,$yubimode, $quicklist, $darkmode, $dowoffset, $userid);
	sql_prep($query,$db,$params);

	if ($_POST['oldpass']=='') echo 'Account settings updated'; else tr('password_changed'); 
}