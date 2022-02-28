<?php

// $usewss: moved to lb.php

if (isset($_GET['nowss'])&&$_GET['nowss']) $usewss=0;
if (preg_match('/smart\-tv/i',$_SERVER['HTTP_USER_AGENT'])) $usewss=0;
if (isset($usewss)&&$usewss) wss_init();


function wss_init(){
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	global $wssecret; //defined in auth.php
		
	$wsskey=md5($wssecret.$gsid.date('Y-n-j-H').$userid).'-'.$gsid.'-'.$userid;	
	$wsuri='ws://localhost:9999/wss.php'; // wss:// in production

?>
<script src="wss.js?v=2"></script>
<script>
wss_init('<?php echo $userid;?>','<?php echo $wsuri;?>','<?php echo $wsskey;?>','<?php echo $gsid;?>');
</script>
<?php
}