<?php

include 'libcbor.php';
include 'icl/listyubikeys.inc.php';

function addyubikey(){
	global $db;
	global $saltroot;

	$user=userinfo();
	$userid=$user['userid'];
		
	$attidbin=hex2bin(SQET('id'));
	$attid=base64_encode($attidbin);
	
	$attbin=hex2bin(SQET('att'));
	$att=base64_encode($attbin);
	
	$clientdata=SQET('clientdata');
	
	$clientobj=json_decode($clientdata,1);	
	//echo '<pre>'; print_r($clientobj); echo '</pre>';
	
	$challenge=base64_decode($clientobj['challenge']);
	$challenge_=sha1($userid.$saltroot);

	if ($challenge!=$challenge_) apperror('Invalid credential challenge');
	
	$offset=0;
	$dec=cbor_decode($attbin,$offset);
	
	//echo '<pre>'; print_r($dec); echo '</pre>';
	
	$fmt=$dec['fmt'];	
	//if ($fmt!='none'&&$fmt!='packed') apperror('This authentication format ['.$fmt.'] is not supported');
	
	$authflags=$dec['authData']['flags'];
	$attestdata=$dec['authData']['attestdata'];
	
	if ($authflags['userpresent']!=1) apperror('User must be present'); //||$authflags['userverified']!=1
	if ($authflags['attested']!=1||!isset($attestdata)) apperror('Missing attestation data');
	
	$credid=$attestdata['credid'];
	$credkey=$attestdata['credkey'];
	
	$kty=isset($credkey['kty'])?$credkey['kty']:null;
	$alg=isset($credkey['alg'])?$credkey['alg']:null;
	$crv=isset($credkey['crv'])?$credkey['crv']:null; //ec
	$x=isset($credkey['x'])?$credkey['x']:null; //ec
	$y=isset($credkey['y'])?$credkey['y']:null; //ec
	$n=isset($credkey['n'])?$credkey['n']:null; //rsa
	$e=isset($credkey['e'])?$credkey['e']:null; //rsa
	
	$keyname=substr($attid,0,8);
		
	$query="insert into ".TABLENAME_YUBIKEYS."(userid,keyname,attid,credid,kty,alg,crv,x,y,n,e) values (?,?,?,?,?,?,?,?,?,?,?)";
	sql_prep($query,$db,array($userid,$keyname,$attid,$credid,$kty,$alg,$crv,$x,$y,$n,$e));
		
	listyubikeys();
}
