<?php

function evict_check(){
	
	$blockedids=evict_getblockedids();
	
	$user=userinfo();
	
	if (!isset($user)||!isset($user['login'])) return;
	$userid=$user['userid'];
	
	if (is_array($blockedids)&&in_array($userid,$blockedids)) evict_user();

	//uncomment to enable forced nightly flush
	//if ((date('H')==23&&date('i')>=55) || (date('H')==0&&date('i')<=5)) evict_user();	
		
}

function evict_user(){
	global $_COOKIE;
	global $usehttps;
	
	unset($_COOKIE['login']);
	unset($_COOKIE['userid']);
	unset($_COOKIE['auth']);
	unset($_COOKIE['groupnames']);
	
	setcookie('userid',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('login',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('auth',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('groupnames',NULL,time()-3600,null,null,$usehttps,true);
	
}


function evict_getblockedids(){
	global $db;
	
	$user=userinfo();
	$gsid=isset($user['gsid'])?$user['gsid']:0;
	
	$blockedids=cache_get(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid);
	if (!is_array($blockedids)){
		$blockedids=array();
		array_push($blockedids,'0');
		$query="select userid from  ".TABLENAME_USERS." where ".COLNAME_GSID."=? and virtualuser=0 and active=0";
		$rs=sql_prep($query,$db,$gsid);
		while ($myrow=sql_fetch_assoc($rs)) array_push($blockedids,$myrow['userid'].'');
	
		cache_set(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid,$blockedids,3600*24*7);	
	}
	return $blockedids;	
}
