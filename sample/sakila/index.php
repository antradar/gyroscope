<?
include 'lb.php';
//include 'https.php'; //enforcing HTTPS on production server

include 'mswitch.php'; //auto switch to mobile version


//comment out the following lines to disable authentication
include 'auth.php';

include 'settings.php';

include 'evict.php';
evict_check();

login();
$user=userinfo();

?>
<html>
<head>
	<title>Sakila-Gyroscope</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='gyroscope.css' type='text/css' rel='stylesheet'>
	<link href='toolbar.css' type='text/css' rel='stylesheet'>
	<meta name="Version" content="Gyroscope <?echo GYROSCOPE_VERSION?>">
</head>

<body onload="setTimeout(scrollTo, 0, 0, 1)">
<script>
document.appsettings={codepage:'<?echo $codepage;?>', viewcount:<?echo $viewcount;?>};
</script>
<div style="display:none;"><img src="imgs/t.gif"><img src="imgs/hourglass.gif"></div>
<!-- left panel -->
<div id="tooltitle"></div>
<div id="leftview" scale:ch="105">
	<?for ($i=0;$i<=$viewcount;$i++){?>
	<div id="lv<?echo $i;?>" style="display:none;width:100%;height:100%;overflow:auto;position:absolute;"></div>
	<?}?>
	<div id="lkv" style="height:100%;">
		<div id="lkvs"></div>
		<div id="lkvtitle"><a id="lkvt"></a><img id="lkvx" width="30" height="30" class="img-close" src="imgs/t.gif" onclick="hidelookup();"></div>
		<div id="lkvc"></div>
	</div>
</div>
<div id="lefticons" scale:cw="0">
<div style="margin-top:5px;margin-left:10px;">
<span class="iconbuttons">
<!-- usually there is one entity icon per list view -->
<input id="anchor_top" title="Top View" style="position:absolute;top:-60px;left:-100px;width:20px;">
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
<acronym title="<?echo $ti['title'];?>"><a href=# title="<?echo $ti['title'];?>" onmouseover="hintstatus('<?echo $ti['title'];?>',this);" onclick="<?echo $action;?>"><img class="<?echo $ti['icon'];?>" src="imgs/t.gif" width="32" height="32"></a></acronym>
<?}?>
</span><!-- iconbuttons -->

<div id="logoutlink">
<a onclick="skipconfirm();" href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');">logout <em><?echo $user['login'];?></em></a>
<div style="padding-top:5px;font-weight:normal;">
<a onclick="ajxjs(self.setaccountpass,'accounts.js');reloadtab('account','Account Settings','showaccount');addtab('account','Account Settings','showaccount');">Account Settings</a>
</div>
</div><!-- logout -->
</div>
</div>
<div id="statusinfo" scale:ny="25" scale:cw="0"></div>

<!-- right panel -->
<div id="tabtitles" scale:cw="225"></div>
<div id="tabviews" style="overflow:auto;position:absolute;left:225px;height:30px;top:80px;" scale:cw="225" scale:ch="105"></div>

<div id="sptr" scale:ch="104"></div>

<script src="nano.js"></script>
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

</body>
</html>
