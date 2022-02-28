<?php

//include 'encdec.php';
//include 'bcrypt.php';

$usehttps=1; //enforcing HTTPS on production server, enable this on production server
$stablecf=0; //set to 1 when behind CloudFlare
$enableudf=0; //allow UDF editing, disable this on production server

$enablelivechat=1;
$livechatmode='gschat'; //ze, zopim
$chatkey='gyroscope-support';//zopim:'5BjFWzJY4yRiXVg7j5pDcHVJvtn1HLjN'; //default to Developer Support
$portalchannel=5; //Channel 5 on Antradar's chat server
$targetgsid=1; //gsid in the receiving multi-tenant server; typically "1" in a single instance
$targetgsauth='12ee3b09f3d9893e508a7fd8b9f4b405f1fee9ae'; //sha1($targetgsid.$target_chatkey), target_chatkey is undisclosed
$chatserver_http='https://www.antradar.com/gyrosgo/gschat/chatd.gsb';
$chatserver_wss='wss://www.antradar.com:2096';
	
$encclientid='pubtest'; //community key for testing only
$encapikey='yK2aP2vE1rF8tN6m';
$encapisecret='pG6mJ1sA6qM8oX7zW4mR4cD7qV1jH3mI'; //don't change this once it's set; do not rely on the shared key to store sensitive data as other developers might wipe out your keys!

$usewss=1; //set this to 1 to activate websocket sync
$WSS_INTERNAL_KEY='asdf-changeme'; //unset this to use legacy DB-driven WSS
$WSS_INTERNAL_HOST='127.0.0.1';
$WSS_INTERNAL_PORT='9999';


$wssecret='asdf'; //sync this value in wss.php or wsss.php

$GSX_ENABLED=0;
$GSX_SERVERS=array(
	'http://gyroscope/myservices.php'
);
$gsxkey='must_change_this';

$forceajxjs=0; //set to 1 to enable live reloading of JS during switch-over deployment, remember to set it back to 0

date_default_timezone_set('America/Toronto');

define ('NULL_UUID','00000000-0000-0000-0000-000000000000');

define ('TABLENAME_GSS','gss');
define ('COLNAME_GSID','gsid');
define ('TABLENAME_USERS','users');
define ('TABLENAME_USERHELPSPOTS','userhelpspots');
define ('TABLENAME_ACTIONLOG','actionlog');
define ('TABLENAME_REPORTS','reports');
define ('TABLENAME_TEMPLATETYPES','templatetypes');
define ('TABLENAME_TEMPLATES','templates');
define ('TABLENAME_TEMPLATEVARS','templatevars');
define ('TABLENAME_HELPTOPICS','helptopics');
define ('TABLENAME_HOMEDASHREPORTS','homedashreports');
define ('TABLENAME_YUBIKEYS','yubikeys');
define ('TABLENAME_CHATS','chats');
define ('TABLENAME_CHATMSGS','chatmsgs');


if (isset($_SERVER['HTTP_GSXIP'])&&$_SERVER['HTTP_GSXIP']!=''){
	$gsxauth=md5($gsxkey.'-'.$_GET['cmd'].'-'.date('Y-n-j-H'));
	$gsxauth_=md5($gsxkey.'-'.$_GET['cmd'].'-'.date('Y-n-j-H',time()-3600));
	if ($_SERVER['HTTP_GSXAUTH']!=$gsxauth&&$_SERVER['HTTP_GSXAUTH']!=$gsxauth_) die('gsx access denied');
	$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_GSXIP'];	//comment this out to completely disable gsx
}

$_SERVER['RAW_IP']=$_SERVER['REMOTE_ADDR'];
$_SERVER['O_IP']=$_SERVER['REMOTE_ADDR'];


if (isset($_SERVER['HTTP_X_REAL_IP'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_FORWARDED_FOR'];

if ($_SERVER['REMOTE_ADDR']==='::1') {
	$_SERVER['REMOTE_ADDR6']=$_SERVER['REMOTE_ADDR'];
	$_SERVER['REMOTE_ADDR']='127.0.0.1';
}

if (isset($_SERVER['HTTP_X_REAL_IP'])) {
	$_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
	$_SERVER['RAW_IP']=$_SERVER['HTTP_X_REAL_IP'];
}
if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])&&$_SERVER['HTTP_X_FORWARDED_SSL']==='on') $_SERVER['HTTPS']='on';
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$fparts=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
	$_SERVER['REMOTE_ADDR']=$fparts[0];	
}

if (strpos($_SERVER['O_IP'],':')!==false&&$_SERVER['O_IP']!=='::1'){
	$ipparts=explode(':',$_SERVER['O_IP']);
	$nipparts=array();
	$ipmax=4; $ipidx=0;
	while ($ipidx<$ipmax){
		array_push($nipparts,$ipparts[$ipidx]);
		$ipidx++;
	}
	$_SERVER['O_IP']=implode(':',$nipparts);	
}

if (isset($_SERVER['REMOTE_ADDR6'])&&($_SERVER['REMOTE_ADDR6']===$_SERVER['REMOTE_ADDR']||strpos($_SERVER['REMOTE_ADDR'],':')!==false)){
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

if (isset($_SERVER['HTTP_GSXIP'])&&$_SERVER['HTTP_GSXIP']!='') $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_GSXIP'];

if ($stablecf) $_SERVER['O_IP']=$_SERVER['REMOTE_ADDR'];


if (trim($_SERVER['PHP_SELF'])=='') $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];

//replace the following with memcache.php in production
include 'memcache_stub.php'; //'memcache_stub.php'; 
cache_init();

function _jsflag($flag){
	global $forceajxjs;
	
	if ($forceajxjs) return 'null';
	
	return $flag;	
}

function jsflag($flag){
	global $forceajxjs;
	
	if ($forceajxjs) echo 'null';
	else echo 'self.'.$flag;
}
