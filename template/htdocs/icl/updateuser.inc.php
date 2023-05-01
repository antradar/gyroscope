<?php

include 'icl/showuser.inc.php';
include 'icl/reauth.inc.php';

include 'bcrypt.php';

include 'passtest.php';

function updateuser(){
	global $userroles;
	global $userrolelocks;
	global $dbsalt;
	
	//vendor auth 1
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['accounts']) apperror('Access denied');
	
	$myuserid=$user['userid'];
	
	$userid=SGET('userid');
	checkgskey('updateuser_'.$userid);
	
	$login=SGET('login');
	$dispname=strip_tags(SGET('dispname'));
	$active=GETVAL('active');
	$virtual=GETVAL('virtual');
	$passreset=GETVAL('passreset');
	
	$unlockga=GETVAL('unlockga');

	$newpass=$_POST['pass'];
	//$np=encstr(md5($dbsalt.$newpass),$newpass.$dbsalt);

	$certname=SQET('certname');
		
	$needcert=GETVAL('needcert');
	$needkeyfile=GETVAL('needkeyfile');
	$cert=strtoupper(SQET('cert'));
	
	$usesms=GETVAL('usesms');
	$smscell=SGET('smscell');
	
	$usegamepad=GETVAL('usegamepad');

	$certhash=md5($dbsalt.$cert);
		
	$groupnames=SGET('groupnames');
	

	global $db;
	
	$query="select * from ".TABLENAME_USERS." where login=? and userid!=?";
	$rs=sql_prep($query,$db,array($login,$userid));
	if ($myrow=sql_fetch_array($rs)){
		apperror('User already exists. Use a different login.');
	}
	
	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	
	$before=$myrow;
	
	//vendor auth 2
	
	
	$mygroupnames=array_flip(explode('|',$myrow['groupnames']));
	$lastvirtual=$myrow['virtualuser'];
				
	$gnames=explode('|',$groupnames);
	foreach ($gnames as $idx=>$gname){
		if (!isset($userroles[$gname])) unset($gnames[$idx]);
		if (in_array($gname,$userrolelocks)){
			if (!isset($user['groups'][$gname])&&!isset($mygroupnames[$gname])) unset($gnames[$idx]);
		}
	}

	foreach ($mygroupnames as $mygroupname=>$label){
		if ($mygroupname=='') continue;
		if (!isset($user['groups'][$mygroupname])&&in_array($mygroupname,$userrolelocks)&&!in_array($mygroupname,$gnames)){
			array_push($gnames,$mygroupname);	
		}
	}
			
	$groupnames=implode('|',$gnames);
		
	if ($virtual){
		$groupnames='users';
		$passreset=0;	
	}

	if ($lastvirtual&&!$virtual&&$newpass==''){
			apperror('A new set of passwords must be specified');
	}

	$query="update ".TABLENAME_USERS." set login=?, dispname=?, active=?, virtualuser=?, usesms=?,smscell=?, usegamepad=?, needcert=?, needkeyfile=?, passreset=?, groupnames=? ";
	$params=array($login,$dispname,$active,$virtual,$usesms,$smscell, $usegamepad, $needcert,$needkeyfile,$passreset,$groupnames);
	if (!$virtual&&$newpass!='') {
		
		$passcheck=passtest($newpass);
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.');
				
		$np=password_hash($dbsalt.$newpass,PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
		$query.=", password=? ";
		array_push($params,$np);
	}
	if (trim($cert)!='') {
		$query.=", certname=?, certhash=? ";
		array_push($params,$certname,$certhash);
	}
	
	$query.=" where userid=? and ".COLNAME_GSID."=?";
	array_push($params,$userid,$gsid);
	
	$rs=sql_prep($query,$db,$params);
	
	
	if ($unlockga){
		$query="update ".TABLENAME_USERS." set usega=0 where userid=? and ".COLNAME_GSID."=? ";
		sql_prep($query,$db,array($userid,$gsid));	
	}

	if (sql_affected_rows($db,$rs)) {
	
		$query="select * from ".TABLENAME_USERS." where userid=?";
		$rs=sql_prep($query,$db,$userid);

		$after=sql_fetch_assoc($rs);
		
		$dbchanges=array('userid'=>$userid,'login'=>"$login");

		
		$diffs=diffdbchanges($before,$after,array('groupnames','password'));
		$dbchanges=array_merge($dbchanges,$diffs);
		$trace=array(
			'table'=>'users',
			'recid'=>$userid,
			'after'=>$after,
			'diffs'=>$diffs
		);
								
		logaction("updated User #$userid $login",$dbchanges,array('rectype'=>'reauth','recid'=>$userid),0,$trace);
		logaction(null,null,array('rectype'=>'user','recid'=>$userid));
	}
	
	if ($userid==$myuserid){
		header('newlogin: '.tabtitle(stripslashes($login)));
		header('newdispname: '.tabtitle(stripslashes($dispname)));
	}

	reauth();
	showuser($userid);
	
	cache_delete(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid);
	cache_delete(TABLENAME_GSS.'gyroscopebinblockedids_'.$gsid);
	
}
