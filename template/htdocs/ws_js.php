<?php

//wss_init(); //uncomment this line to enable websocket sync

function wss_init(){
	$user=userinfo();
	$userid=$user['userid'];
	$wsuri='ws://localhost:9000/wss.php';
?>
wss_init('<?echo $userid;?>','<?echo $wsuri;?>');

<?
}