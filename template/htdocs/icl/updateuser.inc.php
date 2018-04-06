<?php

include 'icl/showuser.inc.php';
include 'icl/reauth.inc.php';

function updateuser(){
	global $userroles;
	global $dbsalt;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	if (!$user['groups']['accounts']) apperror('Access denied');
	
	$myuserid=$user['userid'];
	
	$userid=GETVAL('userid');	
	$login=GETSTR('login');
	$dispname=strip_tags(GETSTR('dispname'));
	$active=GETVAL('active');
	$virtual=GETVAL('virtual');
	$passreset=GETVAL('passreset');

	$newpass=$_POST['pass'];
	$np=encstr(md5($dbsalt.$newpass),$newpass.$dbsalt);

	$certname=QETSTR('certname');
	$needcert=GETVAL('needcert');
	$needkeyfile=GETVAL('needkeyfile');
	$cert=QETSTR('cert');
	
	$usesms=GETVAL('usesms');
	$smscell=GETSTR('smscell');

	$certhash=md5($dbsalt.$cert);

		
	$groupnames=GETSTR('groupnames');
	
	if ($virtual){
		$groupnames='users';
		$passreset=0;	
	}

	global $db;

	$query="select * from ".TABLENAME_USERS." where login='$login' and userid!=$userid";
	$rs=sql_query($query,$db);
	if ($myrow=sql_fetch_array($rs)){
		apperror('User already exists. Use a different login.');
	}

	$query="update ".TABLENAME_USERS." set login='$login', dispname='$dispname', active=$active, virtualuser=$virtual, usesms=$usesms,smscell='$smscell', needcert=$needcert, needkeyfile=$needkeyfile, passreset='$passreset', groupnames='$groupnames' ";
	if (!$virtual&&$newpass!='') $query.=", password='$np' ";
	if (trim($cert)!='') $query.=", certname='$certname', certhash='$certhash' ";

	$query.=" where userid=$userid and gsid=$gsid";
	$rs=sql_query($query,$db);

	if (sql_affected_rows($db,$rs)) logaction("updated User #$userid <u>$login</u>",array('userid'=>$userid,'login'=>"$login"),array('rectype'=>'reauth','recid'=>$userid));
	
	if ($userid==$myuserid){
		header('newlogin: '.tabtitle(stripslashes($login)));
		header('newdispname: '.tabtitle(stripslashes($dispname)));
	}

	reauth();
	showuser($userid);
	
	//cache_delete('gyroscopeblockedids_'.$gsid);
	
}
