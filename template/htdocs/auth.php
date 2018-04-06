<?php
define ('GYROSCOPE_VERSION', '13.4');

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
$salt=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-H');

$dbsalt='gyroscope_demo__'; //do not change this once it's set

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
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-H',time()-3600);
	global $_COOKIE;
	global $_SERVER;
	
	//check cookie authenticity
	$login=isset($_COOKIE['login'])?$_COOKIE['login']:null;
	$dispname=isset($_COOKIE['dispname'])?$_COOKIE['dispname']:null;
	$userid=isset($_COOKIE['userid'])?$_COOKIE['userid']:null;
	$gsid=isset($_COOKIE['gsid'])?$_COOKIE['gsid']:null;
	$gsexpiry=isset($_COOKIE['gsexpiry'])?$_COOKIE['gsexpiry']:null;
	$gstier=isset($_COOKIE['gstier'])?$_COOKIE['gstier']:null;
	$auth=isset($_COOKIE['auth'])?$_COOKIE['auth']:null;

	$groupnames=isset($_COOKIE['groupnames'])?$_COOKIE['groupnames']:null;
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname.$salt2.$gsid.$salt2.$gsexpiry.$salt2.$gstier);
		
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))||$auth=='') {
				
		$tail='';
		if (isset($_GET['keynav'])) $tail='?keynav';
				
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF'].$tail); else {header('HTTP/1.0 403 Forbidden');header('X-STATUS: 403');die('.');}
		die();
	}
	if ($auth==$auth2_){
		setcookie('auth',$auth_,null,null,null,null,true);
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
	$gsid=isset($_COOKIE['gsid'])?$_COOKIE['gsid']:null;
	$gsexpiry=isset($_COOKIE['gsexpiry'])?$_COOKIE['gsexpiry']:null;	
	$gstier=isset($_COOKIE['gstier'])?$_COOKIE['gstier']:null;	
	$auth=isset($_COOKIE['auth'])?$_COOKIE['auth']:null;
		
	$groupnames=isset($_COOKIE['groupnames'])?$_COOKIE['groupnames']:null;
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-H',time()-3600);
		
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname.$salt2.$gsid.$salt2.$gsexpiry.$salt2.$gstier);
		
	
	
	if (!isset($login)||!isset($auth)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))) return array('groups'=>array());
	
	$info=array(
		'login'=>$_COOKIE['login'],
		'dispname'=>$_COOKIE['dispname'],
		'userid'=>$_COOKIE['userid'],
		'gsid'=>$_COOKIE['gsid'],
		'gsexpiry'=>$_COOKIE['gsexpiry'],
		'gstier'=>$_COOKIE['gstier'],
		'groups'=>array()
	);	
	
	$groups=explode('|',$_COOKIE['groupnames']);
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}

function gsguard($val,$tables,$keys,$extfields=''){
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	if (!is_numeric($val)) $val="'$val'";
	
	if (!is_array($tables)) $tables=array($tables);
	if (!is_array($keys)) $keys=array($keys);
	
	if (count($tables)!=count($keys)) apperror('gsguard: parameter count mismatch');
	
	$maintable=$tables[0];
	$mainkeys=explode('-',$keys[0]);
	$mainkey=$mainkeys[0];
	
	$tailtable=$tables[count($tables)-1];
	$tailkey=$keys[count($keys)-1];
		
	if ($extfields!='') $extfields=','.trim($extfields,',');

	$query="select ${maintable}.${mainkey} $extfields from $maintable";
	
	for ($i=1;$i<count($tables);$i++) $query.=', '.$tables[$i];
	
	$query.=" where ${maintable}.gsid=$gsid";
	
	for ($i=1;$i<count($keys);$i++) {
		$kparts=explode('-',$keys[$i-1]);
		$keya=$kparts[0];
		$keyb=$kparts[1];
		$query.=' and '.$tables[$i-1].'.'.$keya.'='.$tables[$i].'.'.$keyb;
	}
	
	$query.=" and ${tailtable}.${tailkey}=$val ";
			
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('gsguard: Access denied');

	return $myrow;
		
}
