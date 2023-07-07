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
	global $stablecf;
		
	$wsskey=md5($wssecret.$gsid.date('Y-n-j-H').$userid).'-'.$gsid.'-'.$userid;	
	$wsuri='wss://localhost:9999/wss/dummy'; // wss:// in production; in nginx, set /wss/ to split protocol via "proxy_pass" and upgrade headers if needed
	
	/*
	Protocol-splitting on Nginx:

upstream wss{
	server 127.0.0.1:2095; #remember to block outbound access
}

server{
	# ...
	
	location /wss/ {
		proxy_http_version 1.1;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection "upgrade";
		proxy_pass http://wss;
	}		
}		
	*/
	
	//use same-port protocol splitting:
	$wshost=$_SERVER['HTTP_HOST'];
	$wsport=$_SERVER['SERVER_PORT'];
	$wsproto='ws';
	if ($stablecf||isset($_SERVER['REQUEST_SCHEME'])&&$_SERVER['REQUEST_SCHEME']=='https') $wsproto='wss';
	$wsuri=$wsproto.'://'.$wshost.':'.$wsport.'/wss/dummy';

?>
<script src="wss.js?v=2"></script>
<script>
wss_init('<?php echo $userid;?>','<?php echo $wsuri;?>','<?php echo $wsskey;?>','<?php echo $gsid;?>');
</script>
<?php
}