<?php

die(); //uncomment to enable

$fedkey='asdf';

date_default_timezone_set('America/Toronto');
$userid=intval($_GET['userid']);
$auth=sha1($fedkey.$userid.'-'.date('Y-n-j-H'));
$auth2=sha1($fedkey.$userid.'-'.date('Y-n-j-H',time()-3600*24));

$token=$_GET['auth'];

if ($token==$auth||$token==$auth2){
	$fedbypass=1;
	include 'login.php';
}
