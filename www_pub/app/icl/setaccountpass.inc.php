<?php
include 'bcrypt.php';
include 'passtest.php';

function setaccountpass($ctx=null){
	global $dbsalt;
	if (isset($ctx)) $db=$ctx->db; else global $db;
	global $usehttps;
		
	$user=userinfo($ctx);
	$userid=$user['userid'];
	$gsid=$user['gsid'];

	$needkeyfile=GETVAL('needkeyfile',$ctx);
	$usesms=GETVAL('usesms',$ctx);
	$smscell=SGET('smscell',1,$ctx);
	
	$usega=GETVAL('usega',$ctx);
	$usegamepad=GETVAL('usegamepad',$ctx);
	$useyubi=GETVAL('useyubi',$ctx);
	$yubimode=GETVAL('yubimode',$ctx);
	
	$quicklist=GETVAL('quicklist',$ctx);
	$darkmode=GETVAL('darkmode',$ctx);
	$dowoffset=GETVAL('dowoffset',$ctx);
	
	gs_setcookie($ctx,'dowoffset',$dowoffset,time()+3600*24*30*6,null,null,$usehttps,true); //6 months
	
	//set useyubi to 0 if no devices are enrolled
	$query="select count(*) as kcount from ".TABLENAME_YUBIKEYS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$kcount=$myrow['kcount'];
	if (!$kcount) $useyubi=0;	
	
	$rawpass=SQET('pass',0,$ctx);
	
	if (SQET('oldpass',0,$ctx)!=''){
		$passcheck=passtest($rawpass);
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.',null,null,$ctx);	
	}
	

	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	
	if (SQET('oldpass',0,$ctx)!=''&&!password_verify($dbsalt.SQET('oldpass',0,$ctx),$myrow['password'])) apperror('invalid password',null,null,$ctx);

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

	cache_delete(TABLENAME_GSS.'_'.$userid.'-'.$gsid);
	cache_inc_entity_ver('user_'.$gsid);
	
	if (SQET('oldpass',0,$ctx)=='') echo 'Account settings updated'; else tr('password_changed');
	
	logaction($ctx,"changed own password",array(),array('rectype'=>'account','recid'=>0),0,null,1);
	 
}