<?php

include 'icl/showuser.inc.php';

function updateuser(){
	global $userroles;
	global $dbsalt;
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	$userid=GETVAL('userid');	
	$login=GETSTR('login');
	$active=GETVAL('active');
	$virtual=GETVAL('virtual');
	$passreset=GETVAL('passreset');

	$newpass=noapos(file_get_contents('php://input'));
	$np=md5($dbsalt.$newpass);
		
	$groupnames=GETSTR('groupnames');
	
	if ($virtual){
		$groupnames='users';
		$passreset=0;	
	}

	global $db;

	$query="select * from users where login='$login' and userid!=$userid";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_array($rs)){
		header('apperror: User already exists. Use a different login.');die();		
	}

	$query="update users set login='$login',active=$active, virtual=$virtual, passreset='$passreset', groupnames='$groupnames' ";
	if (!$virtual&&$newpass!='') $query.=", password='$np' ";
	$query.=" where userid=$userid";
	sql_query($query,$db);

	logaction("updated User #$userid <u>$login</u>",array('userid'=>$userid,'login'=>"$login"));

	showuser($userid);
}
