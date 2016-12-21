<?php

define ('GYROSCOPE_VERSION', '10.2');

//remember to personalize the project name
define ('GYROSCOPE_PROJECT', 'Gyroscope Project Template');

//ignore vendor settings if you are not a certified solution provider
define ('VENDOR_VERSION',''); 
define ('VENDOR_INITIAL','');
define ('VENDOR_NAME','');

//ignore modual settings if the product is a non-shared, custom solution
define ('MOD_SERVER',''); //https://www.antradar.com/gyroscope_mods.php
define ('MOD_KEY','mod_demo123');

/*
	a passphrase (or a "salt") has to be set
	comment out the timestamp for permanent login;
*/

$saltroot='gyroscope_demo';
$salt=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-h');

$dbsalt='gyroscope_demo__'; //do not change this once it's set; salt length has to be 16, 24 or 32

$wssecret='asdf'; //sync this value in wss.php


if (!is_callable('hash_equals')){
	function hash_equals($a,$b){return $a==$b;}	
}

/*
	this function should be called at the very beginning of the page
	if the user is forced to login
*/

function login($silent=false){
	global $salt;
	global $saltroot;
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-h',time()-3600);
	global $_COOKIE;
	global $_SERVER;
	
	//check cookie authenticity
	$login=isset($_COOKIE['login'])?$_COOKIE['login']:null;
	$dispname=isset($_COOKIE['dispname'])?$_COOKIE['dispname']:null;
	$userid=isset($_COOKIE['userid'])?$_COOKIE['userid']:null;
	$auth=isset($_COOKIE['auth'])?$_COOKIE['auth']:null;

	$groupnames=isset($_COOKIE['groupnames'])?$_COOKIE['groupnames']:null;
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname);
	
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))||$auth=='') {
		$tail='';
		if (isset($_GET['keynav'])) $tail='?keynav';
				
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF'].$tail); else {header('HTTP/1.0 403 Forbidden');header('X-STATUS: 403');die('.');}
		die();
	}
	if ($auth==$auth2_){
		setcookie('auth',$auth_);
	}

}


function userinfo(){
	global $salt;
	global $saltroot;
	global $_COOKIE;
	
		//check cookie authenticity
	$login=isset($_COOKIE['login'])?$_COOKIE['login']:null;
	$dispname=isset($_COOKIE['dispname'])?$_COOKIE['dispname']:null;
	$userid=isset($_COOKIE['userid'])?$_COOKIE['userid']:null;
	$auth=isset($_COOKIE['auth'])?$_COOKIE['auth']:null;
		
	$groupnames=isset($_COOKIE['groupnames'])?$_COOKIE['groupnames']:null;
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-h',time()-3600);
		
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname);
		
	
	
	if (!isset($login)||!isset($auth)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))) return array('groups'=>array());
	
	$info=array(
		'login'=>$_COOKIE['login'],
		'dispname'=>$_COOKIE['dispname'],
		'userid'=>$_COOKIE['userid'],
		'groups'=>array()
	);	
	
	$groups=explode('|',$_COOKIE['groupnames']);
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}
