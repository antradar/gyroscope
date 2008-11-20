<?

/*
	a passphrase (or a "salt") has to be set
	for enhanced security, consider using a salt that's derived from a calendar
*/
$salt='gyroscope_demo';

$dbsalt='gyroscope_demo'; //do not change this once it's set

/*
	this function should be called at the very beginning of the page
	if the user is forced to login
*/

function login($silent=false){
	global $salt;
	global $_COOKIE;
	global $_SERVER;
	
	//check cookie authenticity
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];
	$groupnames=$_COOKIE['groupnames'];
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	if (!isset($login)||$auth!=$auth_||$auth=='') {
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF']);
		die();
	}
}


function userinfo(){
	global $salt;
	global $_COOKIE;
	
	//check cookie authenticity
	$login=$_COOKIE['login'];
	$userid=$_COOKIE['userid'];
	$auth=$_COOKIE['auth'];
	$groupnames=$_COOKIE['groupnames'];
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login);
	
	if (!isset($login)||$auth!=$auth_||$auth=='') return array('groups'=>array());
	
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