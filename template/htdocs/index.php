<?
//include 'https.php'; //enforcing HTTPS on production server
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
<style>
</style>

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
<acronym title="Entity 1"><a href=# onmouseover="hintstatus('Entity 1',this);" onclick="showview(0);"><img src="imgs/bigicon1.gif"></a></acronym>
<acronym title="Entity 2"><a href=# onmouseover="hintstatus('Entity 2',this);" onclick="showview(1);"><img src="imgs/bigicon2.gif"></a></acronym>
</span><!-- iconbuttons -->

<div id="logoutlink">
<a href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');">logout <em><?echo $user['login'];?></em></a>
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

<script>
window.onresize=autosize;
autosize();

//showview(0); //uncomment this line if you want to load the first list automatically

addtab('welcome','Welcome','wk');
setInterval(authpump,300000); //check if needs to re-login; comment this out to disable authentication
</script>
</body>
</html>
