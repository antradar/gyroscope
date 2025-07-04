<?php

include 'icl/showuser.inc.php';
include 'bcrypt.php';
include 'passtest.php';

function adduser($ctx=null){
	global $userroles;
	global $userrolelocks;
	global $dbsalt;
		
	//echo '<pre>'; print_r($_POST); echo '</pre>'; return;

	//return;	
		
		
	$user=userinfo($ctx);
	if (!$user['groups']['accounts']) apperror('Access denied',null,null,$ctx);
	$gsid=$user['gsid'];
	
	checkgskey('adduser',$ctx);
	
	//vendor auth 1
		
	$login=SGET('login',1,$ctx);
	$dispname=strip_tags(SGET('dispname',1,$ctx));
	$active=GETVAL('active',$ctx);
	$virtual=GETVAL('virtual',$ctx);
	$passreset=GETVAL('passreset',$ctx);
	
	$newpass=SQET('newpass',1,$ctx);
	//$np=encstr(md5($dbsalt.$newpass),$newpass.$dbsalt);	
	$np=password_hash($dbsalt.$newpass,PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
			
	$groupnames=SGET('groupnames',1,$ctx);	
	
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
		if ($passcheck['grade']==0) apperror('A weak password cannot be used.',null,null,$ctx);		
	}
			
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	
	$query="select * from ".TABLENAME_USERS." where lower(login)=lower(?)";
	$rs=sql_prep($query,$db,$login);
	if ($myrow=sql_fetch_assoc($rs)) apperror('User already exists. Use a different login.',null,null,$ctx);	
	
	//vendor auth 2

	$query="insert into ".TABLENAME_USERS." (".COLNAME_GSID.",login,dispname,active,virtualuser,passreset,groupnames,password) values (?,?,?,?,?,?,?,?) ";
	$rs=sql_prep($query,$db,array($gsid,$login,$dispname,$active,$virtual,$passreset,$groupnames,$np));
	$userid=sql_insert_id($db,$rs);
	if (!$userid) {
		apperror('Error creating User record',null,null,$ctx);
	}
	
	logaction($ctx,"added ".($virtual?'Virtual':'')." User #$userid $login",array('userid'=>$userid,'login'=>"$login"),null,0,array(
		'table'=>'users',
		'recid'=>$userid,
		'after'=>array(
			'login'=>$login
		),
		'diffs'=>array(
			'login'=>$login
		)
	),1);
	
	gs_header($ctx, 'newrecid',$userid);
	gs_header($ctx, 'newkey','user_'.$userid);
	gs_header($ctx, 'newparams','showuser&userid='.$userid);		

	cache_inc_entity_ver('user_'.$gsid);
	
	$loadfuncs='';

	gs_header($ctx, "newloadfunc", "if (!document.smartcard) gid('cardsettings_".$userid."').style.display='none';reloadview('core.users','userlist');$loadfuncs");
		
	showuser($ctx,$userid);
}

