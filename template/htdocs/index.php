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
$user=userinfo();
$userid=$user['userid'];
$query="select * from users where userid=?";
$rs=sql_prep($query,$db,$userid);
$usermeta=sql_fetch_assoc($rs);
?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Version" content="Gyroscope <?php echo GYROSCOPE_VERSION?>" />
	<meta name="theme-color" content="#454242" />	
	<link href="gyroscope.css" type="text/css" rel="stylesheet" />
	<link href="toolbar.css" type="text/css" rel="stylesheet" />
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

<body onload="setTimeout(scrollTo, 0, 0, 1)">
<script>
document.appsettings={codepage:'<?php echo $codepage;?>',binpage:'<?php echo $binpage;?>', beepnewchat:<?php echo $usermeta['canchat']?'true':'false';?>,fastlane:'<?php echo $fastlane;?>', autosave:null, viewmode:'desktop', views:<?php echo json_encode(array_keys($toolbaritems));?>};
</script>

<div style="display:none;"><img src="imgs/t.gif"><img src="imgs/hourglass.gif"></div>
<!-- left panel -->
<div id="tooltitle" title="double-click to reload the side view" ondblclick="if (document.viewindex) reloadview(document.viewindex);"></div>
<div id="leftview" scale:ch="105"><div id="leftview_">
	<?php foreach ($toolbaritems as $modid=>$ti){?>
	<div id="lv<?php echo $modid;?>" style="display:none;width:100%;height:100%;overflow:auto;position:absolute;"></div>
	<?php }?>
	<div id="lkv" style="height:100%;">
		<div id="lkvs"></div>
		<div id="lkvtitle">
			<a id="lkvt"></a><img id="lkvx" width="29" height="32" src="imgs/t.gif" onclick="hidelookup();">
			<?php makehelp('lookupview','lookupview',1);?>
		</div>
		<div id="lkvc"></div>
	</div>
	<?php makehelp('mainleftview','listviewpos',1);?>
</div></div>
<div id="lefticons" scale:cw="0">
<div style="margin-top:10px;margin-left:20px;">
<span class="iconbuttons">
<!-- usually there is one entity icon per list view -->
<input id="anchor_top" title="Top View" style="position:absolute;top:-60px;left:-100px;width:20px;">
<a class="noblink" id="applogo" ondblclick="window.open('./','','width=768,height=568,left=300,top=50,popup=yes,titlebar=no,menubar=no,location=no,toolbar=no,status=no');"><img src="<?php echo $codepage;?>?cmd=clogo" border="0" width="157"></a>

<a id="beltprev" onclick="beltprev();"><img class="beltprev" src="imgs/t.gif" width="16" height="32"></a>

<div id="iconbelt">
<div id="topicons" style="left:0;">
<?php
if ($dict_dir==='rtl') $toolbaritems=array_reverse($toolbaritems);
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
	if ($ti['bingo']==1) $binmode=1;
	
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
?>
</div><!-- topicons -->
</div><!-- iconbelt -->
<?php makehelp('maintopicons','topicons',1);?>

<a id="beltnext" onclick="beltnext();"><img class="beltnext" src="imgs/t.gif" width="16" height="32"></a>

</span><!-- iconbuttons -->

<div id="logoutlink">
<acronym title="<?php echo $user['dispname'];?>"><a onclick="ajxjs(<?php jsflag('setaccountpass');?>,'accounts.js');reloadtab('account','<?php tr('account_settings');?>','showaccount');addtab('account','<?php tr('account_settings');?>','showaccount');return false;"><img src="imgs/t.gif" width="16" height="16" class="admin-user"><span id="labellogin"><?php echo $user['dispname'];?></span><span id="labeldispname" style="display:none;"><?php echo $user['dispname'];?></span></a></acronym>
&nbsp; &nbsp;
<!-- acronym title="<?php tr('account_settings');?>"><a title="<?php tr('account_settings');?>" onclick="ajxjs(<?php jsflag('setaccountpass');?>,'accounts.js');reloadtab('account','<?php tr('account_settings');?>','showaccount');addtab('account','<?php tr('account_settings');?>','showaccount');return false;"><img src="imgs/t.gif" width="16" height="16" class="admin-settings"></a></acronym -->
&nbsp;
<acronym title="<?php tr('signout');?>"><a title="<?php tr('signout');?>" onclick="skipconfirm();" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><img src="imgs/t.gif" width="16" height="16" class="admin-logout"></a></acronym>
</div><!-- logout -->
</div>
</div>
<div id="statusinfo" scale:ny="25" scale:cw="0">
	<span id="statusicons">
	<a id="speechstart" onclick="ajxjs(<?php jsflag('speech_startstop');?>,'speech.js');speech_startstop();" onmouseover="hintstatus(this,'<?php tr('speech_clicktoactivate');?>');"><img src="imgs/t.gif"></a>
	<a><img onclick="document.nomoresocket=0; if (document.websocket) document.websocket.onclose();" id="wsswarn" src="imgs/t.gif" onmouseover="hintstatus(this,'websocket disrupted');"></a>
	<img onclick="this.style.display='none';" id="barcodewarn" src="imgs/t.gif" onmouseover="hintstatus(this,'barcode scanner not active');">
	<img id="diagwarn" src="imgs/t.gif" onclick="window.location.reload();" onmouseover="hintstatus(this,'dialogs suppressed. click to reload browser.');">
	<!-- img id="imecree" src="imgs/t.gif" onclick="creeime();" onmouseover="hintstatus(this,'enable Cree keyboard for the current input field');" -->
	<img id="chatindicator" src="imgs/t.gif" onclick="livechat_start();" onmouseover="hintstatus(this,document.chatstatus=='online'?'click to start live chat':'live chat unavailable');">
	<img id="lI01" style="display:inline;" src="imgs/t.gif" onmousedown="toggle_easyread();" onmouseup="toggle_easyread();" onmouseover="hintstatus(this,'hold to use a more legible font');">
	<img id="gamepadicon" src="imgs/t.gif" onmouseover="hintstatus(this,'gamepad controls');">
	<img id="gsnotesclip" src="imgs/t.gif" onclick="if (navigator.onLine) gsnotes_listclips(); else onlinestatuschanged();" onmouseover="hintstatus(this,'you have outstanding offline clipboard items');">
	</span>
	<span id="statusc"></span>
</div>

<!-- right panel -->
<div id="tabtitles" scale:cw="225"> <a id="closeall" onclick="resettabs('welcome');"><b><img src="imgs/t.gif" class="img-closeall" width="10" height="10"><?php tr('close_all_tabs');?></b></a> </div>
<div id="tabviews" style="overflow:auto;position:absolute;left:295px;height:30px;top:122px;" scale:cw="225" scale:ch="105"></div>


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

<div id="gamepadspot" style="border:solid 3px #ffab00;position:absolute;width:32px;height:32px;top:0;left:0;transition:all 200ms;z-index:3002;display:none;"></div>

<script src="lang/dict.<?php echo $lang;?>.js"></script>
<script src="nano.js?v=4_9"></script>
<script>
hdpromote('toolbar_hd.css');
hdpromote('gyroscope_hd.css');
hddemote('legacy.css');
</script>
<script src="tabs.js"></script>
<script src="viewport.js"></script>
<script src="validators.js"></script>
<script src="autocomplete.js"></script>

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


addtab('welcome','<?php tr('tab_welcome');?>','wk',null,null,{noclose:1,bingo:false});


setInterval(authpump,60000); //check if needs to re-login; comment this out to disable authentication

skipconfirm=function(){
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
if (window.console&&window.console.log) console.log('Powered by Antradar Gyroscope <?php echo GYROSCOPE_VERSION;?>');
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
