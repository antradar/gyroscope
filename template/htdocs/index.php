<?
include 'lb.php';
//include 'https.php'; //enforcing HTTPS on production server

include 'mswitch.php'; //auto switch to mobile version


include 'settings.php';

include 'evict.php';
evict_check();

login();
$user=userinfo();

?>
<!doctype html>
<html>
<head>
	<title>Antradar Gyroscope&trade; &nbsp;Starting Point</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='gyroscope.css' type='text/css' rel='stylesheet'>
	<link href='toolbar.css' type='text/css' rel='stylesheet'>
	<meta name="Version" content="Gyroscope <?echo GYROSCOPE_VERSION?>">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>

<body onload="setTimeout(scrollTo, 0, 0, 1)">
<script>
document.appsettings={codepage:'<?echo $codepage;?>', fastlane:'<?echo $fastlane;?>', viewcount:<?echo $viewcount;?>};
</script>

<div style="display:none;"><img src="imgs/t.gif"><img src="imgs/hourglass.gif"></div>
<!-- left panel -->
<div id="tooltitle"></div>
<div id="leftview" scale:ch="105"><div id="leftview_">
	<?for ($i=0;$i<=$viewcount;$i++){?>
	<div id="lv<?echo $i;?>" style="display:none;width:100%;height:100%;overflow:auto;position:absolute;"></div>
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
<?foreach ($toolbaritems as $ti){
	if ($ti['type']=='break') {
		echo '<div class="break"><span></span></div>';continue;	
	}
	if ($ti['type']=='custom'){
	?>
	<?echo $ti['desktop'];?>
	<?	
		continue;
	}
	
	$action='';
	if (is_numeric($ti['viewindex'])) $action='showview('.$ti['viewindex'].');';
	if ($ti['action']!='') $action.=$ti['action'];
	
?>	
<?/* <acronym title="<?echo $ti['title'];?>"> */?>
<a onmouseover="hintstatus(this,'<?echo $ti['title'];?>');" onclick="<?echo $action;?>"><img class="<?echo $ti['icon'];?>" src="imgs/t.gif" width="32" height="32"><br><?echo $ti['title']?></a>
<? /* </acronym> */ ?>
<?}?>
</span><!-- iconbuttons -->

<div id="logoutlink">
<img src="imgs/t.gif" width="16" height="16" class="admin-user"><?echo $user['login'];?>
&nbsp; &nbsp;
<acronym title="Account Settings"><a title="Account Settings" onclick="ajxjs(self.setaccountpass,'accounts.js');reloadtab('account','Account Settings','showaccount');addtab('account','Account Settings','showaccount');<?if ($user['groups']['accounts']){?>ajxjs(self.showuser,'users_js.php');showview(1);<?}?>return false;"><img src="imgs/t.gif" width="16" height="16" class="admin-settings"></a></acronym>
&nbsp;
<acronym title="Sign Out"><a title="Sign Out" onclick="skipconfirm();" href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><img src="imgs/t.gif" width="16" height="16" class="admin-logout"></a></acronym>
</div><!-- logout -->
</div>
</div>
<div id="statusinfo" scale:ny="25" scale:cw="0">
	<span id="statusicons">
	<a id="speechstart" onclick="speech_startstop();" onmouseover="hintstatus(this,'click to activate speech recognition');"><img src="imgs/t.gif"></a>
	<img id="wsswarn" src="imgs/t.gif" onmouseover="hintstatus(this,'websocket not supported');">
	</span>
	<span id="statusc"></span>
</div>

<!-- right panel -->
<div id="tabtitles" scale:cw="225"> <a id="closeall" style="" onclick="resettabs('welcome');"><b><img src="imgs/t.gif" class="img-closeall" width="10" height="10">Close All</b></a> </div>
<div id="tabviews" style="overflow:auto;position:absolute;left:295px;height:30px;top:122px;" scale:cw="225" scale:ch="105"></div>

<div id="sptr" scale:ch="104"></div>

<div id="fsmask"></div>
<div id="fstitlebar">
	<div id="fstitle"></div>
	<a id="fsclose" onclick="closefs();"><img width="10" height="10" class="img-closeall" src="imgs/t.gif"></a>
</div>
<div id="fsview"></div>

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


//showview(0); //uncomment this line if you want to load the first list automatically


addtab('welcome','Welcome','wk',null,null,{noclose:1});




setInterval(authpump,300000); //check if needs to re-login; comment this out to disable authentication

skipconfirm=function(){
	if (document.confirmskipper) clearTimeout(document.confirmskipper);
	window.onbeforeunload=null;	
	document.confirmskipper=setTimeout(function(){
		window.onbeforeunload=function(){
			return 'Are you sure you want to exit Gyroscope?';	
		}	
	},500);
}


window.onbeforeunload=function(){
	return 'Are you sure you want to exit Gyroscope?';
}

</script>
<script src="wss.js"></script>
<script>
<?include 'ws_js.php';?>
</script>

<script src="speech.js"></script>
<script src="smartcard.js"></script>
<script>
document.smartcard=true;
smartcard_init('cardreader',{
	'noplugin':function(){document.smartcard=null;},
	'nohttps':function(){document.smartcard=null;}
});
</script>

</body>
</html>
