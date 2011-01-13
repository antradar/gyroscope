<?
include 'settings.php';
include 'auth.php';
login();
$user=userinfo();
?>
<html>
<head>
	<title>Antradar Gyroscope(tm) Sample Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;"/>
	<link href='iphone/gyrodemo.css' type='text/css' rel='stylesheet'>
<style>
body{font-size:16px;font-family:helvetica;}
.menuitem{padding-left:10px;font-size:20px;height:30px;float:left;margin-right:3px;}
.menuitem a, .menuitem a:hover, .menuitem a:visited, .menuitem a:link{
	display:block;
	padding-top:3px;
	color:#000000;
	text-decoration:none;
}

</style>

</head>

<body onload="setTimeout(scrollTo, 0, 0, 1);">

<div id="toolicons">
	<div class="menuitem"><a href=# onclick="showview(0);"><img src="imgs/bigicon1.gif" border="0"></a></div>
	<div class="menuitem"><a href=# onclick="showview(1);"><img src="imgs/bigicon2.gif" border="0"></a></div>
	<a href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" style="padding-right:10px;font-size:14px;color:#000000;">Logout</a>
</div><!-- toolicons -->


<div id="leftview" style="float:left;width:150px;font-size:20px;margin-right:5px;">
	<div id="tooltitle"></div>
	<div id="lv0" style="background-color:#ffffff;display:none;"></div>
	<div id="lv1" style="background-color:#ffffff;display:none;"></div>
	<div id="lv2" style="background-color:#ffffff;display:none;"></div>
	<div id="lv3" style="background-color:#ffffff;display:none;"></div>
	<div id="lv4" style="background-color:#ffffff;display:none;"></div>
</div>
<div id="content" style="float:left;width:320px;overflow-x:auto;">

<div id="backlist" style="display:none;"><a id="backlistbutton" onclick="navback();"><img src="iphone/bb.png"></a></div>

<div id="tabtitles" style="padding-top:5px;padding-left:5px;"></div>
<div id="tabviews"></div>
<div id="statusinfo" style="display:none;"></div>
</div>

<script>
document.appsettings={codepage:'<?echo $codepage;?>',viewcount:<?echo $viewcount;?>};
</script>
<script src="iphone/nano.js"></script>
<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js"></script>
<script src="iphone/validators.js"></script>
<script src="autocomplete.js"></script>

<script>
function showdeck(){
	switch(document.viewmode){
		
		case 1: 
			gid('leftview').style.display='block'; 
			gid('tabtitles').style.display='block';
			
			gid('content').style.display='none';

		break;
		case 2:
			gid('leftview').style.display='none'; 
			gid('tabtitles').style.display='none';
			
			gid('content').style.display='block';
		break;
	}
		
}


function rotate(){

	ori=window.orientation;
	if (ori==null) return;
	switch(ori){
	case 0: //vertical 
		//gid('panel2').style.display='block';
		showdeck();
		gid('leftview').style.width='320px';
		gid('backlist').style.display='block';
		gid('leftview').style.fontSize='25px';
	break;
	case 90: case -90: 
		//gid('panel2').style.display='none';
		gid('leftview').style.display='block';
		gid('leftview').style.width='150px';
		gid('leftview').style.fontSize='14px';
		gid('tabtitles').style.display='block';
		gid('content').style.display='block';
		gid('backlist').style.display='none';
	break;

	}

}

addtab('welcome','Welcome','wk');

window.onorientationchange=rotate;
rotate();


</script>
</body>
</html>
