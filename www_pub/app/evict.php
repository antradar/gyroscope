<?php

function evict_check($ctx=null){
	
	$blockedids=evict_getblockedids($ctx);
	
	$user=userinfo($ctx);
		
	if (!isset($user)||!isset($user['login'])) return;
	$userid=$user['userid'];
	
	if (is_array($blockedids)&&in_array($userid,$blockedids)) evict_user($ctx);

	//uncomment to enable forced nightly flush
	//if ((date('H')==23&&date('i')>=55) || (date('H')==0&&date('i')<=5)) evict_user();	
		
}

function evict_user($ctx=null){
	
	if (isset($ctx)) $cookie=$ctx->request->cookie; else {global $_COOKIE; $cookie=$_COOKIE;}
	global $usehttps;
	
	if (isset($ctx)){
		$ctx->request->cookie=array();
		$cookie=array();
	}
		
	unset($_COOKIE['login']);
	unset($_COOKIE['userid']);
	unset($_COOKIE['auth']);
	unset($_COOKIE['groupnames']);
	
	gs_setcookie($ctx, 'userid',NULL,time()-3600,null,null,$usehttps,true);
	gs_setcookie($ctx, 'login',NULL,time()-3600,null,null,$usehttps,true);
	gs_setcookie($ctx, 'auth',NULL,time()-3600,null,null,$usehttps,true);
	gs_setcookie($ctx, 'groupnames',NULL,time()-3600,null,null,$usehttps,true);
	
}


function evict_getblockedids($ctx=null){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=isset($user['gsid'])?$user['gsid']:0;
	
	$blockedids=cache_get(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid,$ctx);
	if (!is_array($blockedids)){
		$blockedids=array();
		array_push($blockedids,'0');
		$query="select userid from  ".TABLENAME_USERS." where ".COLNAME_GSID."=? and virtualuser=0 and active=0";
		$rs=sql_prep($query,$db,$gsid);
		while ($myrow=sql_fetch_assoc($rs)) array_push($blockedids,$myrow['userid'].'');
	
		cache_set(TABLENAME_GSS.'gyroscopeblockedids_'.$gsid,$blockedids,3600*24*7,$ctx);	
	}
	return $blockedids;	
}
