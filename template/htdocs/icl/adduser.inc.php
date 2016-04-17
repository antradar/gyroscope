<?php

include 'icl/showuser.inc.php';

function adduser(){
	global $userroles;
	global $dbsalt;
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
		
	$login=GETSTR('login');
	$active=GETVAL('active');
	$virtual=GETVAL('virtual');
	$passreset=GETSTR('passreset');
	
	$newpass=noapos(file_get_contents('php://input'));
	$np=md5($dbsalt.$newpass);
		
	$groupnames=GETSTR('groupnames');	
	
	if ($virtual){
		$groupnames='users';
		$np=md5(time().rand(999,9999).'block');
		$passreset=0;
	}
	
	global $db;
	
	$query="insert into users (login,active,virtual,passreset,groupnames,password) values ('$login',$active,$virtual,'$passreset','$groupnames','$np') ";
	$rs=sql_query($query,$db);
	$userid=sql_insert_id($db,$rs)+0;

	if (!$userid) {
		header('apperror:Error creating User record');die();
	}
	
	logaction("added ".($virtual?'Virtual':'')." User #$userid <u>$login</u>",array('userid'=>$userid,'login'=>"$login"));
	
	header('newrecid:'.$userid);
	header('newkey:user_'.$userid);
	header('newparams:showuser&userid='.$userid);
	
	showuser($userid);
}

