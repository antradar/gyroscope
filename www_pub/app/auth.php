<?php
define ('GYROSCOPE_VERSION', '23.0');

//remember to personalize the project name
define ('GYROSCOPE_PROJECT', 'Gyroscope Starter');
define ('GYROSCOPE_SHORT_APP_NAME', 'GS');
define ('DLC_COMPANY','%%Your Company%%');

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

global $saltroot;
global $salt;

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

function login($silent=false,$ctx=null){
	global $usehttps;

	global $saltroot;
		
	if (isset($ctx)) {
		$salt=$ctx->salt;
		$cookie=$ctx->request->cookie;
		$server=$ctx->server; 
	} else {
		global $salt;
		global $_COOKIE; $cookie=$_COOKIE;
		$server=$_SERVER;
	}
	
	
	$salt2=$saltroot.$server['REMOTE_ADDR'].'-'.$server['O_IP'].date('Y-m-j-H',time()-3600);
	
			
	//check cookie authenticity
	$login=isset($cookie['login'])?$cookie['login']:null;
	$dispname=isset($cookie['dispname'])?$cookie['dispname']:null;
	$userid=isset($cookie['userid'])?$cookie['userid']:null;
	$gsid=isset($cookie['gsid'])?$cookie['gsid']:null;
	$gsexpiry=isset($cookie['gsexpiry'])?$cookie['gsexpiry']:null;
	$gstier=isset($cookie['gstier'])?$cookie['gstier']:null;
	$auth=isset($cookie['auth'])?$cookie['auth']:null;

	$groupnames=isset($cookie['groupnames'])?$cookie['groupnames']:null;
	
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname.$salt2.$gsid.$salt2.$gsexpiry.$salt2.$gstier);
			
	if (!isset($login)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))||$auth===''||$auth===null) {
				
		$tail='';
		if (isset($_GET['keynav'])) $tail='?keynav';
				
		if (isset($ctx)){
			if (!$silent){
				$ctx->response->header('location','login.php?from='.$_SERVER['PHP_SELF'].$tail);
			} else {
				$ctx->response->status(403);
				$ctx->response->header('X-STATUS',403);
				$ctx->response->_ended=true;
				$ctx->response->end('.');
				return;	
			}
			
			$ctx->response->_ended=true;
			$ctx->response->end();
			return;
			
		} else {
			if (!$silent) header('location: login.php?from='.$_SERVER['PHP_SELF'].$tail); else {header('HTTP/1.0 403 Forbidden');header('X-STATUS: 403');die('.');}
			die();
		}
	}
	
	if ($auth===$auth2_){
		if ($ctx){
			$ctx->response->cookie('auth',$auth_,null,null,null,$usehttps,true);
		} else {
			setcookie('auth',$auth_,null,null,null,$usehttps,true);
		}
	}

}


function userinfo($ctx=null){
		
	global $saltroot;
	
	if (isset($ctx)) {
		$salt=$ctx->salt;
		$cookie=$ctx->request->cookie;
		$server=$ctx->server; 
	} else {
		global $salt;
		global $_COOKIE; $cookie=$_COOKIE;
		$server=$_SERVER;
	}
	
	
	
	//check cookie authenticity
	$login=isset($cookie['login'])?$cookie['login']:null;
	$dispname=isset($cookie['dispname'])?$cookie['dispname']:null;
	$userid=isset($cookie['userid'])?$cookie['userid']:null;
	$gsid=isset($cookie['gsid'])?$cookie['gsid']:null;
	$gsexpiry=isset($cookie['gsexpiry'])?$cookie['gsexpiry']:null;	
	$gstier=isset($cookie['gstier'])?$cookie['gstier']:null;	
	$auth=isset($cookie['auth'])?$cookie['auth']:null;
		
	$groupnames=isset($cookie['groupnames'])?$cookie['groupnames']:null;
	$salt2=$saltroot.$server['REMOTE_ADDR'].'-'.$server['O_IP'].date('Y-m-j-H',time()-3600);
		
	$auth_=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
	$auth2_=md5($salt2.$userid.$groupnames.$salt2.$login.$salt2.$dispname.$salt2.$gsid.$salt2.$gsexpiry.$salt2.$gstier);
		
	
	
	if (!isset($login)||!isset($auth)||(!hash_equals($auth,$auth_)&&!hash_equals($auth,$auth2_))) return array('groups'=>array());
	
	$info=array(
		'login'=>stripslashes($cookie['login']),
		'dispname'=>$cookie['dispname'],
		'userid'=>$cookie['userid'],
		'gsid'=>$cookie['gsid'],
		'gsexpiry'=>$cookie['gsexpiry'],
		'gstier'=>$cookie['gstier'],
		'groups'=>array()
	);	
	
	$groups=explode('|',($cookie['groupnames']??''));
	foreach ($groups as $group) $info['groups'][$group]=true;
	
	return $info;
}

function gsguard($ctx=null,$val,$tables,$keys,$extfields='',$nocache=0,$rootgskey=COLNAME_GSID){
		
	if (isset($ctx)) $db=$ctx->db; else global $db;
	if (isset($ctx)) $gsguard_cache=$ctx->gsguard_cache; else global $gsguard_cache;
	if (!isset($gsguard_cache)) $gsguard_cache=array();
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	if (!is_numeric($val)) $val="'$val'";
	
	if (!is_array($tables)) $tables=array($tables);
	if (!is_array($keys)) $keys=array($keys);
	
	if (count($tables)!=count($keys)) apperror('gsguard: parameter count mismatch',null,null,$ctx);
	
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

function makegskey($verb,$groupnames='',$ctx=null){
	global $gsreqkey;
	
	global $_SERVER;
	global $_COOKIE;
	
	$cookie=$_COOKIE;
	$server=$_SERVER;
	
	if (isset($ctx)) {
		$cookie=$ctx->request->cookie;
		$post=$ctx->request->post;
		$server=$ctx->server;
	}	
	
	$user=userinfo($ctx);
	$userid=$user['userid'];
	
	$gsfrac=preg_replace('/[^A-Za-z0-9-]/','',$cookie['gsfrac']);
		
	$key=md5($gsfrac.$gsreqkey.'_'.$userid.'_'.$verb.'_'.$server['REMOTE_ADDR'].'-'.$server['O_IP']);
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

function emitgskey($verb,$groupnames='',$ctx=null){
	echo makegskey($verb,$groupnames,$ctx);	
}

function checkgskey($verb,$ctx=null){
	global $gsreqkey;
	global $_SERVER;

	$user=userinfo($ctx);
	$userid=$user['userid'];
	
	$cookie=$_COOKIE;
	$post=$_POST;
	$server=$_SERVER;
	
	if (isset($ctx)) {
		$cookie=$ctx->request->cookie;
		$post=$ctx->request->post;
		$server=$ctx->server;
	}
		
	//$key=$_SERVER['HTTP_X_GSREQ_KEY'];
	$key=isset($post['X-GSREQ-KEY'])?$post['X-GSREQ-KEY']:'';

		
	$gsfrac=preg_replace('/[^A-Za-z0-9-]/','',$cookie['gsfrac']);
	
	$key_=md5($gsfrac.$gsreqkey.'_'.$userid.'_'.$verb.'_'.$server['REMOTE_ADDR'].'-'.$server['O_IP']);
	if ($key!==$key_) apperror('gskey: request denied',null,null,$ctx);
}

function authreport($groupnames,$ctx=null){
	$rawgroups=explode('|',$groupnames);
	$groups=array();
	foreach ($rawgroups as $group) $groups[$group]=1;
	
	$user=userinfo($ctx);
		
	foreach ($user['groups'] as $ugroup=>$val){
		if (isset($groups[$ugroup])&&$groups[$ugroup]) return;	
	}
	
	apperror('Report access denied',null,null,$ctx);
	
}
