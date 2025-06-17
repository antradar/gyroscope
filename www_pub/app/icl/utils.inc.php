<?php
include 'icl/reauth.inc.php';

function authpump($ctx=null){
	reauth($ctx);
	$hb=$_GET['hb'];
	$ret=preg_replace('/[^\d]/','',$hb);
	if (strlen($ret)>40) $ret=substr($ret,0,40);
	echo $ret;
	die();
}
