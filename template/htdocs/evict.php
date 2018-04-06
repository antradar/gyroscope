<?php

function evict_check(){
	
	$blockedids=evict_getblockedids();
	
	$user=userinfo();
	
	if (!isset($user)||!isset($user['login'])) return;
	$userid=$user['userid'];
	
	if (in_array($userid,$blockedids)) evict_user();
		
}

function evict_user(){
	global $_COOKIE;
	
	unset($_COOKIE['login']);
	unset($_COOKIE['userid']);
	unset($_COOKIE['auth']);
	unset($_COOKIE['groupnames']);
	
	setcookie('userid',NULL,time()-3600);
	setcookie('login',NULL,time()-3600);
	setcookie('auth',NULL,time()-3600);
	setcookie('groupnames',NULL,time()-3600);
	
}


function evict_getblockedids(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	//$blockedids=cache_get('gyroscopeblockedids_'.$gsid);
	//if (!isset($blockedids)){
		
		$blockedids=array();
		$query="select * from  ".TABLENAME_USERS." where gsid=$gsid and virtualuser=0 and active=0 and gsid=$gsid";
		$rs=sql_query($query,$db);
		while ($myrow=sql_fetch_assoc($rs)) array_push($blockedids,$myrow['userid']);
	
	//	cache_set('gyroscopeblockedids_'.$gsid,$blockedids,3600*24*7);	
	//}
	
	return $blockedids;	
}
