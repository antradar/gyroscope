<?php
header('Content-Type: text/css');
?>
.passwarn{
	background:transparent url(imgs/pass-rules.png) no-repeat 0 0;
	background-size:128px 16px;
	vertical-align:middle;
	width:16px;height:16px;border:solid 1px #660200;
	border-radius:3px;
	overflow:hidden;
	cursor:pointer;
	margin-right:2px;
}

.passwarn-var{background-position:0 0;}
.passwarn-symbol{background-position:-16px 0;}
.passwarn-numeric{background-position:-32px 0;}
.passwarn-length{background-position:-48px 0;}
.passwarn-comp{background-position:-64px 0;}

<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>

@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1||$dark==3){
?>
	
	.passwarn{filter:invert(1) hue-rotate(180deg);}

<?php	
}//if dark==0||dark==1
	
if ($dark==0){
?>
}
<?php	
}
	