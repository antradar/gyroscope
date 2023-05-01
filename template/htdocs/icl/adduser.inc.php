<?php

include 'icl/showuser.inc.php';
include 'bcrypt.php';
include 'passtest.php';

function adduser(){
	global $userroles;
	global $userrolelocks;
	global $dbsalt;
		
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	$gsid=$user['gsid'];
	
	checkgskey('adduser');
	
	//vendor auth 1
		
	$login=SGET('login');
	$dispname=strip_tags(SGET('dispname'));
	$active=GETVAL('active');
	$virtual=GETVAL('virtual');
	$passreset=GETVAL('passreset');
	
	$newpass=$_POST['newpass'];
	//$np=encstr(md5($dbsalt.$newpass),$newpass.$dbsalt);	
	$np=password_hash($dbsalt.$newpass,PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
			
	$groupnames=SGET('groupnames');	
	
	$gnames=explode('|',$groupnames);
	foreach ($gnames as $idx=>$gname){
		if (!isset($userroles[$gname])) unset($gnames[$idx]);
		if (in_array($gname,$userrolelocks)){
			if (!isset($user['groups'][$gname])) unset($gnames[$idx]);
		}
	}
	
	$groupnames=implode('|',$gnames);
		
	if ($virtual){
		$groupnames='users';
						
		$np=password_hash($dbsalt.$np,PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
		
		$passreset=0;
	} else {
		$passcheck=passtest($newpass);
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.');		
	}
		
	global $db;
	
	$query="select * from ".TABLENAME_USERS." where lower(login)=lower(?)";
	$rs=sql_prep($query,$db,$login);
	if ($myrow=sql_fetch_assoc($rs)) apperror('User already exists. Use a different login.');
	
	//vendor auth 2

	$query="insert into ".TABLENAME_USERS." (".COLNAME_GSID.",login,dispname,active,virtualuser,passreset,groupnames,password) values (?,?,?,?,?,?,?,?) ";
	$rs=sql_prep($query,$db,array($gsid,$login,$dispname,$active,$virtual,$passreset,$groupnames,$np));
	$userid=sql_insert_id($db,$rs);
	if (!$userid) {
		apperror('Error creating User record');
	}
	
	logaction("added ".($virtual?'Virtual':'')." User #$userid $login",array('userid'=>$userid,'login'=>"$login"),null,0,array(
		'table'=>'users',
		'recid'=>$userid,
		'after'=>array(
			'login'=>$login
		),
		'diffs'=>array(
			'login'=>$login
		)
	));
	
	header('newrecid:'.$userid);
	header('newkey:user_'.$userid);
	header('newparams:showuser&userid='.$userid);

	$loadfuncs='';

	//vendor auth 3

	header("newloadfunc: if (!document.smartcard) gid('cardsettings_".$userid."').style.display='none';reloadview('core.users','userlist');$loadfuncs");
	
	showuser($userid);
}

