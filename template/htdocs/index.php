<?
//include 'https.php'; //enforcing HTTPS on production server

include 'mswitch.php'; //auto switch to mobile version

include 'settings.php';

//comment out the following lines to disable authentication
include 'auth.php';
login();
$user=userinfo();

?>
<html>
<head>
	<title>Antradar Gyroscope&trade; &nbsp;Starting Point</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='gyroscope.css' type='text/css' rel='stylesheet'>
</head>

<body onload="setTimeout(scrollTo, 0, 0, 1)">
<script>
document.appsettings={codepage:'<?echo $codepage;?>', viewcount:<?echo $viewcount;?>};
</script>

<div style="display:none;">
	<img src="imgs/lefttab.png"><img src="imgs/tabbg.gif"><img src="imgs/tab_on.png"><img src="imgs/tab_off.png">
	<img src="imgs/close_on.png"><img src="imgs/close_off.png"><img src="imgs/addrec.gif"><img src="imgs/mg.gif">
	<img src="imgs/close_on_.png"><img src="imgs/close_off_.png">
	<img src="imgs/calel.gif"><img src="imgs/caler.gif">	
</div>

<!-- left panel -->
<div id="tooltitle"></div>
<div id="leftview" scale:ch="105">
<div id="lv0" style="display:none;width:100%;height:100%;overflow:auto;"></div>
<div id="lv1" style="display:none;width:100%;height:100%;overflow:auto;"></div>
<!-- add more list view (lv) panels for additional entity groups -->
</div>
<div id="lefticons" scale:cw="0">
<div style="margin-top:5px;margin-left:10px;">
<span class="iconbuttons">
<!-- usually there is one entity icon per list view -->
<input id="anchor_top" title="Top View" style="position:absolute;top:-60px;left:0;">
<acronym title="Entity 1"><a href=# title="Entity 1" onmouseover="hintstatus('Entity 1',this);" onclick="showview(0);"><img src="imgs/bigicon1.gif"></a></acronym>
<acronym title="Entity 2"><a href=# title="Entity 2" onmouseover="hintstatus('Entity 2',this);" onclick="showview(1);"><img src="imgs/bigicon2.gif"></a></acronym>
</span><!-- iconbuttons -->

<div id="logoutlink">
<a href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');">logout <em><?echo $user['login'];?></em></a>
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

window.onbeforeunload=function(){
	return 'Are you sure you want to exit Gyroscope?';
}

</script>
</body>
</html>
