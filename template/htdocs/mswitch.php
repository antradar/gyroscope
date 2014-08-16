<?php

if (!isset($_GET['nom'])){
	$ua=$_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/iphone/i',$ua)||preg_match('/opera mini/i',$ua)||preg_match('/mobile/i',$ua)) {
		header('Location: iphone.php');
		die();	
	}
}
