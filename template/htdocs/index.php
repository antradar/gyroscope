<?
include 'lb.php';
if (isset($usehttps)&&$usehttps) include 'https.php';

include 'mswitch.php'; //auto switch to mobile version


include 'connect.php';
include 'settings.php';

include 'evict.php';
evict_check();

login();
$user=userinfo();

?>
<!doctype html>
<html>
<head>
	<title><?echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='gyroscope.css' type='text/css' rel='stylesheet'>
	<link href='toolbar.css' type='text/css' rel='stylesheet'>
	<meta name="Version" content="Gyroscope <?echo GYROSCOPE_VERSION?>">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
</head>

<body onload="setTimeout(scrollTo, 0, 0, 1)">
<script>
document.appsettings={codepage:'<?echo $codepage;?>', fastlane:'<?echo $fastlane;?>', views:<?echo json_encode(array_keys($toolbaritems));?>};
</script>

<div style="display:none;"><img src="imgs/t.gif"><img src="imgs/hourglass.gif"></div>
<!-- left panel -->
<div id="tooltitle"></div>
<div id="leftview" scale:ch="105"><div id="leftview_">
	<?foreach ($toolbaritems as $modid=>$ti){?>
	<div id="lv<?echo $modid;?>" style="display:none;width:100%;height:100%;overflow:auto;position:absolute;"></div>
	<?}?>
	<div id="lkv" style="height:100%;">
		<div id="lkvs"></div>
		<div id="lkvtitle"><a id="lkvt"></a><img id="lkvx" width="29" height="32" src="imgs/t.gif" onclick="hidelookup();"></div>
		<div id="lkvc"></div>
	</div>
</div></div>
<div id="lefticons" scale:cw="0">
<div style="margin-top:10px;margin-left:20px;">
<span class="iconbuttons">
<!-- usually there is one entity icon per list view -->
<input id="anchor_top" title="Top View" style="position:absolute;top:-60px;left:-100px;width:20px;">
<a class="noblink" id="applogo"><img src="imgs/clogo.gif" border="0"></a>

<a id="beltprev" onclick="beltprev();"><img class="beltprev" src="imgs/t.gif" width="16" height="32"></a>

<div id="iconbelt">
<div id="topicons" style="left:0;">
<?foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']=='break') {
		echo '<div class="break"><span></span></div>';continue;	
	}
	if (isset($ti['type'])&&$ti['type']=='custom'){
	?>
	<?echo $ti['desktop'];?>
	<?	
		continue;
	}
	
	$action="showview('".$modid."');";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	if (!isset($ti['icon'])||$ti['icon']=='') continue;

	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}
		
?>	
<?/* <acronym title="<?echo $ti['title'];?>"> */?>
<a onmouseover="hintstatus(this,'<?echo $ti['title'];?>');" onclick="<?echo $action;?>"><img class="<?echo $ti['icon'];?>" src="imgs/t.gif" width="32" height="32"><br><?echo $ti['title']?></a>
<? /* </acronym> */ ?>
<?
}//foreach
?>
</div><!-- topicons -->
</div><!-- iconbelt -->

<a id="beltnext" onclick="beltnext();"><img class="beltnext" src="imgs/t.gif" width="16" height="32"></a>

</span><!-- iconbuttons -->

<div id="logoutlink">
<acronym title="<?echo $user['dispname'];?>"><img src="imgs/t.gif" width="16" height="16" class="admin-user"></acronym><span id="labellogin"><?echo $user['login'];?></span><span id="labeldispname" style="display:none;"><?echo $user['dispname'];?></span>
&nbsp; &nbsp;
<acronym title="<?tr('account_settings');?>"><a title="<?tr('account_settings');?>" onclick="ajxjs(self.setaccountpass,'accounts.js');reloadtab('account','<?tr('account_settings');?>','showaccount');addtab('account','<?tr('account_settings');?>','showaccount');return false;"><img src="imgs/t.gif" width="16" height="16" class="admin-settings"></a></acronym>
&nbsp;
<acronym title="<?tr('signout');?>"><a title="<?tr('signout');?>" onclick="skipconfirm();" href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><img src="imgs/t.gif" width="16" height="16" class="admin-logout"></a></acronym>
</div><!-- logout -->
</div>
</div>
<div id="statusinfo" scale:ny="25" scale:cw="0">
	<span id="statusicons">
	<a id="speechstart" onclick="speech_startstop();" onmouseover="hintstatus(this,'click to activate speech recognition');"><img src="imgs/t.gif"></a>
	<img id="wsswarn" src="imgs/t.gif" onmouseover="hintstatus(this,'websocket not supported');">
	<img onclick="this.style.display='none';" id="barcodewarn" src="imgs/t.gif" onmouseover="hintstatus(this,'barcode scanner not active');">
	</span>
	<span id="statusc"></span>
</div>

<!-- right panel -->
<div id="tabtitles" scale:cw="225"> <a id="closeall" style="" onclick="resettabs('welcome');"><b><img src="imgs/t.gif" class="img-closeall" width="10" height="10"><?tr('close_all_tabs');?></b></a> </div>
<div id="tabviews" style="overflow:auto;position:absolute;left:295px;height:30px;top:122px;" scale:cw="225" scale:ch="105"></div>

<div id="sptr" scale:ch="104"></div>

<div id="fsmask"></div>
<div id="fstitlebar">
	<div id="fstitle"></div>
	<a id="fsclose" onclick="closefs();"><img width="10" height="10" class="img-closeall" src="imgs/t.gif"></a>
</div>
<div id="fsview"></div>

<script src="lang/dict.<?echo $lang;?>.js"></script>
<script src="nano.js"></script>
<script>
hdpromote('toolbar_hd.css');
</script>
<script src="tabs.js"></script>
<script src="viewport.js"></script>
<script src="validators.js"></script>
<script src="autocomplete.js"></script>
<?if (isset($_GET['keynav'])){?>
<script src="blind.js"></script>
<?}?>
<script>
window.onresize=autosize;
autosize();
setTimeout(function(){scaleall(document.body);},100);


addtab('welcome','<?tr('tab_welcome');?>','wk',null,null,{noclose:1});




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
<script src="wss.js"></script>
<script>
<?include 'ws_js.php';?>
</script>

<script src="speech.js"></script>

<?/*
<script src="barcodescanner.js"></script>
<script>
	window.onblur=function(){if (gid('barcodewarn')) gid('barcodewarn').style.display='inline';}
	window.onfocus=function(){if (gid('barcodewarn')) gid('barcodewarn').style.display='none';}
</script>
*/
?>

<script src="smartcard.js"></script>
<script>
document.smartcard=true;
smartcard_init('cardreader',{
	'noplugin':function(){document.smartcard=null;},
	'nohttps':function(){document.smartcard=null;}
});
</script>
<script src="tiny_mce/mceloader.js"></script>
<script>
if (window.Notification) Notification.requestPermission();
</script>
</body>
</html>
