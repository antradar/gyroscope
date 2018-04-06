<?php

include 'encdec.php';

$usehttps=1; //enforcing HTTPS on production server, enable this on production server

$enableudf=0; //allow UDF editing, disable this on production server
$enablelivechat=1;
$chatkey='5BjFWzJY4yRiXVg7j5pDcHVJvtn1HLjN'; //default to Developer Support

date_default_timezone_set('America/Toronto');

define ('TABLENAME_USERS','users');
define ('TABLENAME_ACTIONLOG','actionlog');
define ('TABLENAME_REPORTS','reports');

$_SERVER['RAW_IP']=$_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_X_REAL_IP'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_FORWARDED_FOR'];

if ($_SERVER['REMOTE_ADDR']=='::1') {
	$_SERVER['REMOTE_ADDR6']=$_SERVER['REMOTE_ADDR'];
	$_SERVER['REMOTE_ADDR']='127.0.0.1';
}

if (isset($_SERVER['HTTP_X_REAL_IP'])) {
	$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
	$_SERVER['RAW_IP']=$_SERVER['HTTP_X_REAL_IP'];
}
if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])&&$_SERVER['HTTP_X_FORWARDED_SSL']=='on') $_SERVER['HTTPS']='on';
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$fparts=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
	$_SERVER['REMOTE_ADDR']=$fparts[0];	
}

if ($_SERVER['REMOTE_ADDR6']==$_SERVER['REMOTE_ADDR']||strpos($_SERVER['REMOTE_ADDR'],':')!==false){
	$ipparts=explode(':',$_SERVER['REMOTE_ADDR']);
	$nipparts=array();
	$ipmax=4; $ipidx=0;
	while ($ipidx<$ipmax){
		array_push($nipparts,$ipparts[$ipidx]);
		$ipidx++;	
	}
	$_SERVER['REMOTE_ADDR6']=$_SERVER['REMOTE_ADDR'];
	$_SERVER['REMOTE_ADDR']=implode(':',$nipparts);
}

if (trim($_SERVER['PHP_SELF'])=='') $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];

//include 'memcache.php'; cache_init();
