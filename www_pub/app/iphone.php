<?php
include 'lb.php';
if ($usehttps) include 'https.php';

include 'connect.php';
include 'settings.php';
include 'retina.php';
include 'forminput.php';

include 'xss.php';
xsscheck(1,null,1);

include 'evict.php';
evict_check();

login();
$user=userinfo();
$userid=$user['userid'];
$query="select * from ".TABLENAME_USERS." where userid=?";
$rs=sql_prep($query,$db,$userid);
$usermeta=sql_fetch_assoc($rs);

$quicklist=isset($usermeta['quicklist'])&&$usermeta['quicklist']?1:0;
if ($uiconfig['mobile']['singletab']) $quicklist=0;
$dark=isset($usermeta['darkmode'])?intval($usermeta['darkmode']):0;


include 'uiconfig.php';
?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="theme-color" content="#454242" />
	<link id="ajxcss_gyrodemo" href="iphone/gyrodemo_css.php?dark=<?php echo $dark;?>&v=2" type="text/css" rel="stylesheet" />
	<link href="gsnotes.css" type="text/css" rel="stylesheet" />
	<link id="ajxcss_toolbar" href="toolbar_css.php?dark=<?php echo $dark;?>&v=4" type="text/css" rel="stylesheet" />
	<link id="ajxcss_passrules" href="passrules_css.php?v=3&dark=<?php echo $dark;?>" type="text/css" rel="stylesheet" />
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
<?php
$ui_anchor='top';
$ui_bump=0;
if (isset($uiconfig['mobile'])&&isset($uiconfig['mobile']['toolbar_position'])) {
	$ui_anchor=$uiconfig['mobile']['toolbar_position'];
}

if ($ui_anchor=='bottom'){
	$ui_bump='10px';	
}
?>
<div id="toolbg" style="position:fixed;width:100%;z-index:1000;<?php echo $ui_anchor;?>:0;background:#333333;<?php if ($ui_anchor=='top') echo 'opacity:0.9;';?>"></div>
<?php
if (isset($roundwatchframe)&&$roundwatchframe){
?>
<div id="watchmenu">
	<a onclick="showhide('toolicons',1);"><img alt="home menu" class="img-home" src="imgs/t.gif" border="0" width="28" height="28"></a>
</div>
<?php
}
?>
<div id="toolicons" style="position:fixed;width:100%;z-index:1900;<?php echo $ui_anchor;?>:<?php echo $ui_bump;?>;">
		<img id="applogo" src="<?php echo $codepage;?>?cmd=clogo" onclick="if(document.viewindex) reloadview(document.viewindex);else{reloadtab('welcome','','wk');showtab('welcome');}" style="display:none;height:34px;width:auto;max-width:120px;object-fit:contain;" alt="">

	<?php
	$tcount=1;
	if ($enablelivechat) $tcount=2;
	foreach ($toolbaritems as $ti) if (isset($ti['icon'])&&$ti['icon']!='') $tcount++;
	if (isset($roundwatchframe)&&$roundwatchframe) $tcount=$tcount+4;
	?>	
	<div id="toollist" style="overflow:auto;width:100%;"><div id="toollistcontent" style="width:<?php echo 52*($tcount+1+($uiconfig['enable_master_search']?4:0));?>px;" data-orig-width="<?php echo 52*($tcount+1+($uiconfig['enable_master_search']?4:0));?>">

	<?php 
	if (isset($roundwatchframe)&&$roundwatchframe){
	?>
	<div class="menuitem"><a><img src="imgs/t.gif" style="width:80px;height:10px;"></a></div>	
	<?php	
	}
	?>
			
	<div class="menuitem"><a id="speechstart" href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>ajxjs(<?php jsflag('speech_startstop');?>,'speech.js');speech_startstop(1);return false;" style="display:none;"><img alt="voice commands" style="" class="img-speechrecog" src="imgs/t.gif" border="0" width="28" height="28"></a></div>
	<div class="menuitem" id="homeicon"><a href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>reloadtab('welcome','','wk');showtab('welcome');document.viewindex=null;return false;"><img alt="home menu" class="img-home" src="imgs/t.gif" border="0" width="28" height="28"></a></div>
	<div class="menuitem" id="gsnotesclipicon"><a href=# onclick="<?php if (isset($roundwatchframe)&&$roundwatchframe) echo 'initwatchmenu();';?>if (navigator.onLine) gsnotes_listclips(); else onlinestatuschanged();"><img alt="offline clipboard" class="img-gsclip" src="imgs/t.gif" border="0" width="28" height="28"></a></div>

	<?php 
	
		foreach ($toolbaritems as $modid=>$ti){
		if (isset($ti['type'])&&$ti['type']=='break') continue;
		if (isset($ti['noiphone'])&&$ti['noiphone']) continue;	
		if ($modid==='lv.welcome'&&isset($uiconfig['mobile_landscape']['toolbar_position'])&&$uiconfig['mobile_landscape']['toolbar_position']!=='bottom') continue;
		$settingsPos=$uiconfig['settings_position'];
		if (isset($uiconfig['mobile_landscape']['settings_position'])&&$uiconfig['mobile_landscape']['settings_position']!='') $settingsPos=$uiconfig['mobile_landscape']['settings_position'];
		if ($modid==='core.settings'&&$settingsPos==='top') continue;
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
	<div class="menuitem"><a onclick="<?php echo $action;?>return false;"><img alt="<?php echo $ti['title'];?>" class="<?php echo $ti['icon'];?>" src="imgs/t.gif" border="0" width="28" height="28"></a></div>
	<?php }
	?>
	<?php if ($enablelivechat){?>
	<div class="menuitem"><a href=# onclick="livechat_start();return false;"><img alt="live chat" id="chaticon" src="imgs/t.gif" border="0" width="28" height="28"></a></div>
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
	<div id="toolbaractions"<?php if (!empty($usermeta['haspic'])) echo ' class="hasprofile"';?>>
		<span id="labellogin" style="display:none;"><?php echo htmlspecialchars($user['login']);?></span><span id="labeldispname" style="display:none;"><?php echo htmlspecialchars($user['dispname']);?></span>
		<div id="mmastersearch">
			<img id="msearchicon" onclick="showmmastersearch();" src="imgs/t.gif" class="search-icon">
			<div class="mastersearchshell">
			<input id="mastersearch" onfocus="showmmastersearch();" onkeyup="_mastersearch();" onblur="hidemmastersearch();">
			<img id="msearchcloser" onclick="hidemmastersearch();" src="imgs/search-close.gif" width="20">
			</div>
		</div>
		<?php
		$settingsPos=$uiconfig['settings_position'];
		if (isset($uiconfig['mobile_landscape']['settings_position'])&&$uiconfig['mobile_landscape']['settings_position']!='') $settingsPos=$uiconfig['mobile_landscape']['settings_position'];
		if ($settingsPos==='top'){
			?><a onclick="showview('core.settings',1,1,null,null,null);return false;"><img alt="Settings" width="16" height="16" border="0" src="imgs/t.gif" class="admin-settings" style="cursor:pointer;vertical-align:middle;"></a><?php
		}
		$accountPos=$uiconfig['account_position'];
		if (isset($uiconfig['mobile_landscape']['account_position'])&&$uiconfig['mobile_landscape']['account_position']!='') $accountPos=$uiconfig['mobile_landscape']['account_position'];
		if ($accountPos!=='left'){
			if (!empty($usermeta['haspic'])){
				?><img id="mainuserprofile" class="mainuserprofile loaded" src="<?php echo $codepage;?>?cmd=imguserprofile&userid=<?php echo $userid;?>" alt="<?php echo htmlspecialchars($user['dispname']);?>" title="<?php echo htmlspecialchars($user['dispname']);?>" onclick="ajxjs2('setaccountpass','accounts.js',function(){reloadtab('account','My Account','showaccount');addtab('account','My Account','showaccount');});return false;" style="cursor:pointer;"><?php
			} else {
				?><img id="mainuserprofile" class="admin-user" alt="<?php echo htmlspecialchars($user['dispname']);?>" src="imgs/t.gif" width="16" height="16" border="0" title="<?php echo htmlspecialchars($user['dispname']);?>" onclick="ajxjs2('setaccountpass','accounts.js',function(){reloadtab('account','My Account','showaccount');addtab('account','My Account','showaccount');});return false;" style="cursor:pointer;"><?php
			}
		}
		?>
		<a id="adminlogout" onclick="if (document.websocket) document.websocket.onclose=null;" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" style="display:inline-block;vertical-align:middle;"><img alt="sign out" border="0" width="16" height="16" src="imgs/t.gif" class="admin-logout" <?php if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['power']==false) echo 'style="display:none;"';?>></a>
	</div>

</div><!-- toolicons -->

<div id="mainsearchview_">
	<div id="mainsearchview"></div>
</div>
	

<div id="pusher" style="width:100%;height:50px;<?php if ($ui_anchor!='top') echo 'display:none;';?>"></div>

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
	<div id="tooltitle" ontouchstart="toggle_easyread_start();" ontouchend="toggle_easyread_end();" onclick="if (document.viewindex) reloadview(document.viewindex,0,1);" style="width:150px;position:fixed;top:<?php if ($ui_anchor=='top') echo '50px;';?>;z-index:1000;height:25px;"></div>
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
	<div id="mainmenu" style="display:none;"></div>
	<div id="bookmarkview" style="display:none;"></div>
	<div id="mvptr" onclick="setquicklist(document.appsettings.quicklist?0:1);"></div>
<div id="content" style="float:left;width:320px;">

	<div id="backlist" ontouchstart="toggle_easyread_start();" ontouchend="toggle_easyread_end();" style="display:none;position:fixed;width:100%;z-index:1000;"><a id="backlistbutton"><img class="mtback_light" alt="back button" onclick="navback();" src="iphone/bb_<?php echo $lang;?>.png"><img class="mtback_dark" alt="back button" onclick="navback();" src="iphone/dbb_<?php echo $lang;?>.png"></a></div>
	<div id="backlistshadow" style="display:none;width:100%;"></div>

	<div id="tabtitles" style="width:325px;position:fixed;z-index:1000;<?php if ($ui_anchor=='bottom') echo 'top:10px;';?>"></div>
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

<div id="cornertab" style="display:none;position:fixed;bottom:50px;right:10px;width:300px;height:400px;z-index:700;background:#ffffff;border:1px solid #cccccc;box-shadow:0 4px 12px rgba(0,0,0,0.15);border-radius:8px;overflow:hidden;">
	<div id="cornertab_header" style="background:#454242;color:#ffffff;padding:8px;">
		<span id="cornertab_title"></span>
		<a id="cornertab_close" onclick="tab_undock_corner();" style="margin-left:8px;float:right;cursor:pointer;color:#ffffff;font-weight:bold;font-size:14px;display:inline-block;padding:0 5px;">x</a>
		<a id="cornertab_minimize" onclick="corner_minimize();" style="float:right;cursor:pointer;color:#ffffff;font-weight:bold;font-size:14px;display:inline-block;padding:0 5px;">_</a>
	</div>
	<div id="cornertab_content" style="width:100%;height:calc(100% - 28px);overflow:auto;"></div>
</div>
<div id="cornerbutton" style="display:none;position:fixed;bottom:50px;right:10px;width:44px;height:44px;z-index:700;background:#454242;border-radius:8px;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.15);text-align:center;line-height:44px;color:#ffffff;font-size:18px;" onclick="corner_button_click();">&#x2750;</div>

<div id="callout" style="z-index:4000;opacity:0;transition:top 200ms,left 200ms,opacity 250ms;position:fixed;top:-80px;left:250px;"><img src="imgs/callout.png" style="width:56px;"></div>

</div><!-- watchframe_outer -->

<script>
document.appsettings={codepage:'<?php echo $codepage;?>',binpages:<?php echo json_encode($binpages);?>,quicklist:<?php echo $quicklist?'true':'false';?>,beepnewchat:<?php echo $usermeta['canchat']?'true':'false';?>,fastlane:'<?php echo $fastlane;?>',autosave:null, viewmode:'iphone',uiconfig:<?php echo json_encode($uiconfig);?>,views:<?php echo json_encode(array_keys($toolbaritems));?>};
</script>
<script src="lang/dict.<?php echo $lang;?>.js"></script>
<script src="nano.js?v=5_6"></script>
<script>
hdpromote('toolbar_hd_css.php?dark=<?php echo $dark;?>');
hdpromote('iphone/gyrodemo_hd_css.php?dark=<?php echo $dark;?>');
hddemote('legacy.css');
</script>
<?php if (isset($roundwatchframe)&&$roundwatchframe){?>
	<link href="watch.css?v=14" type="text/css" rel="stylesheet" />
<?php }?>	

<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js?v=12"></script>
<script src="validators.js?v=2"></script>
<script src="autocomplete.js?v=3"></script>

<script>


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
			showdeck();
			gid('leftview').style.width=vw+'px';
			gid('leftview').style.marginLeft=0;
			gid('leftview').style.left='';
			gid('leftview').style.position='';
			gid('leftview').style.float='';
			gid('leftview').style.height='';
			gid('content').style.marginLeft='';
			gid('content').style.float='';
			gid('content').style.height='';
			gid('backlist').style.display='block';
			gid('backlistshadow').style.display='block';
			gid('tooltitle').style.width=vw+'px';
			gid('pusher').style.height='50px';
				gid('applogo').style.display='none';
				gid('toollistcontent').style.paddingLeft='0';
				gid('mvptr').style.display='none';
			gid('tabtitleshadow').style.display='none';
			gid('content').style.width=vw+'px';
			var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='';qns[qi].style.top='';}
			if (document.lastori==null||document.lastori!=ori) ajxcss(self.cssloader,'iphone/portrait_css.php?dark=<?php echo $dark;?>','portrait','landscape');
			document.viewheight=vw+30;
			scaleall(document.body);
			setTimeout(function(){scaleall(document.body);},100);
			document.iphone_portrait=1;
			gid('tabviews').style.paddingTop='0';
			var prls=document.querySelectorAll('.reloader[id^="tabreloader_"]');
			for(var pri=0;pri<prls.length;pri++){prls[pri].style.top='';}
			reposition_bar_views();
			update_qnav_padding();
			if (document.tabafloat) {
				document.body.className=document.body.className.replace(/\s*tabafloat_\w+/g,'')+' tabafloat_portrait';
				gid('tabtitles').style.display='none';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='0';qns[qi].style.top='';}
			}
			// Reposition toolbar based on portrait orientation
			var mob=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile:null;
			if (mob&&mob.toolbar_position=='bottom'){
				gid('toolbg').style.bottom='0';gid('toolbg').style.top='';
				gid('toolicons').style.bottom='10px';gid('toolicons').style.top='';
			} else {
				gid('toolbg').style.top='0';gid('toolbg').style.bottom='';
				gid('toolicons').style.top='0';gid('toolicons').style.bottom='';
			}
			// Reset any left-toolbar positioning from landscape mode
			gid('content').style.zIndex='';gid('toolbg').style.left='';gid('toolbg').style.width='100%';gid('toolbg').style.height='';
			gid('toolicons').style.left='';gid('toolicons').style.width='100%';gid('toolicons').style.height='';gid('toolicons').style.flexDirection='';gid('toolicons').style.justifyContent='';gid('toolicons').style.alignItems='';
			gid('toollist').style.display='';gid('toollist').style.position='';gid('toollist').style.left='';gid('toollist').style.width='';gid('toollist').style.height='';gid('toollistcontent').style.width=gid('toollistcontent').getAttribute('data-orig-width')+'px';gid('toollistcontent').style.paddingLeft='';gid('toollistcontent').style.display='';
			// Hide #mainmenu in portrait (uses #toollist instead)
			gid('mainmenu').style.display='none';gid('mainmenu').style.left='';gid('mainmenu').style.top='';gid('bookmarkview').style.display='none';gid('bookmarkview').style.visibility='';
			gid('tabtitles').style.top='';gid('tabtitles').style.left='';gid('tabtitles').style.width='';gid('tabtitles').style.marginLeft='';gid('tabviews').style.top='';gid('tabviews').style.left='';gid('tabviews').style.width='';gid('tabviews').style.paddingTop='';gid('tabviews').style.position='';gid('tabviews').style.height='';
			// Reserve space for toolbaractions (power button + search) in portrait toolbar
			var ta_w=gid('toolbaractions')?(gid('toolbaractions').offsetWidth||0):0;
			gid('toollist').style.width=ta_w>0?(vw-ta_w-20)+'px':'';
			gid('pusher').style.display='';
			// Hide span labels in portrait mode (no left toolbar)
			var spans=gid('toollistcontent').getElementsByTagName('span');for(var si=0;si<spans.length;si++) spans[si].style.display='';
			// Reset search visibility for portrait
			var mob=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile:null;
			if (mob&&mob.enable_master_search){
				gid('mmastersearch').style.display='inline-block';gid('adminlogout').style.display='';
			} else {
				gid('mmastersearch').style.display='none';gid('adminlogout').style.display='';
			}
			var msShell=gid('mmastersearch')?gid('mmastersearch').querySelector('.mastersearchshell'):null;
			if (msShell) msShell.style.width='0';
			var msIcon=gid('msearchicon');if (msIcon) msIcon.style.display='';
			gid('mastersearch').className='';
			var msCloser=gid('msearchcloser');if (msCloser) msCloser.style.display='none';
			lkv_dismount();
			if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
		break;
		case <?php echo $ori_landscape_forward;?>: case <?php echo $ori_landscape_backward;?>:
			gid('leftview').style.display='block';
			gid('leftview').style.position='absolute';
			gid('leftview').style.float='none';
			gid('tabtitles').style.display='block';
			gid('content').style.display='block';gid('content').style.zIndex='';
			gid('content').style.float='none';
			gid('backlist').style.display='none';
			gid('backlistshadow').style.display='none';
			gid('tooltitle').style.width=vw+'px';
			gid('pusher').style.height='50px';
			// Reposition toolbar based on landscape orientation
			var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
			var mob=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile:null;
			var lscape_bottom=(mobl&&mobl.toolbar_position=='bottom')||(mob&&mob.toolbar_position=='bottom'&&!mobl);
			var lscape_left=(mobl&&mobl.toolbar_position=='left');
			if (lscape_left){
				var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
				// Common left-toolbar setup
					gid('content').style.zIndex='';
					gid('toollist').style.display='none';gid('toollist').style.position='';gid('toollist').style.left='';
					gid('toollistcontent').style.display='none';
					gid('mainmenu').style.display='block';
					gid('applogo').style.display='block';
					gid('pusher').style.display='none';
					gid('tabtitles').style.top='';gid('tabtitles').style.left='';gid('tabtitles').style.width='';gid('tabtitles').style.marginLeft='';
					gid('tabviews').style.top='';gid('tabviews').style.left='';gid('tabviews').style.width='';gid('tabviews').style.paddingTop='';gid('tabviews').style.position='';gid('tabviews').style.height='';
					// Load listwelcome content if not already loaded
					if (!document.mainmenuloaded){
						ajxpgn('mainmenu',document.appsettings.codepage+'?cmd=listwelcome&iphone=1',0,0,null,function(){document.mainmenuloaded=true;appendMobileMenuItems();autosize();});
						document.mainmenuloaded=true;
					}
					if (lscape_vnp){
						// vnp=true: 40px top bar + collapsible icon sidebar on left
						gid('toolbg').style.top='0';gid('toolbg').style.bottom='';gid('toolbg').style.left='';gid('toolbg').style.width='100%';gid('toolbg').style.height='40px';
						gid('toolicons').style.top='0';gid('toolicons').style.bottom='';gid('toolicons').style.left='';gid('toolicons').style.width='100%';gid('toolicons').style.height='40px';
						gid('mainmenu').style.left='';gid('mainmenu').style.top='';
						gid('mainmenu').style.height=(ch()-40)+'px';
						var mmi=gid('mainmenuitems');var vnav_acct=gid('vnav_account');var vnav_h=(vnav_acct?vnav_acct.offsetHeight:0);if(mmi) mmi.style.height=(ch()-40-vnav_h)+'px';
						if (document.appsettings.quicklist){
							gid('mainmenu').className='';
							gid('applogo').src=document.appsettings.codepage+'?cmd=clogo';
							gid('content').style.width=(cw-210)+'px';gid('content').style.marginLeft='210px';
							gid('tabtitles').style.left='210px';gid('tabtitles').style.width=(cw-210)+'px';gid('tabtitles').style.marginLeft='';
							gid('tabviews').style.left='210px';gid('tabviews').style.width=(cw-210)+'px';
							gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
							var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='210px';qns[qi].style.top='';}
							if(gid('vnavside_hide'))gid('vnavside_hide').style.display='block';if(gid('vnavside_show'))gid('vnavside_show').style.display='none';
						} else {
							gid('mainmenu').className='collapsed';
							gid('applogo').src=document.appsettings.codepage+'?cmd=clogo&mode=sqr';
							gid('content').style.width=(cw-54)+'px';gid('content').style.marginLeft='54px';
							gid('tabtitles').style.left='54px';gid('tabtitles').style.width=(cw-54)+'px';gid('tabtitles').style.marginLeft='';
							gid('tabviews').style.left='54px';gid('tabviews').style.width=(cw-54)+'px';
							gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
							var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='54px';qns[qi].style.top='';}
							if(gid('vnavside_hide'))gid('vnavside_hide').style.display='none';if(gid('vnavside_show'))gid('vnavside_show').style.display='block';
						}
						if (document.appsettings.quicklist) lkv_remount(); else lkv_dismount();
						if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
					// Search visibility for landscape vnp=true
					var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
					if (mobl&&mobl.enable_master_search){
						gid('mmastersearch').style.display='inline-block';
						gid('adminlogout').style.display='';
						var msShell2=gid('mmastersearch').querySelector('.mastersearchshell');
						if (msShell2) msShell2.style.width='0';
						gid('mastersearch').className='';
						var msIcon2=gid('msearchicon');if (msIcon2) msIcon2.style.display='';
						var msCloser2=gid('msearchcloser');if (msCloser2) msCloser2.style.display='none';
					} else {
						gid('mmastersearch').style.display='none';
						gid('adminlogout').style.display='';
					}
					// tabviews top will be set by autosize() based on tab row count
					gid('tabviews').style.top='43px';gid('tabviews').style.height=(ch()-43)+'px';
				} else {
					// vertical_nav_persist=false: top bar (logo+power) + left panel (menu or bookmark TOC)
					gid('mvptr').style.display='none';
					gid('leftview').style.display='none';
					gid('toolbg').style.width='100%';gid('toolbg').style.height='40px';
					gid('toolicons').style.width='100%';gid('toolicons').style.height='40px';
					gid('mainmenu').style.width='180px';gid('mainmenu').style.left='0';gid('mainmenu').style.top='40px';
					gid('mainmenu').style.display='block';
					gid('bookmarkview').style.height=(ch()-40)+'px';
					// Check if current tab is dash or non-dash to decide left panel content
					var curTabId=document.currenttab;
					var isDashRotate=(document.tabkeys[curTabId]=='welcome')||(document.tabtitles[curTabId]&&document.tabtitles[curTabId].reloadinfo&&document.tabtitles[curTabId].reloadinfo.opts&&document.tabtitles[curTabId].reloadinfo.opts.tabctx=='dash');
					if (isDashRotate) {
						// Dash tab: show main menu
						gid('mainmenu').style.visibility='visible';
						gid('bookmarkview').style.display='none';
						gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
						gid('tabviews').style.left='180px';gid('tabviews').style.width=(cw-180)+'px';
						gid('content').style.width=(cw-180)+'px';gid('content').style.marginLeft='180px';gid('content').style.zIndex='2000';
						gid('tabtitles').style.top='17px';gid('tabtitles').style.left='180px';gid('tabtitles').style.width=(cw-180)+'px';gid('tabtitles').style.marginLeft='';
					} else {
						// Non-dash tab: show bookmark TOC (or blank left panel)
						gid('mainmenu').style.visibility='hidden';
						gid('bookmarkview').style.display='block';
						gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'')+' boundless';
						gid('tabviews').style.left='180px';gid('tabviews').style.width=(cw-180)+'px';
						gid('content').style.width=(cw-180)+'px';gid('content').style.marginLeft='180px';gid('content').style.zIndex='2000';
						gid('tabtitles').style.top='17px';gid('tabtitles').style.left='180px';gid('tabtitles').style.width=(cw-180)+'px';gid('tabtitles').style.marginLeft='';
						tab_loadbookmark(document.tabviews[curTabId]);
						gid('tabviews').onscroll=tab_syncbookmarks;
						setTimeout(tab_syncbookmarks,100);
					}
					// Search visibility for landscape vnp=false
					var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
					if (mobl&&mobl.enable_master_search){
						gid('mmastersearch').style.display='inline-block';
						gid('adminlogout').style.display='';
						var msShell=gid('mmastersearch').querySelector('.mastersearchshell');
						if (msShell) msShell.style.width='0';
						gid('mastersearch').className='';
									var msIcon=gid('msearchicon');if (msIcon) msIcon.style.display='';
						var msCloser=gid('msearchcloser');if (msCloser) msCloser.style.display='none';
					} else {
						gid('mmastersearch').style.display='none';
						gid('adminlogout').style.display='';
					}
					// Compute mainmenu height: full viewport height minus 40px top bar
					gid('mainmenu').style.height=(ch()-40)+'px';
					var mmi=gid('mainmenuitems');if(mmi) mmi.style.height=(ch()-40)+'px';
					// tabviews top will be set by autosize() based on tab row count
					gid('tabviews').style.top='43px';gid('tabviews').style.height=(ch()-43)+'px';
					var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='183px';qns[qi].style.top='43px';}
					lkv_dismount();
					if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
				}
			} else {
				// Standard landscape: toolbar at top or bottom
				// Reset any left-toolbar positioning
				gid('content').style.zIndex='';gid('toolbg').style.left='';gid('toolbg').style.width='100%';gid('toolbg').style.height='';
				gid('toolicons').style.left='';gid('toolicons').style.width='100%';gid('toolicons').style.height='';gid('toolicons').style.flexDirection='';gid('toolicons').style.justifyContent='';gid('toolicons').style.alignItems='';
				gid('applogo').style.display='block';
				// Position #toollist at left:100px (after logo), width fills remaining space minus adminlogout
				var ta_w2=gid('toolbaractions')?(gid('toolbaractions').offsetWidth||0):0;
				gid('toollist').style.display='';gid('toollist').style.position='absolute';gid('toollist').style.left='100px';gid('toollist').style.width=(cw-100-ta_w2-10)+'px';gid('toollist').style.height='';
				// Hide #mainmenu in standard landscape (uses #toollist instead)
				gid('mainmenu').style.display='none';gid('mainmenu').style.left='';gid('mainmenu').style.top='';gid('bookmarkview').style.display='none';gid('bookmarkview').style.visibility='';gid('tabtitles').style.top='';gid('tabtitles').style.left='';gid('tabtitles').style.width='';gid('tabtitles').style.marginLeft='';gid('tabviews').style.top='';gid('tabviews').style.left='';gid('tabviews').style.width='';gid('tabviews').style.paddingTop='';gid('tabviews').style.position='';gid('tabviews').style.height='';
				gid('toollistcontent').style.width=gid('toollistcontent').getAttribute('data-orig-width')+'px';gid('toollistcontent').style.paddingLeft='0';gid('toollistcontent').style.display='';
				// Hide span labels in standard landscape (toolbar at top/bottom)
				var spans=gid('toollistcontent').getElementsByTagName('span');for(var si=0;si<spans.length;si++) spans[si].style.display='';
				gid('mvptr').style.display='block';
				if (document.appsettings.quicklist){
					gid('leftview').style.left='5px';
					gid('leftview').style.width='210px';
					gid('mvptr').style.left='215px';
					gid('mvptr').className='';
					gid('content').style.width=cw-240+'px';
					gid('content').style.marginLeft='240px';
					gid('tabtitles').style.width=cw-240+'px';
					gid('tabtitles').style.marginLeft='';
					var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='237px';qns[qi].style.top='';}
					lkv_dismount();
					if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
				} else {
					gid('leftview').style.left='-230px';
					gid('leftview').style.width='210px';
					gid('mvptr').style.left='0';
					gid('mvptr').className='rexpand';
					gid('content').style.width=(cw-24)+'px';
					gid('content').style.marginLeft='22px';
					gid('tabtitles').style.width=(cw-24)+'px';
					gid('tabtitles').style.marginLeft='22px';
					var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='22px';qns[qi].style.top='';}
					lkv_dismount();
					if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
				}
				if (lscape_bottom){
					gid('toolbg').style.bottom='0';gid('toolbg').style.top='';
					gid('toolicons').style.bottom='10px';gid('toolicons').style.top='';
				} else {
					gid('toolbg').style.top='0';gid('toolbg').style.bottom='';
					gid('toolicons').style.top='0';gid('toolicons').style.bottom='';
				}
				// Standard landscape search visibility
				if (mobl&&mobl.enable_master_search){
					gid('mmastersearch').style.display='inline-block';
					gid('adminlogout').style.display='';
					var msShell=gid('mmastersearch').querySelector('.mastersearchshell');
					if (msShell) msShell.style.width='0';
					gid('mastersearch').className='';
							var msIcon=gid('msearchicon');if (msIcon) msIcon.style.display='';
					var msCloser=gid('msearchcloser');if (msCloser) msCloser.style.display='none';
				} else {
					gid('mmastersearch').style.display='none';
					gid('adminlogout').style.display='';
				}
			}
			if (document.lastori==null||document.lastori!=ori) ajxcss(self.cssloader,'iphone/landscape_css.php?dark=<?php echo $dark;?>','landscape','portrait');
			document.viewheight=210;
			scaleall(document.body);
			setTimeout(function(){scaleall(document.body);},100);
			document.iphone_portrait=null;
			reposition_bar_views();
			update_qnav_padding();
			if (document.tabafloat) {
				document.body.className=document.body.className.replace(/\s*tabafloat_\w+/g,'')+' tabafloat_landscape';
				gid('leftview').style.display='none';
				gid('mvptr').style.display='none';
				gid('tabtitles').style.display='none';
				gid('tabviews').style.paddingTop='0';gid('tabviews').style.left='0';gid('tabviews').style.top='0';gid('tabviews').style.width=document.appsettings.cw+'px';gid('tabviews').style.height=document.appsettings.ch+'px';
				gid('mainmenu').style.display='none';gid('bookmarkview').style.display='none';gid('bookmarkview').style.visibility='';
				gid('mmastersearch').style.display='none';
				gid('content').style.marginLeft='0';gid('content').style.zIndex='';
				gid('content').style.width=document.appsettings.cw+'px';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='0';qns[qi].style.top='0';}
			} else {
				gid('tabviews').style.paddingTop=(26*(document.rowcount||1))+'px';
				var rls=document.querySelectorAll('.reloader[id^="tabreloader_"]');
				for(var ri=0;ri<rls.length;ri++){rls[ri].style.top='';}
			}
			gid('rotate_indicator').style.display='none';
			if (typeof tab_reflow==='function') tab_reflow();
		break;
		}
		document.lastori=ori;
	}

addtab('welcome','<?php tr('tab_welcome');?>','dash_default',function(){showmainmenu();},null,{noclose:true,tabctx:'dash'});

setInterval(authpump,60000);
</script>
<?php include 'ws_js.php';?>
<script>
showtab('welcome');

if (typeof(window.onorientationchange)!='object') window.onresize=onrotate;
else window.onorientationchange=onrotate;

onrotate();

scaleall(document.body);
if (self.initViewportKeyboard) initViewportKeyboard();
if (self.initKeyboardFocusHandlers) initKeyboardFocusHandlers();
setquicklist(document.appsettings.quicklist,true);

</script>
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
<script src="gsreplay_loader.js"></script>
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
if (isset($uiconfig['mobile']['corner_botchat'])&&$uiconfig['mobile']['corner_botchat']){
	echo "<script>ajxjs2('botchat_showcornerbutton','botchats.js',function(){botchat_showcornerbutton();});</script>";
}
?>
<?php
include 'offline.php';
?>
</body>
</html>
