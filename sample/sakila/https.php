<?
$target=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
if ($_SERVER['HTTPS']!='on'&&$_SERVER['HTTPS']!=1&&$_SERVER['REMOTE_ADDR']!='127.0.0.1') {	
	$target=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$params='';
	foreach ($_GET as $key=>$val){
		$params.="&$key=$val";
	}
	$params=str_replace("\r",'-',$params);
	$params=str_replace("\n",'-',$params);
	if ($params!='') $target.="?$params";	
	header('location: https://'.$target);
	die();
}
