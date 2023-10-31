<?php
include 'lb.php';
if (isset($usehttps)&&$usehttps) include 'https.php';

include 'mswitch.php'; //auto switch to mobile version


include 'connect.php';
include 'settings.php';


include 'forminput.php';

include 'xss.php';
xsscheck(1);

include 'evict.php';
evict_check();

login();

header('gsfunc: gs_index');


$user=userinfo();
$userid=$user['userid'];
$query="select * from ".TABLENAME_USERS." where userid=?";
$rs=sql_prep($query,$db,$userid);
$usermeta=sql_fetch_assoc($rs);
$quicklist=isset($usermeta['quicklist'])&&$usermeta['quicklist']?1:0;
$dark=isset($usermeta['darkmode'])?intval($usermeta['darkmode']):0;

setcookie('userdarkmode',$dark,time()+3600*24*30*6,null,null,$usehttps,true); //6 months

include 'uiconfig.php';
include 'icl/showdefleftcontent.inc.php';

if ($uiconfig['toolbar_position']=='left') $quicklist=0;

?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Version" content="Gyroscope <?php echo GYROSCOPE_VERSION?>" />
	<meta name="theme-color" content="#454242" />	
	<link id="ajxcss_gyroscope" href="gyroscope_css.php?dark=<?php echo $dark;?>&v=2" type="text/css" rel="stylesheet" />
	<link id="ajxcss_toolbar" href="toolbar_css.php?v=3&dark=<?php echo $dark;?>" type="text/css" rel="stylesheet" />
	<link href="gsnotes.css" type="text/css" rel="stylesheet" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<?php if ($dict_dir==='rtl'){?>
	<link href="rtl.css" type="text/css" rel="stylesheet" />
	<?php }?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">
	<?php if ($SQL_READONLY){
	?>
	<style>
	/* readonly mode */
	button.warn, .button.warn{display:none;}
	.recadder{display:none;}
	.changebar_anchor{display:none;}
	.savebar_anchor{display:none;}
	#lefticons{background:transparent url(imgs/readonlybg.png) repeat-x 0 0;}
	</style>
	<?php	
	}?>
</head>

<body onload="setTimeout(scrollTo, 0, 0, 1);">
<script>
document.appsettings={codepage:'<?php echo $codepage;?>',binpages:<?php echo json_encode($binpages);?>, quicklist:<?php echo $quicklist?'true':'false';?>, beepnewchat:<?php echo $usermeta['canchat']?'true':'false';?>,shortappname:'<?php echo GYROSCOPE_SHORT_APP_NAME;?>', fastlane:'<?php echo $fastlane;?>', autosave:null, viewmode:'desktop', uiconfig:<?php echo json_encode($uiconfig);?>, views:<?php echo json_encode(array_keys($toolbaritems));?>};
</script>

<div style="display:none;"><img src="imgs/t.gif"><img src="imgs/hourglass.gif"></div>
<!-- left panel -->
<div id="tooltitle" class="<?php if ($uiconfig['toolbar_position']=='left') echo 'promoted';?>" title="double-click to reload the side view" ondblclick="if (document.viewindex) reloadview(document.viewindex);"></div>
<div id="mainmenu" class="<?php if ($uiconfig['toolbar_position']=='top') echo 'silent';?>"></div>
<div id="leftview" class="<?php if ($uiconfig['toolbar_position']=='left') echo 'promoted';?>" scale:ch="105"><div id="leftview_">
	<div id="defleftview" style="width:100%;height:100%;overflow:auto;position:absolute;"><?php showdefleftcontent($quicklist);?></div>
	<?php foreach ($toolbaritems as $modid=>$ti){?>
	<div id="lv<?php echo $modid;?>" style="display:none;width:100%;height:100%;overflow:auto;position:absolute;"></div>
	<?php }?>
	<div id="lkv" style="height:100%;">
		<div id="lkvs"></div>
		<div id="lkvtitle">
			<a id="lkvt"></a><img id="lkvx" width="29" height="32" src="imgs/t.gif" onclick="hidelookup();">
			
		</div>
		<div id="lkvc"></div>
		<?php makehelp('lookupview','lookupview',1);?>
	</div>
	<?php makehelp('mainleftview','listviewpos',1);?>
	
	<div id="leftviewcloser" onclick="resetleftviews();"><img src="imgs/t.gif"></div>
</div></div>
<div id="lefticons" class="<?php if ($uiconfig['toolbar_position']=='left') echo 'solid';?>" scale:cw="0">
<div style="margin-top:<?php if ($uiconfig['toolbar_position']=='left') echo '0'; else echo '10px';?>;margin-left:<?php if ($uiconfig['toolbar_position']=='left') echo '10px'; else echo '20px';?>;">
<span class="iconbuttons">
<!-- usually there is one entity icon per list view -->
<input id="anchor_top" title="Top View" style="position:absolute;top:-60px;left:-100px;width:20px;">
<a class="noblink" id="applogo" ondblclick="window.open('./','','width=768,height=568,left=300,top=50,popup=yes,titlebar=no,menubar=no,location=no,toolbar=no,status=no');"><img src="<?php echo $codepage;?>?cmd=clogo" border="0" width="157"></a>

<a id="beltprev" onclick="beltprev();"><img class="beltprev" src="imgs/t.gif" width="16" height="32"></a>

<div id="iconbelt">
<div id="topicons" style="left:0;">
<?php
if ($dict_dir==='rtl') $toolbaritems=array_reverse($toolbaritems);
if ($uiconfig['toolbar_position']=='top'){
foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']==='break') {
		echo '<div class="break"><span></span></div>';continue;	
	}
	if (isset($ti['type'])&&$ti['type']==='custom'){
	?>
	<?php echo $ti['desktop'];?>
	<?php	
		continue;
	}
	
	$binmode='null';
	if (isset($ti['bingo'])&&$ti['bingo']==1) $binmode=1;
	
	$action="showview('".$modid."',1,null,null,null,".$binmode.");";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	if (!isset($ti['icon'])||$ti['icon']=='') continue;

	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}
		
?>	
<a onmouseover="hintstatus(this,'<?php echo $ti['title'];?>');" onclick="<?php echo $action;?>"><img class="<?php echo $ti['icon'];?>" src="imgs/t.gif" width="32" height="32"><br><?php echo $ti['title']?></a>
<?php
}//foreach
}//ui.toolbar_position
?>
</div><!-- topicons -->
</div><!-- iconbelt -->
<div id="bookmarkview" style="<?php if ($uiconfig['toolbar_position']=='top') echo 'display:none;'?>;position:absolute;top:56px;left:0;width:260px;z-index:360;overflow:auto;">
</div><!-- bookmarkview -->
<?php makehelp('maintopicons','topicons',1);?>

<a id="beltnext" onclick="beltnext();"><img class="beltnext" src="imgs/t.gif" width="16" height="32"></a>

</span><!-- iconbuttons -->

<div id="logoutlink" class="<?php if ($usermeta['haspic']) echo 'bigprofile';?> <?php if ($uiconfig['toolbar_position']=='left') echo 'moveup'; if ($uiconfig['enable_master_search']) echo ' hassearch';?>">
<?php if ($uiconfig['enable_master_search']){?>
	<span id="mastersearchanchor">
		<input id="mastersearchshadow" class="mastersearch" value="Search">
		<input id="mastersearch" class="mastersearch" placeholder="Search All" onfocus="showmastersearch();" onblur="hidemastersearch();" onkeyup="_mastersearch();">
	</span>
<?php }?>
<acronym title="<?php echo htmlspecialchars($user['dispname']);?>"><a onclick="ajxjs(<?php jsflag('setaccountpass');?>,'accounts.js');reloadtab('account','<?php tr('account_settings');?>','showaccount');addtab('account','<?php tr('account_settings');?>','showaccount');return false;"><?php if ($usermeta['haspic']){?><img src="<?php echo $codepage;?>?cmd=imguserprofile&userid=<?php echo $userid;?>" id="mainuserprofile"><?php } else {?><img src="imgs/t.gif" id="mainuserprofile" class="admin-user"><?php }?><span id="labellogin"><?php echo htmlspecialchars($user['dispname']);?></span><span id="labeldispname" style="display:none;"><?php echo htmlspecialchars($user['dispname']);?></span></a></acronym>
&nbsp; &nbsp;
<!-- acronym title="<?php tr('account_settings');?>"><a title="<?php tr('account_settings');?>" onclick="ajxjs(<?php jsflag('setaccountpass');?>,'accounts.js');reloadtab('account','<?php tr('account_settings');?>','showaccount');addtab('account','<?php tr('account_settings');?>','showaccount');return false;"><img src="imgs/t.gif" width="16" height="16" class="admin-settings"></a></acronym -->
&nbsp;
<acronym title="<?php tr('signout');?>"><a title="<?php tr('signout');?>" onclick="skipconfirm();" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><img src="imgs/t.gif" width="16" height="16" class="admin-logout"></a></acronym>
</div><!-- logout -->
</div>
</div>
<div id="statusinfo" scale:ny="25" scale:cw="0">
	<span id="statusicons">
	<img id="lI01" style="display:inline;" src="imgs/t.gif" onmousedown="toggle_easyread();" onmouseup="toggle_easyread();" onmouseover="hintstatus(this,'hold to use a more legible font');">
	<a id="speechstart" onclick="ajxjs(<?php jsflag('speech_startstop');?>,'speech.js');speech_startstop();" onmouseover="hintstatus(this,'<?php tr('speech_clicktoactivate');?>');"><img src="imgs/t.gif"></a>
	<a><img onclick="document.nomoresocket=0; if (document.websocket) document.websocket.onclose();" id="wsswarn" src="imgs/t.gif" onmouseover="hintstatus(this,'websocket disrupted');"></a>
	<img onclick="this.style.display='none';" id="barcodewarn" src="imgs/t.gif" onmouseover="hintstatus(this,'barcode scanner not active');">
	<img id="diagwarn" src="imgs/t.gif" onclick="window.location.reload();" onmouseover="hintstatus(this,'dialogs suppressed. click to reload browser.');">
	<img id="sysreswarn" src="imgs/t.gif" onclick="document.nanoavg=0;this.style.display='none';" onmouseover="hintstatus(this,'system resource critically low. click to reset indicator.');">
	<!-- img id="imecree" src="imgs/t.gif" onclick="creeime();" onmouseover="hintstatus(this,'enable Cree keyboard for the current input field');" -->
	<img id="chatindicator" src="imgs/t.gif" onclick="livechat_start();" onmouseover="hintstatus(this,document.chatstatus=='online'?'click to start live chat':'live chat unavailable');">
	<img id="gamepadicon" src="imgs/t.gif" onmouseover="hintstatus(this,'gamepad controls');">
	<img id="gsnotesclip" src="imgs/t.gif" onclick="if (navigator.onLine) gsnotes_listclips(); else onlinestatuschanged();" onmouseover="hintstatus(this,'you have outstanding offline clipboard items');">
	</span>
	<span id="statusc"></span>
</div>

<?php
$tabbase=122;
if ($uiconfig['toolbar_position']=='left') $tabbase=57;
?>
<!-- right panel -->
<div id="tabtitles" scale:cw="225"> <a id="closeall" onclick="resettabs('welcome');"><b><img src="imgs/t.gif" class="img-closeall" width="10" height="10"><?php tr('close_all_tabs');?></b></a> </div>
<div id="tabviews" class="<?php if ($uiconfig['toolbar_position']=='left') echo 'boundless';?>" style="overflow:auto;position:absolute;left:295px;height:30px;top:<?php echo $tabbase;?>px;" scale:cw="225" scale:ch="105"></div>

<div id="tabexpander" onclick="toggletabdock();"></div>

<div id="vsptr" onclick="setquicklist(document.appsettings.quicklist?0:1);" <?php if ($uiconfig['toolbar_position']=='left') echo 'style="display:none;"';?>></div>
<div id="sptr" scale:ch="104"></div>

<div id="fsmask"></div>
<div id="fstitlebar">
	<div id="fstitle"></div>
	<a id="fsclose" onclick="closefs();"><img width="10" height="10" class="img-closeall" src="imgs/t.gif"></a>
</div>
<div id="fsview"></div>

<div style="display:none;">
<audio id="gschatsound_msgin"><source src="chatsounds/msgin.mp3"></audio>
<audio id="gschatsound_newchat"><source src="chatsounds/newchat.mp3"></audio>
</div>

<div style="position:absolute;top:25px;right:20px;width:160px;">
	<?php makehelp('myaccountlink','mysettings',1,-120);?>
</div>

<?php
if ($uiconfig['enable_master_search']){?>
<div id="mainsearchview_">
	<div id="mainsearchview"></div>
</div>
<?php } ?>

<div id="gsstickerview" onclick="this.style.display='none';">
	<div id="gsstickercontent"></div>
</div>

<div id="gamepadspot" style="border:solid 3px #ffab00;position:absolute;width:32px;height:32px;top:0;left:0;transition:all 200ms;z-index:3002;display:none;"></div>

<div id="callout" style="z-index:4000;filter:alpha(opacity=0);opacity:0;transition:top 120ms,left 120ms,opacity 180ms;position:absolute;top:-80px;left:250px;"><img src="imgs/callout.png" style="width:56px;"></div>

<script src="lang/dict.<?php echo $lang;?>.js"></script>
<script src="nano.js?v=5_1"></script>
<script>
hdpromote('toolbar_hd_css.php?dark=<?php echo $dark;?>');
hdpromote('gyroscope_hd_css.php?dark=<?php echo $dark;?>');
hddemote('legacy.css');
document.nanoperf=500; //in microseconds, set to null or comment out to disable
</script>
<script src="tabs.js?v=2"></script>
<script src="viewport.js"></script>
<script src="validators.js"></script>
<script src="autocomplete.js?v=4"></script>
<script>
	setquicklist(document.appsettings.quicklist,true);
</script>
<?php


if ($usermeta['usegamepad']){
?>
<script src="gamepad.js"></script>
<?php
}//gamepad
?>
<?php if (isset($_GET['keynav'])){?>
<script src="blind.js"></script>
<?php }?>
<script>
window.onresize=autosize;
autosize();
setTimeout(function(){scaleall(document.body);},100);

<?php if ($uiconfig['toolbar_position']=='left'){?>
addtab('welcome','<img src="imgs/t.gif" class="ico-homedash"><span style="display:none;">Home</span>','dash_default',function(){showmainmenu();},null,{noclose:1,bingo:false,tabctx:'dash'});
tabviewfunc_welcome=function(){
	showmainmenu();
}

<?php } else {?>
addtab('welcome','<?php tr('tab_welcome');?>','wk',function(){
	//set this func block to null later
	//ajxjs(null,'scal.js');
	//scal_init('test',2023,12);
},null,{noclose:1,bingo:false});
<?php }?>

setInterval(authpump,60000); //check if needs to re-login; comment this out to disable authentication

skipconfirm=function(){
	if (document.websocket) document.websocket.onclose=null;
	if (document.confirmskipper) clearTimeout(document.confirmskipper);
	window.onbeforeunload=null;	
	document.confirmskipper=setTimeout(function(){
		window.onbeforeunload=function(){
			return document.dict['confirm_exit'];	
		}	
	},500);
}


window.onbeforeunload=function(){
	return document.dict['confirm_exit'];
}


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

<script src="smartcard.js"></script>
<!-- script src="gsinit.js"></script -->
<!-- script src="imecree.js"></script -->

<script>
if (navigator.serviceWorker&&navigator.serviceWorker.register){
	navigator.serviceWorker.register('service_worker.js');
}
</script>

<?php if ($enablelivechat){
	include 'livechat.php';
	livechat();
}?>
<script>
if (window.Notification) Notification.requestPermission();
if (window.console&&window.console.log) console.log('%c Powered by Antradar Gyroscope <?php echo GYROSCOPE_VERSION;?>','color:#72ADDE;');
window.onload=function(){
	<?php if ($enablelivechat){?>
	livechat_init();	

	<?php }?>
	document.smartcard=true;
	smartcard_init('cardreader',{
		'noplugin':function(){document.smartcard=null;},
		'nohttps':function(){document.smartcard=null;}
	});	
}

</script>
<?php
include 'offline.php';

?>
</body>
</html>
