<?
/*
	a passphrase (or a "salt") has to be set
	comment out the timestamp for permanent login;
*/

define ('GYROSCOPE_VERSION', '6.9.1');
define ('GYROSCOPE_PROJECT', 'Gyroscope Project Template');
define ('VENDOR_VERSION','');
define ('VENDOR_INITIAL','');
define ('VENDOR_NAME','');

$saltroot='gyroscope_demo';
$salt=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-h');

$dbsalt='gyroscope_demo'; //do not change this once it's set


if (!is_callable(hash_equals)){
	function hash_equals($a,$b){return $a==$b;}	
}

/*
	this function should be called at the very beginning of the page
	if the user is forced to login
*/

function login($silent=false){
	global $salt;
	global $saltroot;
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-h',time()-3600);
	global $_COOKIE;
	global $_SERVER;
	
	//check cookie authenticity
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];

	$groupnames=$_COOKIE['groupnames'];
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login);
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))||$auth=='') {
		$tail='';
		if (isset($_GET['keynav'])) $tail='?keynav';
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF'].$tail); else {header('HTTP/1.0 403 Forbidden');header('X-STATUS: 403');}
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
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];
	$groupnames=$_COOKIE['groupnames'];
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-h',time()-3600);
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login);
	
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))) return array('groups'=>array());
	
	$info=array(
		'login'=>$_COOKIE['login'],
		'userid'=>$_COOKIE['userid'],
		'groups'=>array()
	);	
	
	$groups=explode('|',$_COOKIE['groupnames']);
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}
