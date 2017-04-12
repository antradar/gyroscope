<?php

function reauth(){
	
	global $db;
	global $salt;
	global $wssecret;
	
	
	$user=userinfo();
	$userid=$user['userid']+0;
	
	$query="select * from ".TABLENAME_USERS." where userid=$userid";
	$rs=sql_query($query,$db);
	
	$myrow=sql_fetch_assoc($rs);
	$login=$myrow['login'];
	$dispname=$myrow['dispname'];
	
	$active=$myrow['active'];
	$virtual=$myrow['virtualuser'];
	
	$groupnames=$myrow['groupnames'];
	$auth=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname);
		
	
	$wsskey=md5($wssecret.date('Y-n-j-H'));
	
	if (!$active||$virtual){
		setcookie('userid',NULL,time()-3600);
		setcookie('login',NULL,time()-3600);
		setcookie('dispname',NULL,time()-3600);		
		setcookie('auth',NULL,time()-3600);
		setcookie('groupnames',NULL,time()-3600);		
	} else {
		header('wsskey:'.$wsskey);
		setcookie('auth',$auth);
		setcookie('userid',$userid);
		setcookie('login',$login);
		setcookie('dispname',$dispname);
		setcookie('groupnames',$groupnames);	
	}
}