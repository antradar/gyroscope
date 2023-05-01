<?php

include 'lb.php';
include 'lang.php';
include 'forminput.php';
include 'bcrypt.php';

include 'gsratecheck.php';

if (isset($usehttps)&&$usehttps) include 'https.php'; 
include 'connect.php';
include 'auth.php';
include 'xss.php';

include 'sendsms.php';

$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-H',time()-3600);

xsscheck();

$login=SQET('login');
$nopass=intval(SQET('nopass'));

if ($login==''){header('HTTP/1.0 403');die('.');}

header('gsfunc: ajx_2facheck');

list($rateok,$penalty)=gsratecheck_verify($_SERVER['REMOTE_ADDR'],$login);
if (!$rateok){
	header('prevalidation: too many login attempts');
	die();	
}

$query="select * from ".TABLENAME_USERS." left join ".TABLENAME_GSS." on ".TABLENAME_USERS.".".COLNAME_GSID."=".TABLENAME_GSS.".".COLNAME_GSID." where login=? and active=1 and virtualuser=0";
$rs=sql_prep($query,$db,$login);  

$passok=0;

$federated=null;

if ($myrow=sql_fetch_array($rs)){

	if ($nopass) $passok=1;
	else $passok=password_verify($dbsalt.$_POST['password'],$myrow['password']);
		
	/*
	$federated=array(
		'url'=>'https://www.foreign-source-site.com/federate.php?',
		'loginfield'=>'gyroscope_login_d806dd416f5abf0b7',
		'key'=>'asdf',
		'timezone'=>'America/Toronto',
	);
	*/
	
	$passreset=$myrow['passreset'];		
	
	$needkeyfile=$myrow['needkeyfile'];
	$usesms=$myrow['usesms'];
	$smscell=$myrow['smscell'];
	$usega=$myrow['usega'];
	$gakey=$myrow['gakey'];
	
	if ($smscell=='') $usesms=0;
	if ($smskey=='') $usesms=0;
	if ($gakey=='') $usega=0;
	
	if ($passreset){
		$needkeyfile=0;
		$usesms=0;
		$usega=0;
	}
	
	$userid=$myrow['userid'];
		
	$needcert=$myrow['needcert'];

	$useyubi=$myrow['useyubi'];
	$yubimode=$myrow['yubimode'];	
	
} else {
	password_hash($dbsalt.time(),PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
	
	//check with master server via a rpc call, build federated array if necessary
}


if (is_array($federated)){
	$fedurl=$federated['url'];
	$tz=date_default_timezone_get();
	
	date_default_timezone_set($federated['timezone']);
	
	$fedauth=sha1($federated['key'].$userid.'-'.date('Y-n-j-H'));
	
	date_default_timezone_set($tz);
	
	$passreset=0;
	$passok=1;
	
	header('fedurl: '.$fedurl.'&userid='.$userid.'&auth='.$fedauth);
	header('fedloginfield: '.$federated['loginfield']);
	die();
}
	


$tfas=array();
$foci=array();

if (!$passok){
	header('prevalidation: invalid credentials');
	die();
}

if ($passreset){
	header('prevalidation: reset bypass');
	die();	
}



if ($usesms){
	array_push($tfas,'sms');
	array_push($foci,'smscode');
	
	$code=rand(10000,99999);
	$codehash=md5($salt.$code);
	
	$query="update ".TABLENAME_USERS." set smscode=? where userid=?";
	sql_prep($query,$db,array($codehash,$userid));
	
	sendsms($smscell,'Your access code is: '.$code);	
}

if ($needkeyfile) {
	array_push($tfas,'keyfile');
}

if ($needcert){
	array_push($tfas,'cert');
}

if ($usega){
	array_push($tfas,'ga');
	array_push($foci,'gapin');
}

if ($useyubi&&$yubimode==0){
	array_push($tfas,'yubi');	
}

if (count($tfas)>0){
	header('tfas: '.implode(',',$tfas));
	if (count($foci)>0){
		header('focalpoint: '.$foci[0]);	
	}
}

if ($needkeyfile){
?>
	<div>Key File:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
		<input id="keyfile" type="file" name="keyfile">
		<input type="hidden" name="MAX_FILE_SIZE" value="4096">
	</div>
<?php
}
