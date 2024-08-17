<?php
function show_login_header(){
	$dark=isset($_COOKIE['userdarkmode'])?intval($_COOKIE['userdarkmode']):0;
?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "viewport" content = "width=device-width, user-scalable=no" />
	<meta name="theme-color" content="#9FA3A7" />	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<?php include 'appicon.php';?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">
	
<style>
<?php
$framecolor='rgba(200,200,200,0.4)';
if (isset($SQL_READONLY)&&$SQL_READONLY) $framecolor='rgba(255,200,100,0.4)';
?>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;<?php if ($dict_dir=='rtl') echo 'direction:rtl;';?>}
#logo_light,#logo_dark{margin:10px 0;width:100%;}
#logo_light{display:block;}
#logo_dark{display:none;}

#yubikeysetup a, #yubikeysetup a:hover, #yubikeysetup a:link, #yubikeysetup a:visited{text-decoration:none;color:#187CA6;}
#yubikeysetup a:hover{text-decoration:underline;}

#loginbox__{width:320px;margin:0 auto;background-color:<?php echo $framecolor;?>;margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:<?php if ($dict_dir=='rtl') echo 'right'; else echo 'left';?>;}
.powered{color:#000000;text-align:right;font-size:12px;width:320px;margin:0 auto;padding-top:10px;}
.loginbutton_,.loginbutton{color:#ffffff;background:#187CA6;padding:8px 20px;border-radius:3px;border:none;cursor:pointer;box-shadow:0px 1px 2px #c9c9c9;-webkit-appearance:none;text-decoration:none;}
.loginbutton_:focus, .loginbutton_:hover{background:#29ABE1;}
.loginbutton_:active, #loginbuttonbutton:active{box-shadow:1px 1px 3px #999999;}

#fingerprint{cursor:pointer;vertical-align:middle;}
#fingerprint img{width:22px;margin-right:-10px;filter:saturate(0.4);}
#fingerprint img:hover{filter:saturate(1);}

#loginbar{text-align:center;}

#cardlink, #passlink{display:none;text-align:center;padding-top:10px;}
#cardlink{display:none;}
#cardinfo{padding:5px;font-size:12px;padding-left:26px;background:#fcfcdd url(imgs/smartcard.png) no-repeat 5px 50%;margin-bottom:10px;display:none;}

.lfinp,.lfsel{border:solid 1px #999999;display:block;margin-bottom:5px;border-radius:3px;}
.lfinp:active, .lfinp:focus, .lfsel:active, .lfsel:focus{outline:0;border:solid 2px #29ABE1;}
.lfinp{font-size:18px;-webkit-appearance:none;}
.lfsel{font-size:15px;}

#lang{padding:5px 0;}

.passtoggle{position:absolute;top:12px;right:8px;width:18px;cursor:pointer;}

@media screen and (min-width:20px){
	.lfinp{padding:5px;box-sizing:border-box;height:34px;line-height:32px;font-size:15px;}
}

@media screen and (max-width:400px){
	#loginbox__,.powered{width:90%;}
	#loginbox__{margin-top:50px;}
}

@media screen and (max-width:300px){
	.loginbutton_{width:auto;padding-left:15px;padding-right:15px;}
}

@media screen and (max-width:260px){
	.powered{text-align:center;}
	.powered span{display:block;padding-top:3px;}
}

<?php if (SGET('kpw')||preg_match('/kindle/i',$_SERVER['HTTP_USER_AGENT'])){?>
body{font-size:28px;}
#loginbox__{width:640px;background-color:#dedede;margin-top:150px;border-radius:8px;}
#loginbox_{padding:20px;}
.powered{font-size:24px;width:640px;padding-top:20px;}
#login, #password{height:45px;font-size:32px;line-height:32px;}
.loginbutton_{height:auto;padding:6px 0;font-size:28px;width:280px;-webkit-appearance: none;}
<?php }?>

<?php
if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php
}

if ($dark==0||$dark==1){
?>
	body{background-image:url(imgs/dbgtile.png);}
	#loginbox{background: #21262D;color:#C9D1D9;}
	input,#lang{background:#0D1117;color:#C2C3C5;}
	.loginbutton_{box-shadow:none;border:solid 1px #388BFD;}
	.loginbutton_:hover{background:#125B7A;}
	#logo_light{display:none;}
	#logo_dark{display:block;}
	.powered{color:#8B949E;}
	#fingerprint,.passtoggle{filter:invert(1) hue-rotate(180deg);}
	.passtoggle{filter:invert(1);}
	#yubikeysetup a, #yubikeysetup a:hover, #yubikeysetup a:link, #yubikeysetup a:visited{text-decoration:none;color:#29ABE1;}	
<?php	

}//if dark==0||dark==1
	
if ($dark==0){
?>
}
<?php	
}
?>

</style>
<?php if (isset($roundwatchframe)&&$roundwatchframe){?>
<style>
	body{background-image:url(imgs/dbgtile.png);font-size:22px;}
	#loginbox__{margin-top:100px;border-radius:40px;}
	#loginbox{background:#21262D;color:#C9D1D9;border-radius:40px;overflow:hidden;}
	input,#lang{background:#0D1117;color:#C2C3C5;}
	.loginbutton_{box-shadow:none;border:solid 1px #388BFD;font-size:22px;}
	.loginbutton_:hover{background:#125B7A;}
	.lfinp{margin-bottom:20px;}
	#logo_light{display:none;margin-bottom:20px;}
	#logo_dark{display:block;margin-bottom:20px;}
	.powered{text-align:center;color:#8B949E;padding-bottom:160px;font-size:17px;}
	#fingerprint{filter:invert(1);}
</style>
<?php }?>
</head>
<body>
	
<?php
	
}
