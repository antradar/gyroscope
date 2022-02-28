<?php

//Gyroscope EncDec using OpenSSL
define ('ENC_DEC', 'OpenSSL');

function encstr($str,$key){
	$method='AES-256-CBC';
	$key=enckey($key);

	$ivlen=openssl_cipher_iv_length($method);
	if (is_callable('random_bytes')) $iv=random_bytes($ivlen);
	else $iv=openssl_random_pseudo_bytes($ivlen);
	
	$enc=base64_encode($iv.openssl_encrypt($str,$method,$key,0,$iv));
		
	return $enc;
}

function decstr($str,$key){
	$raw=base64_decode($str);
	
	$method='AES-256-CBC';
	$key=enckey($key);
	
	$ivlen=openssl_cipher_iv_length($method);
	$iv=substr($raw,0,$ivlen);
	$enc=substr($raw,$ivlen);
		
	$dec=openssl_decrypt($enc,$method,$key,0,$iv);

	
	return $dec;
}

function enckey($str){
	return substr(hash('SHA256',$str),32);
}
