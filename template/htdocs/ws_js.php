<?php

$usewss=0; //set this to 1 to activate websocket sync

if ($_GET['nowss']) $usewss=0;
if (preg_match('/smart\-tv/i',$_SERVER['HTTP_USER_AGENT'])) $usewss=0;
if ($usewss) wss_init();


function wss_init(){
	$user=userinfo();
	$userid=$user['userid'];
	$wssecret='asdf'; //sync this value in wss.php
	$wsskey=md5($wssecret.date('H'));
		
	$wsuri='ws://localhost:9000/wss.php?WSS'.$wsskey.'=';

?>
wss_init('<?echo $userid;?>','<?echo $wsuri;?>');

<?
}