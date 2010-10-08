<?
/*
	a passphrase (or a "salt") has to be set
	comment out the timestamp for permanent login;
*/
$salt='gyroscope_demo'.$_SERVER['REMOTE_ADDR'].date('j');
$salt2='gyroscope_demo'.$_SERVER['REMOTE_ADDR'].(date('j')+1);

$dbsalt='gyroscope_demo'; //do not change this once it's set

/*
	this function should be called at the very beginning of the page
	if the user is forced to login
*/

function login($silent=false){
	global $salt;
	global $salt2;
	global $_COOKIE;
	global $_SERVER;
	
	//check cookie authenticity
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];
	$auth2=$_COOKIE['auth2'];
	$groupnames=$_COOKIE['groupnames'];
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login);
	if (!isset($login)||($auth!=$auth_&&$auth2!=$auth_)||$auth==''||$auth2=='') {
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF']);
		die();
	}
	if ($auth2==$auth_){
		setcookie('auth',$auth_);
		setcookie('auth2',$auth2_);
	}

}


function userinfo(){
	global $salt;
	global $salt2;
	global $_COOKIE;
	
	//check cookie authenticity
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];
	$groupnames=$_COOKIE['groupnames'];
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	
	if (!isset($login)||($auth!=$auth_&&$auth2!=$auth_)) return array('groups'=>array());
	
	$info=array(
		'login'=>$_COOKIE['login'],
		'userid'=>$_COOKIE['userid'],
		'groups'=>array()
	);	
	
	$groups=explode('|',$_COOKIE['groupnames']);
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}
?>