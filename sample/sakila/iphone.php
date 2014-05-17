<?
include 'lb.php';
//include 'https.php';
include 'auth.php';
include 'settings.php';
include 'retina.php';

include 'evict.php';
evict_check();

login();
$user=userinfo();
?>
<html>
<head>
	<title>Antradar Gyroscope&trade; Mobile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;"/>
	<link href='iphone/gyrodemo.css' type='text/css' rel='stylesheet'>
	<link href='toolbar.css' type='text/css' rel='stylesheet'>
<style>
body{font-family:helvetica;}
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

<div style="height:40px;position:fixed;width:100%;z-index:1000;top:0;background-color:#efefef;opacity:0.9"></div>
<div id="toolicons" style="position:fixed;width:100%;z-index:2000;top:0;border-bottom:solid 1px #dedede;">

	<div id="toollist" style="overflow:auto;width:300px;height:35px;"><div style="width:1000px;">
	
	<?foreach ($toolbaritems as $ti){
		if ($ti['type']=='break') continue;
		if ($ti['noiphone']) continue;	
		if ($ti['type']=='custom'){
		?>
		<?echo $ti['iphone'];?>
		<?	
			continue;
		}
		
		$action='';
		if (is_numeric($ti['viewindex'])) $action='showview('.$ti['viewindex'].',null,1);';
		if ($ti['action']!='') $action.=$ti['action'];
	?>
	<div class="menuitem"><a href=# onclick="<?echo $action;?>"><img class="<?echo $ti['icon'];?>" src="imgs/t.gif" border="0" width="32" height="32"></a></div>
	<?}?>

	</div></div>
		
	<a href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" style="position:absolute;top:10px;right:10px;"><img border="0" width="16" height="16" src="imgs/t.gif" class="img-exit"></a>
</div><!-- toolicons -->
<div style="width:100%;height:40px;"></div>

<div id="leftview" style="float:left;width:150px;margin-right:5px;">
	<div id="tooltitle" style="width:150px;position:fixed;top:40px;z-index:1000;height:25px;"></div>
	<div id="tooltitleshadow" style="width:150px;height:25px;"></div>
	<div id="lvviews">
	<?for ($i=0;$i<=$viewcount;$i++){?>
		<div id="lv<?echo $i;?>" style="background-color:#ffffff;display:none;"></div>
	<?}?>	
	</div>
	<div id="lkv" style="height:100%;">
		<div id="lkvtitle"><a id="lkvt"></a><img id="lkvx" src="imgs/t.gif" onclick="hidelookup();" width="25" height="25"></div>
		<div id="lkvc"></div>
	</div>
	
</div>
<div id="content" style="float:left;width:320px;">

	<div id="backlist" style="display:none;position:fixed;top:40px;width:100%;z-index:1000;"><a id="backlistbutton"><img onclick="navback();" src="iphone/bb.png"></a></div>
	<div id="backlistshadow" style="display:none;width:100%;height:43px;"></div>

	<div id="tabtitles" style="width:325px;position:fixed;z-index:1000;"></div>
	<div id="tabtitleshadow" style="height:25px;width:100px;display:none;"></div>

	<div id="tabviews" style=""></div>
	<div id="statusinfo" style="display:none;"></div>
</div>
<div id="rotate_indicator" style="display:none;position:fixed;width:100px;height:100px;top:220px;left:110px;z-index:3000;background-image:url(iphone/flip.png);"></div>
<script>
document.appsettings={codepage:'<?echo $codepage;?>',viewcount:<?echo $viewcount;?>};
</script>
<script src="nano.js"></script>
<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js"></script>
<script src="validators.js"></script>
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
	
<?
	$ori_portrait_backward=0;
	$ori_portrait_forward=0;
	$ori_landscape_backward=-90;
	$ori_landscape_forward=90;

	$agent=$_SERVER['HTTP_USER_AGENT'];

	if (preg_match('/playbook/i',$agent)) $ori_invert=1;
	if ($ori_invert){
		$ori_portrait_backward=-90;
		$ori_portrait_forward=90;
		$ori_landscape_backward=0;
		$ori_landscape_forward=0;
	}
?>	
	ori=window.orientation;
	//if (ori==null) ori=0; //debug
	
	if (ori==null) ori=90;
	if (window.operamini) ori=0;	

	setTimeout(scrollTo, 0, 0, 1);
	
	if (!document.appsettings.cw) document.appsettings.cw=320;
	if (document.appsettings.cw<document.body.clientWidth) document.appsettings.cw=document.body.clientWidth;
	
	var cw=document.appsettings.cw;
	
	switch(ori){
	case <?echo $ori_portrait_backward;?>: case <?echo $ori_portrait_forward;?>: 
		var vw=document.body.clientWidth;
		//gid('panel2').style.display='block';
		showdeck();
		gid('leftview').style.width=vw+'px';
		gid('backlist').style.display='block';
		gid('backlistshadow').style.display='block';
		gid('leftview').style.fontSize='25px';
		gid('tooltitle').style.width=vw+'px';
		gid('toollist').style.width='280px';
		gid('tabtitleshadow').style.display='none';
		gid('content').style.width=vw+'px';
		
		//ajxcss(self.cssloader,'iphone/portrait.css');
		document.viewheight=vw+30;
		document.iphone_portrait=1;
		
		hidelookup();

		
	break;
	case <?echo $ori_landscape_forward;?>: case <?echo $ori_landscape_backward;?>: 
		//gid('panel2').style.display='none';
		gid('leftview').style.display='block';
		gid('leftview').style.width='150px';
		gid('tabtitles').style.display='block';
		gid('content').style.display='block';
		gid('backlist').style.display='none';
		gid('backlistshadow').style.display='none';
		
		gid('tooltitle').style.width='150px';
		gid('toollist').style.width=cw-50+'px';
		gid('tabtitleshadow').style.display='block';
		gid('content').style.width=cw-155+'px';
		gid('tabtitles').style.width=cw-155+'px';
		//ajxcss(self.cssloader,'iphone/landscape.css');
		document.viewheight=210;

		scaleall(document.body);
		document.iphone_portrait=null;
		gid('rotate_indicator').style.display='none';
		
	break;
	}
	
	

}

function portrait_ignore(ttl){
	if (!ttl) ttl=2000;

	document.portraitlock=1;

	setTimeout(function(){document.portraitlock=null;},ttl);
}

addtab('welcome','Welcome','wk',null,null,{noclose:true});

window.onorientationchange=rotate;
rotate();
scaleall(document.body);
</script>
<script src="wss.js"></script>
<script>
<?include 'ws_js.php';?>
</script>
</body>
</html>
