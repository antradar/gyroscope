<?php

include 'lb.php';
include 'lang.php';
include 'forminput.php';

if (isset($usehttps)&&$usehttps) include 'https.php'; 
include 'connect.php';
include 'auth.php';
include 'xss.php';

include 'sendsms.php';

$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-H',time()-3600);

xsscheck();


$password=md5($dbsalt.$_POST['password']);
$raw_login=$_POST['login'];
$login=str_replace("'",'',$raw_login);

$query="select * from ".TABLENAME_USERS." left join gss on ".TABLENAME_USERS.".gsid=gss.gsid where login='$login' and active=1 and virtualuser=0";
$rs=sql_query($query,$db);  

$passok=0;

if ($myrow=sql_fetch_array($rs)){
	$enc=$myrow['password'];
	$dec=decstr($enc,$_POST['password'].$dbsalt);
	if ($password==$dec) $passok=1;
	
	$passreset=$myrow['passreset'];
	
	$needkeyfile=$myrow['needkeyfile'];
	$usesms=$myrow['usesms'];
	$smscell=$myrow['smscell'];
	if ($smscell=='') $usesms=0;
	if ($smskey=='') $usesms=0;
	
	$userid=$myrow['userid']+0;
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
	
	$query="update users set smscode='$codehash' where userid=$userid";
	sql_query($query,$db);
	
	sendsms($smscell,'Your access code is: '.$code);	
}

if ($needkeyfile) {
	array_push($tfas,'keyfile');
}

if (count($tfas)>0){
	header('tfas: '.implode(',',$tfas));
	if (count($foci)>0){
		header('focalpoint: '.$foci[0]);	
	}
	die();
}

