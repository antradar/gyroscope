<?php

//Gyroscope EncDec using OpenSSL
define ('ENC_DEC', 'OpenSSL');

function encstr($str,$key,$remote=false){
	$method='AES-256-CBC';
	$ver=0;
	
	if (!$remote){
		$key=enckey($key);
	} else {
		$okey=$key;
		$key=enckey_remote($okey,1);
		$keyparts=explode('_',$key);
		if (count($keyparts)>1){
			$key=$keyparts[1];
			$ver=intval(str_replace('v','',$keyparts[0]));			
		}
		
	}

	$ivlen=openssl_cipher_iv_length($method);
	if (is_callable('random_bytes')) $iv=random_bytes($ivlen);
	else $iv=openssl_random_pseudo_bytes($ivlen);
	
	$enc='v'.$ver.'_'.base64_encode($iv.openssl_encrypt($str,$method,$key,0,$iv));
		
	return $enc;
}


function decstr($str,$key,$remote=false){
	$ver=0;
	$strparts=explode('_',$str);
	if (count($strparts)>1){
		$str=$strparts[1];
		$ver=intval(str_replace('v','',$strparts[0]));
	}
	
	$raw=base64_decode($str);
	$method='AES-256-CBC';
	
	if (!$remote){
		$key=enckey($key);
	} else {
		$okey=$key;
		$key=enckey_remote($okey,0,$ver);
	
		$keyparts=explode('_',$key);
		if (count($keyparts)>1){
			$key=$keyparts[1];
		}			
		
	}
	
	$ivlen=openssl_cipher_iv_length($method);
	$iv=substr($raw,0,$ivlen);
	$enc=substr($raw,$ivlen);
	$dec=openssl_decrypt($enc,$method,$key,0,$iv);
	
	return $dec;
}

function mdecstr($strs){ //remote only
	$method='AES-256-CBC';

	$rawkeys=array_keys($strs);
	
	$keys=array();
	$vers=array();
	
	foreach ($strs as $okey=>$str){
		
		$strparts=explode('_',$str);
		if (count($strparts)>1){
			$str=$strparts[1];
			$ver=intval(str_replace('v','',$strparts[0]));	
		}
		
		array_push($keys,$okey);
		array_push($vers,$ver);	
	}
	
		
	$deckeys=enckey_remote($keys,0,$vers);
				
	$res=array();
	foreach ($deckeys as $okey=>$deckey){
		$str=$strs[$okey];
		
		$strparts=explode('_',$str);
		if (count($strparts)>1){
			$str=$strparts[1];	
		}
		
		$keyparts=explode('_',$deckey);
		if (count($keyparts)>1){
			$deckey=$keyparts[1];
		}

		$raw=base64_decode($str);
		$ivlen=openssl_cipher_iv_length($method);
		$iv=substr($raw,0,$ivlen);
		$enc=substr($raw,$ivlen);
			
		$dec=openssl_decrypt($enc,$method,$deckey,0,$iv);
		$res[$okey]=$dec;	
	}
	
	return $res;
	
}


function enckey($str){
	return substr(hash('SHA256',$str),32);
}

function enckey_remote($okey,$set=0,$ver=0){
	global $encapikey; //set in lb.php, issued by keyvault.antradar.com
	global $encclientid; //set in lb.php, alphanumeric
	global $encapisecret; //once set, do not change
	

	$tz=date_default_timezone_get();
	date_default_timezone_set('UTC');
		$auth=sha1($encapikey.'-'.$encclientid.'-'.$encapisecret.'-'.$okey.'-'.date('Y-n-j-H'));
	date_default_timezone_set($tz);
	
		
	$key='';
	$data='&clientid='.urlencode($encclientid).'&auth='.urlencode($auth).'&apisecret='.urlencode($encapisecret);
	if (!is_array($okey)){
		$data.='&okey='.urlencode($okey);
		$data.='&ver='.$ver;
	} else {
		if ($set) die("batch encryption mode not supported\r\n");
		foreach ($okey as $idx=>$ok) {
			$data.='&okey[]='.urlencode($ok);
			$data.='&ver[]='.(is_numeric($ver[$idx])?$ver[$idx]:0);
		}
	}
	
		
	$curl=curl_init('https://keyvault.antradar.com/enckey2.php?set='.$set);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	$res=curl_exec($curl);
	
				
	$resobj=json_decode($res,1);
	
	
	$key=$resobj['key'];
	
	
	/*
	if (!is_array($key)&&$key==''){
		if (is_callable('apperror')) apperror('failed to obtain encryption key');
		else die('failed to obtain encryption key');
	}
	*/
	
	
	return $key;
	
}

