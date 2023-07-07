<?php
include 'lb.php';
if ($usehttps) include 'https.php';

include 'connect.php';
include 'settings.php';
include 'retina.php';
include 'forminput.php';

include 'xss.php';
xsscheck(1);

include 'evict.php';
evict_check();

login();
$user=userinfo();
$userid=$user['userid'];
$query="select * from ".TABLENAME_USERS." where userid=?";
$rs=sql_prep($query,$db,$userid);
$usermeta=sql_fetch_assoc($rs);

$quicklist=1; //always enable quick list for mobile views
$dark=isset($usermeta['darkmode'])?intval($usermeta['darkmode']):0;


include 'uiconfig.php';
?>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="theme-color" content="#454242" />
	<link id="ajxcss_gyrodemo" href="iphone/gyrodemo_css.php?dark=<?php echo $dark;?>&v=2" type="text/css" rel="stylesheet" />
	<link href="gsnotes.css" type="text/css" rel="stylesheet" />
	<link id="ajxcss_toolbar" href="toolbar_css.php?dark=<?php echo $dark;?>&v=4" type="text/css" rel="stylesheet" />
<?php 
	if (isset($_GET['watch'])&&$_GET['watch']==1||preg_match('/sm\-r\d+/i',$_SERVER['HTTP_USER_AGENT'])){
		$roundwatchframe=1;
	}
?>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<?php
	include 'appicon.php';
	?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">
<style>
body{font-family:helvetica;}
.menuitem{padding-left:10px;height:30px;float:left;margin-right:3px;}
.menuitem a, .menuitem a:hover, .menuitem a:visited, .menuitem a:link{
	display:block;
	padding-top:3px;
	color:#000000;
	text-decoration:none;
}

<?php if ($SQL_READONLY){
?>
/* readonly mode */
button.warn, .button.warn{display:none;}
.recadder{display:none;}
.changebar_anchor{display:none;}
.savebar_anchor{display:none;}
#toolicons{border-top:dashed 2px #8B6827;}
<?php	
}?>



</style>

</head>
<body onload="setTimeout(scrollTo, 0, 0, 1);">
<div id="watchframe_outer">
<div id="toolbg" style="position:fixed;width:100%;z-index:1000;top:0;background:#333333;opacity:0.9"></div>
<?php
if (isset($roundwatchframe)&&$roundwatchframe){
?>
<div id="watchmenu">
	<a onclick="showhide('toolicons',1);"><img alt="home menu" class="img-home" src="imgs/t.gif" border="0" width="32" height="32"></a>
</div>
<?php
}
?>
<div id="toolicons" style="position:fixed;width:100%;z-index:1900;top:0;">

	<?php
	$tcount=1;
	if ($enablelivechat) $tcount=2;
	foreach ($toolbaritems as $ti) if (isset($ti['icon'])&&$ti['icon']!='') $tcount++;
	if (isset($roundwatchframe)&&$roundwatchframe) $tcount=$tcount+4;
	?>	
	<div id="toollist" style="overflow:auto;width:100%;"><div id="toollistcontent" style="width:<?php echo 52*($tcount+1+($uiconfig['enable_master_search']?4:0));?>px;">

	<?php 
	if (isset($roundwatchframe)&&$roundwatchframe){
	?>
	<div class="menuitem"><a><img src="imgs/t.gif" style="width:80px;height:10px;"></a></div>	
	<?php	
	}
	?>
			
	<div class="menuitem"><a id="speechstart" href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>ajxjs(<?php jsflag('speech_startstop');?>,'speech.js');speech_startstop(1);return false;" style="display:none;"><img alt="voice commands" style="" class="img-speechrecog" src="imgs/t.gif" border="0" width="32" height="32"></a></div>
	<div class="menuitem" id="homeicon"><a href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>reloadtab('welcome','','wk');showtab('welcome');document.viewindex=null;return false;"><img alt="home menu" class="img-home" src="imgs/t.gif" border="0" width="32" height="32"></a></div>
	<div class="menuitem" id="gsnotesclipicon"><a href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>if (navigator.onLine) gsnotes_listclips(); else onlinestatuschanged();"><img alt="offline clipboard" class="img-gsclip" src="imgs/t.gif" border="0" width="32" height="32"></a></div>

	<?php 
	
	if ($uiconfig['enable_master_search']){
		$toolbaritems['ui.search']=array('title'=>'Search','icon'=>'img-search','action'=>"showmmastersearch();");
		$item=array_pop($toolbaritems);
		array_unshift($toolbaritems,$item);
	}
		foreach ($toolbaritems as $modid=>$ti){
		if (isset($ti['type'])&&$ti['type']=='break') continue;
		if (isset($ti['noiphone'])&&$ti['noiphone']) continue;	
		if (isset($ti['type'])&&$ti['type']=='custom'){
		?>
		<?php echo isset($ti['iphone'])?$ti['iphone']:'';?>
		<?php	
			continue;
		}
		
		$binmode='null';
		if (isset($ti['bingo'])&&$ti['bingo']==1) $binmode=1;

		$action="showview('".$modid."',1,1,null,null,".$binmode.");";
		if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
		if (!isset($ti['icon'])||$ti['icon']=='') continue;
		
		if (isset($ti['groups'])){
			$canview=0;
			$gs=explode('|',$ti['groups']);
			foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
			if (!$canview) continue;	
		}
		
		if (isset($roundwatchframe)&&$roundwatchframe) $action='initwatchmenu();'.$action;
		
	?>
	<div class="menuitem"><a onclick="<?php echo $action;?>return false;"><img alt="<?php echo $ti['title'];?>" class="<?php echo $ti['icon'];?>" src="imgs/t.gif" border="0" width="32" height="32"></a></div>
	<?php }
	?>
	<?php if ($enablelivechat){?>
	<div class="menuitem"><a href=# onclick="livechat_start();return false;"><img alt="live chat" id="chaticon" src="imgs/t.gif" border="0" width="32" height="32"></a></div>
	<?php }
	
	?>
	
	<?php 
	if (isset($roundwatchframe)&&$roundwatchframe){
	?>
	<div class="menuitem"><a id="watchlogout" onclick="if (document.websocket) document.websocket.onclose=null;" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?><?php if (isset($_GET['watch'])) echo '&watch='.intval($_GET['watch']);?>"><img src="imgs/t.gif" width="16" height="16" class="admin-logout"></a></div>
	<div class="menuitem"><a><img src="imgs/t.gif" style="width:10px;height:10px;"></a></div>	
	<div class="menuitem"><a><img src="imgs/t.gif" style="width:10px;height:10px;"></a></div>	
	<?php	
	}
	?>	
	
	</div></div>
	<span id="labellogin" style="display:none;"><?php echo $user['login'];?></span><span id="labeldispname" style="display:none;"><?php echo $user['dispname'];?></span>	
	<a id="adminlogout" onclick="if (document.websocket) document.websocket.onclose=null;" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" style="position:absolute;top:10px;right:10px;"><img alt="sign out" border="0" width="16" height="16" src="imgs/t.gif" class="admin-logout"></a>

	<div id="mmastersearch">
		<div class="mastersearchshell">
		<input id="mastersearch" onkeyup="_mastersearch();" onblur="hidemmastersearch();">
		</div>
		<img id="msearchcloser" onclick="hidemmastersearch();" src="imgs/search-close.gif" width="20">
	</div>
	
</div><!-- toolicons -->

<div id="mainsearchview_">
	<div id="mainsearchview"></div>
</div>
	
<div id="pusher" style="width:100%;height:50px;"></div>

<div style="display:none;">
	<img src="imgs/t.gif"><img src="imgs/hourglass.gif">
	<video loop id="nosleepvideo">
		<source src="nosleep.webm" type="video/webm">
		<source src="nosleep.mp4" type="video/mp4">
	</video>
	<audio id="gschatsound_msgin"><source src="chatsounds/msgin.mp3"></audio>
	<audio id="gschatsound_newchat"><source src="chatsounds/newchat.mp3"></audio>
</div>
<div id="leftview" style="float:left;margin-left:10px;width:210px;margin-right:10px;">
	<div id="tooltitle" ontouchstart="toggle_easyread_start();" ontouchend="toggle_easyread_end();" onclick="if (document.viewindex) reloadview(document.viewindex,0,1);" style="width:150px;position:fixed;top:50px;z-index:1000;height:25px;"></div>
	<div id="tooltitleshadow" style="width:150px;height:25px;"></div>
	<div id="lvviews">
	<?php foreach ($toolbaritems as $modid=>$ti){?>
		<div class="lvview" id="lv<?php echo $modid;?>" style="display:none;"></div>
	<?php }?>	
	</div>
	<div id="lkv" style="height:100%;">
		<div id="lkvtitle"><a id="lkvt"></a><img id="lkvx" src="imgs/t.gif" onclick="hidelookup();" width="30" height="24"></div>
		<div id="lkvc"></div>
	</div>
	
</div>
<div id="content" style="float:left;width:320px;">

	<div id="backlist" ontouchstart="toggle_easyread_start();" ontouchend="toggle_easyread_end();" style="display:none;position:fixed;width:100%;z-index:1000;"><a id="backlistbutton"><img class="mtback_light" alt="back button" onclick="navback();" src="iphone/bb_<?php echo $lang;?>.png"><img class="mtback_dark" alt="back button" onclick="navback();" src="iphone/dbb_<?php echo $lang;?>.png"></a></div>
	<div id="backlistshadow" style="display:none;width:100%;"></div>

	<div id="tabtitles" style="width:325px;position:fixed;z-index:1000;"></div>
	<div id="tabtitleshadow" style="height:25px;width:100px;display:none;"></div>

	<div id="tabviews" style=""></div>
	<div id="statusinfo" style="display:none;"><div id="statusc"></div></div>
</div>
<div id="rotate_indicator" style="display:none;position:fixed;width:100px;height:100px;top:220px;left:110px;z-index:3000;"></div>
<div id="fsmask"></div>
<div id="fstitlebar">
	<div id="fstitle"></div>
	<a id="fsclose" onclick="closefs();"><img alt="close full screen" width="10" height="10" class="img-closeall" src="imgs/t.gif"></a>
</div>
<div id="fsview"></div>

<div id="gsstickerview" onclick="this.style.display='none';">
	<div id="gsstickercontent"></div>
</div>

<div id="callout" style="z-index:4000;opacity:0;transition:top 200ms,left 200ms,opacity 250ms;position:fixed;top:-80px;left:250px;"><img src="imgs/callout.png" style="width:56px;"></div>

</div><!-- watchframe_outer -->

<script>
document.appsettings={codepage:'<?php echo $codepage;?>',binpages:<?php echo json_encode($binpages);?>,quicklist:<?php echo $quicklist?'true':'false';?>,beepnewchat:<?php echo $usermeta['canchat']?'true':'false';?>,fastlane:'<?php echo $fastlane;?>',autosave:null, viewmode:'iphone',uiconfig:<?php echo json_encode($uiconfig);?>,views:<?php echo json_encode(array_keys($toolbaritems));?>};
</script>
<script src="lang/dict.<?php echo $lang;?>.js"></script>
<script src="nano.js?v=5_1"></script>
<script>
hdpromote('toolbar_hd_css.php?dark=<?php echo $dark;?>');
hdpromote('iphone/gyrodemo_hd_css.php?dark=<?php echo $dark;?>');
hddemote('legacy.css');
</script>
<?php if (isset($roundwatchframe)&&$roundwatchframe){?>
	<link href="watch.css?v=14" type="text/css" rel="stylesheet" />
<?php }?>	

<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js"></script>
<script src="validators.js"></script>
<script src="autocomplete.js?v=3"></script>

<script>

function showmmastersearch(){//mobile master search
	gid('mmastersearch').style.display='block';
	gid('mastersearch').focus();
	if (gid('mastersearch').value!='') showmainsearchview();
}

function hidemmastersearch(){
	gid('mmastersearch').style.display='none';
	hidemainsearchview();
}

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
	
<?php 
	$ori_portrait_backward=180;
	$ori_portrait_forward=0;
	$ori_landscape_backward=-90;
	$ori_landscape_forward=90;

	$agent=$_SERVER['HTTP_USER_AGENT'];

	if (preg_match('/playbook/i',$agent)||preg_match('/android/i',$agent)) $ori_invert=1;
	if (preg_match('/mobile/i',$agent)||preg_match('/opera mini/i',$agent)) $ori_invert=0; //do not invert any phones
	
	if (isset($ori_invert)&&$ori_invert){
		$ori_portrait_backward=-90;
		$ori_portrait_forward=90;
		$ori_landscape_backward=180;
		$ori_landscape_forward=0;
	}
?>	
	var ori=90;
	if (window.matchMedia){
		if (window.matchMedia('(orientation: landscape)').matches) ori=<?php echo $ori_landscape_forward;?>;
		else if (window.matchMedia('(orientation: portrait)').matches) ori=<?php echo $ori_portrait_forward;?>;
	}
	
	if (window.operamini) ori=0;	
	
	<?php
	if (strpos($agent,'Mobile; ALCATEL ')!==false){
	?>
		ori=0; //alcatel flip phone
	<?php	
	}	
	?>
	
	
	if (!document.appsettings.cw) document.appsettings.cw=320;
	if (document.appsettings.cw<document.body.clientWidth) document.appsettings.cw=document.body.clientWidth;
		
	var cw=document.appsettings.cw;
	var vw=document.body.clientWidth;
	
	switch(ori){
	case <?php echo $ori_portrait_backward;?>: case <?php echo $ori_portrait_forward;?>: 
		
		//gid('panel2').style.display='block';
		showdeck();
		gid('leftview').style.width=vw+'px';
		gid('leftview').style.marginLeft=0;
		gid('backlist').style.display='block';
		gid('backlistshadow').style.display='block';
		//gid('leftview').style.fontSize='25px';
		gid('tooltitle').style.width=vw+'px';
		//gid('tooltitle').style.top='40px';
		gid('pusher').style.height='50px';
		gid('toollist').style.width=document.documentElement.clientWidth-50+'px';//'280px';
		gid('tabtitleshadow').style.display='none';
		gid('content').style.width=vw+'px';
		
		if (document.lastori==null||document.lastori!=ori) ajxcss(self.cssloader,'iphone/portrait_css.php?dark=<?php echo $dark;?>','portrait','landscape');
		document.viewheight=vw+30;
		scaleall(document.body);
		document.iphone_portrait=1;
		
		hidelookup();

		lkv_dismount();
		
	break;
	case <?php echo $ori_landscape_forward;?>: case <?php echo $ori_landscape_backward;?>: 
		//gid('panel2').style.display='none';
		gid('leftview').style.display='block';
		gid('leftview').style.width='210px';
		gid('leftview').style.marginLeft='10px';
		//gid('leftview').style.fontSize='14px';
		gid('tabtitles').style.display='block';
		gid('content').style.display='block';
		gid('backlist').style.display='none';
		gid('backlistshadow').style.display='none';
		
		gid('tooltitle').style.width=vw+'px';
		//gid('tooltitle').style.top='50px';
		gid('pusher').style.height='50px';
		gid('toollist').style.width=cw-50+'px';
		gid('tabtitleshadow').style.display='block';
		gid('content').style.width=cw-230+'px';
		gid('tabtitles').style.width=cw-230+'px';
		if (document.lastori==null||document.lastori!=ori) ajxcss(self.cssloader,'iphone/landscape_css.php?dark=<?php echo $dark;?>','landscape','portrait');
		document.viewheight=210;

		scaleall(document.body);
		document.iphone_portrait=null;
		gid('rotate_indicator').style.display='none';
		
		lkv_remount();
	break;
	}
	
	document.lastori=ori;

}

function portrait_ignore(ttl){
	if (!ttl) ttl=2000;

	document.portraitlock=1;

	setTimeout(function(){document.portraitlock=null;},ttl);
}

addtab('welcome','<?php tr('tab_welcome');?>','wk',null,null,{noclose:true});

function onrotate(){
	if (document.resizetimer) clearTimeout(document.resizetimer);
	document.resizetimer=setTimeout(function(){
		rotate();
		setTimeout(rotate,500);
	},100);
}

setInterval(authpump,60000); //check if needs to re-login; comment this out to disable authentication

addtab('welcome','Welcome','wk',null,null,{noclose:true});

if (typeof(window.onorientationchange)!='object') window.onresize=onrotate;
else window.onorientationchange=onrotate;

onrotate();

scaleall(document.body);


</script>
<?php include 'ws_js.php';?>
<script src="speechloader.js"></script>
<?php
/*
<script src="barcodescanner.js"></script>
<script>
	barcodescanner.init();
	window.onblur=function(){if (gid('barcodewarn')) gid('barcodewarn').style.display='inline';document.keyboard=[];document.gamepadlock=true;}
	window.onfocus=function(){if (gid('barcodewarn')) gid('barcodewarn').style.display='none';document.gamepadlock=null;}
</script>
*/
?>
<script>
if (window.Notification) Notification.requestPermission();

initwatchmenu=function(){
	showhide('toolicons',1);	
}

</script>
<?php if ($enablelivechat){
	include 'livechat.php';
	livechat();
}?>
<script>
window.onload=function(){
	<?php if ($enablelivechat){?>
	livechat_init();
	<?php }?>	
}
</script>

<!-- script src="imecree.js"></script -->

<script>
if (navigator.serviceWorker&&navigator.serviceWorker.register){
	navigator.serviceWorker.register('service_worker.js');
}
</script>
<?php
include 'offline.php';
?>
</body>
</html>
