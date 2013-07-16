<?php

function evict_check(){
	//get a list of usernames to evict. memcache recommended
	
	$logins=array();
	
	//$logins=array('admin'); //test; comment this out on live server
	
	$user=userinfo();
	$login=$user['login'];
	if (in_array($login,$logins)) evict_user($login);
		
}

function evict_user($login){
	global $_COOKIE;
	if (isset($_COOKIE['login'])) evict_clear($login);
	
	unset($_COOKIE['login']);
	unset($_COOKIE['userid']);
	unset($_COOKIE['auth']);
	unset($_COOKIE['groupnames']);
	
	setcookie('userid',NULL,time()-3600);
	setcookie('login',NULL,time()-3600);
	setcookie('auth',NULL,time()-3600);
	setcookie('groupnames',NULL,time()-3600);
	
}

function evict_set($login){
	//place an eviction flag
	
}

function evict_clear($login){
	//notify the server that the user has already been kicked out; clear the flag
		
}