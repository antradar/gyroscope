<?php

if (!$_GET['nom']){
	$ua=$_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/iphone/i',$ua)) {
		header('Location: iphone.php');
		die();	
	}
}
