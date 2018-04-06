<?php

$usewss=0; //set this to 1 to activate websocket sync

if (isset($_GET['nowss'])&&$_GET['nowss']) $usewss=0;
if (preg_match('/smart\-tv/i',$_SERVER['HTTP_USER_AGENT'])) $usewss=0;
if (isset($usewss)&&$usewss) wss_init();


function wss_init(){
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid']+0;
	global $wssecret;
		
	$wsskey=md5($wssecret.date('Y-n-j-H'));	
	$wsuri='ws://localhost:9999/wss.php'; // wss:// in production

?>
<script src="wss.js"></script>
<script>
wss_init('<?echo $userid;?>','<?echo $wsuri;?>','<?echo $wsskey;?>','<?echo $gsid;?>');
</script>
<?
}