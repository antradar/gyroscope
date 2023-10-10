<?php

function xsscheck($easy=0){
	global $_SERVER;
	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 1; mode=block');
	header('X-Content-Type-Options: nosniff');
	
	//header("Content-Security-Policy: child-src 'self'");

	header("Content-Security-Policy: child-src 'self' *.stripe.com ");

	//header("Content-Security-Policy: default-src 'self'; child-src 'self';");
		
if (!$easy||true){ //comment out ||true to relax cross-site signon
	$referer=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
	$referer=str_replace('http://','',$referer);
	$referer=str_replace('https://','',$referer);
	$host=preg_quote($_SERVER['HTTP_HOST']);
	$pattern='/^'.$host.'/';
	
	$fedbypass=0;

	//federated signon
	
	//if (preg_match('/^foreign-source-site\.com\/login\.php/',$referer)&&$referer!='') $fedbypass=1;

	if (!$fedbypass){
	
		if (!preg_match($pattern,$referer)&&$referer!='') {
			header('HTTP/1.0 403 Forbidden');
			header('X-STATUS: 403');
			die();
		}
	}
}

}
