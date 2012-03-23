<?
function xsscheck(){
	global $_SERVER;
	header('X-Frame-Options: SAMEORIGIN');
	$referer=$_SERVER['HTTP_REFERER'];
	$referer=str_replace('http://','',$referer);
	$referer=str_replace('https://','',$referer);
	$host=preg_quote($_SERVER['HTTP_HOST']);
	$pattern='/^'.$host.'/';
	if (!preg_match($pattern,$referer)&&$referer!='') die('XSS');
}
