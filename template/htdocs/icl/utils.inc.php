<?
include 'icl/reauth.inc.php';

function authpump(){
	reauth();
	$hb=$_GET['hb'];
	echo $hb;
	die();
}
