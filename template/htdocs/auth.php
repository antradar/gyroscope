<?php
define ('GYROSCOPE_VERSION', '20.4');

//remember to personalize the project name
define ('GYROSCOPE_PROJECT', 'Gyroscope Starter');
define ('GYROSCOPE_SHORT_APP_NAME', 'GS');

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
$salt=$saltroot.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-H');

$dbsalt='gyroscope_demo__'; //do not change this once it's set

include 'gsreqkey.php';
if ($gsreqkey===''||$gsreqkey===null) die('Missing GS Req Key - incorrect configuration');

$blobkey='a_not_so_important_random_key';

//$wssecret is moved to lb.php

//$gsxkey //change this in lb.php

if (!is_callable('hash_equals')){
	function hash_equals($str1, $str2){
		if (strlen($str1) !== strlen($str2)) {
			return false;
		} else {
			$res = $str1 ^ $str2;
			$ret = 0;
			for ($i = strlen($res) - 1; $i >= 0; $i--)
			$ret |= ord($res[$i]);
			return !$ret;
		}
	}
}

/*
	this function should be called at the very beginning of the page
	if the user is forced to login
*/

function login($silent=false){
	global $usehttps;
	
	global $salt;
	global $saltroot;
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-H',time()-3600);
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
			
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))||$auth===''||$auth===null) {
				
		$tail='';
		if (isset($_GET['keynav'])) $tail='?keynav';
				
		if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF'].$tail); else {header('HTTP/1.0 403 Forbidden');header('X-STATUS: 403');die('.');}
		die();
	}
	
	if ($auth===$auth2_){
		setcookie('auth',$auth_,null,null,null,$usehttps,true);
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
	$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-H',time()-3600);
		
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname.$salt2.$gsid.$salt2.$gsexpiry.$salt2.$gstier);
		
	
	
	if (!isset($login)||!isset($auth)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))) return array('groups'=>array());
	
	$info=array(
		'login'=>stripslashes($_COOKIE['login']),
		'dispname'=>$_COOKIE['dispname'],
		'userid'=>$_COOKIE['userid'],
		'gsid'=>$_COOKIE['gsid'],
		'gsexpiry'=>$_COOKIE['gsexpiry'],
		'gstier'=>$_COOKIE['gstier'],
		'groups'=>array()
	);	
	
	$groups=explode('|',($_COOKIE['groupnames']??''));
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}

function gsguard($val,$tables,$keys,$extfields='',$nocache=0,$rootgskey=COLNAME_GSID){
		
	global $db;
	global $gsguard_cache;
	if (!isset($gsguard_cache)) $gsguard_cache=array();
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!is_numeric($val)) $val="'$val'";
	
	if (!is_array($tables)) $tables=array($tables);
	if (!is_array($keys)) $keys=array($keys);
	
	if (count($tables)!=count($keys)) apperror('gsguard: parameter count mismatch');
	
	$cachekey=$val.'-T-'.implode(',',$tables).'-K-'.implode(',',$keys);//.'-E-'.$extfields;
	$hit=0;$res=isset($gsguard_cache[$cachekey])?$gsguard_cache[$cachekey]:array();
	if (!$nocache&&is_array($res)&&count($res)>0) $hit=1;
	
	if ($extfields!=''){
		$exfs=explode(',',$extfields);
		foreach ($exfs as $k) {
			//echo "CACHE MISS: $k<br>"; //uncomment this line to identify keys that need to be set in an earlier query
			if (!in_array(trim($k),array_keys($res))) $hit=0;
		}
	}
	//$hit=0; //uncomment to disable gsguard cache
	if ($hit) {
		//echo "gsguard cache hit ";
		return $gsguard_cache[$cachekey];
	}
		
	$maintable=$tables[0];
	$mainkeys=explode('-',$keys[0]);
	$mainkey=$mainkeys[0];
	
	$tailtable=$tables[count($tables)-1];
	$tailkey=$keys[count($keys)-1];
		
	if ($extfields!='') $extfields=','.trim($extfields,',');

	$query="select ${maintable}.${mainkey} $extfields from $maintable";
	
	for ($i=1;$i<count($tables);$i++) $query.=', '.$tables[$i];
	
	$query.=" where ${maintable}.".$rootgskey."='$gsid'";
	
	for ($i=1;$i<count($keys);$i++) {
		$kparts=explode('-',$keys[$i-1]);
		$keya=$kparts[0];
		$keyb=$kparts[1];
		$query.=' and '.$tables[$i-1].'.'.$keya.'='.$tables[$i].'.'.$keyb;
	}
	
	$query.=" and ${tailtable}.${tailkey}=$val ";
	$rs=sql_query($query,$db); //don't convert this to sql_prep; params are carefully filtered
	if (!$myrow=sql_fetch_assoc($rs)) apperror('gsguard: Access denied');
	
	if (isset($res)) $myrow=array_merge($res,$myrow);
	$gsguard_cache[$cachekey]=$myrow;
	return $myrow;
		
}

function makegskey($verb,$groupnames=''){
	global $gsreqkey;
	global $_SERVER;
	global $_COOKIE;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	$gsfrac=preg_replace('/[^A-Za-z0-9-]/','',$_COOKIE['gsfrac']);
		
	$key=md5($gsfrac.$gsreqkey.'_'.$userid.'_'.$verb.'_'.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP']);
	if ($groupnames!=''){
		$found=0;
		
		$gns=explode(',',$groupnames);
		foreach ($gns as $gn){
			if (trim($gn)!=''&&in_array(trim($gn),array_keys($user['groups']))) {$found=1;break;}
		}
		
		if (!$found) return '';
	}
	
	
	return $key;
}

function emitgskey($verb,$groupnames=''){
	echo makegskey($verb,$groupnames);	
}

function checkgskey($verb){
	global $gsreqkey;
	global $_SERVER;

	$user=userinfo();
	$userid=$user['userid'];	
		
	//$key=$_SERVER['HTTP_X_GSREQ_KEY'];
	$key=isset($_POST['X-GSREQ-KEY'])?$_POST['X-GSREQ-KEY']:'';

		
	$gsfrac=preg_replace('/[^A-Za-z0-9-]/','',$_COOKIE['gsfrac']);
	
	$key_=md5($gsfrac.$gsreqkey.'_'.$userid.'_'.$verb.'_'.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP']);
	if ($key!==$key_) apperror('gskey: request denied');
}

function authreport($groupnames){
	$rawgroups=explode('|',$groupnames);
	$groups=array();
	foreach ($rawgroups as $group) $groups[$group]=1;
	
	$user=userinfo();
		
	foreach ($user['groups'] as $ugroup=>$val){
		if (isset($groups[$ugroup])&&$groups[$ugroup]) return;	
	}
	
	apperror('Report access denied');
	
}
