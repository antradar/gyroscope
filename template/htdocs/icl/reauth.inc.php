<?php

function reauth(){
	
	global $db;
	global $salt;
	global $wssecret;
	
	
	$user=userinfo();
	$userid=$user['userid']+0;
	$gsid=$user['gsid']+0;
	
	$query="select gsexpiry,gstier from gss where gsid=$gsid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	
	$gsexpiry=$myrow['gsexpiry']+0;
	$gstier=$myrow['gstier']+0;
	
	$query="select * from ".TABLENAME_USERS." where userid=$userid and gsid=$gsid ";
	$rs=sql_query($query,$db);
	
	$myrow=sql_fetch_assoc($rs);
	$login=$myrow['login'];
	$dispname=$myrow['dispname'];
	
	$active=$myrow['active'];
	$virtual=$myrow['virtualuser'];
	
	$groupnames=$myrow['groupnames'];
	$auth=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
		
	
	$wsskey=md5($wssecret.date('Y-n-j-H'));
	
	if (!$active||$virtual){
		setcookie('userid',NULL,time()-3600);
		setcookie('gsid',NULL,time()-3600);
		setcookie('gsexpiry',NULL,time()-3600);
		setcookie('gstier',NULL,time()-3600);
		setcookie('login',NULL,time()-3600);
		setcookie('dispname',NULL,time()-3600);		
		setcookie('auth',NULL,time()-3600);
		setcookie('groupnames',NULL,time()-3600);		
	} else {
		header('wsskey:'.$wsskey);
		setcookie('auth',$auth,null,null,null,null,true);
		setcookie('userid',$userid,null,null,null,null,true);
		setcookie('gsid',$gsid,null,null,null,null,true);
		setcookie('gsexpiry',$gsexpiry,null,null,null,null,true);
		setcookie('gstier',$gstier,null,null,null,null,true);
		setcookie('login',$login,null,null,null,null,true);
		setcookie('dispname',$dispname,null,null,null,null,true);
		setcookie('groupnames',$groupnames,null,null,null,null,true);
	}
}