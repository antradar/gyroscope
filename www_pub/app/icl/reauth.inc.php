<?php
include 'models/myuser.reauth.php';

function reauth($ctx=null){
	
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	if (isset($ctx)) $salt=$ctx->salt; else global $salt;
	global $wssecret;
	global $usehttps;
	
	$user=userinfo($ctx);
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	$gsexpiry=0;
	$gstier=0;
	
	//every portalized table should have its own gsexpiry and gstier

	$myrow=myuser_reauth($ctx,$userid,$gsid);	
	
	$gsexpiry=intval($myrow['gsexpiry']);
	$gstier=intval($myrow['gstier']);
	
	$login=$myrow['login'];
	$dispname=$myrow['dispname'];
	
	$active=$myrow['active'];
	$virtual=$myrow['virtualuser'];
	
	$groupnames=$myrow['groupnames'];
	$auth=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
		
	
	$wsskey=md5($wssecret.$gsid.date('Y-n-j-H').$userid).'-'.$gsid.'-'.$userid;
	
	
	if (!$active||$virtual){
		gs_setcookie($ctx, 'userid',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gsid',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gsexpiry',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gstier',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'login',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'dispname',NULL,time()-3600,null,null,$usehttps,true);		
		gs_setcookie($ctx, 'auth',NULL,time()-3600,null,null,$usehttps,true);
		gs_setcookie($ctx, 'groupnames',NULL,time()-3600,null,null,$usehttps,true);		
	} else {
		gs_header($ctx, 'wsskey', $wsskey);
		gs_setcookie($ctx, 'auth',$auth,null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'userid',$userid,null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gsid',$gsid,null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gsexpiry',$gsexpiry,null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'gstier',$gstier,null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'login',$login,null,null,null,$usehttps,true);
		gs_setrawcookie($ctx, 'dispname',rawurlencode($dispname),null,null,null,$usehttps,true);
		gs_setcookie($ctx, 'groupnames',$groupnames,null,null,null,$usehttps,true);
	}
	
	

}
