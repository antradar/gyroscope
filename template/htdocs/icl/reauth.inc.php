<?php

function reauth(){
	
	global $db;
	global $salt;
	global $wssecret;
	global $usehttps;
	
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	$gsexpiry=0;
	$gstier=0;
	
	//every portalized table should have its own gsexpiry and gstier
	
	$query="select gsexpiry,gstier,login,dispname,active,virtualuser,groupnames from 
	".TABLENAME_USERS.",".TABLENAME_GSS." 
	where userid=? and ".TABLENAME_USERS.".".COLNAME_GSID."=?
	and ".TABLENAME_USERS.".".COLNAME_GSID."=".TABLENAME_GSS.".".COLNAME_GSID;
	
	$rs=sql_prep($query,$db,array($userid,$gsid));
		
	$myrow=sql_fetch_assoc($rs);
	
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
		setcookie('userid',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('gsid',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('gsexpiry',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('gstier',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('login',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('dispname',NULL,time()-3600,null,null,$usehttps,true);		
		setcookie('auth',NULL,time()-3600,null,null,$usehttps,true);
		setcookie('groupnames',NULL,time()-3600,null,null,$usehttps,true);		
	} else {
		header('wsskey:'.$wsskey);
		setcookie('auth',$auth,null,null,null,$usehttps,true);
		setcookie('userid',$userid,null,null,null,$usehttps,true);
		setcookie('gsid',$gsid,null,null,null,$usehttps,true);
		setcookie('gsexpiry',$gsexpiry,null,null,null,$usehttps,true);
		setcookie('gstier',$gstier,null,null,null,$usehttps,true);
		setcookie('login',$login,null,null,null,$usehttps,true);
		setrawcookie('dispname',rawurlencode($dispname),null,null,null,$usehttps,true);
		setcookie('groupnames',$groupnames,null,null,null,$usehttps,true);
	}
}
