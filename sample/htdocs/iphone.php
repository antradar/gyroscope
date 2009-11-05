<?
include 'settings.php';
include 'auth.php';
login();
$user=userinfo();
?>
<html>
<head>
	<title>Antradar Gyroscope(tm) Sample Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; user-scalable=0;"/>
	<link href='iphone/gyrodemo.css' type='text/css' rel='stylesheet'>
<style>
body{font-size:16px;font-family:helvetica;}
.listitem{padding-left:10px;font-size:22px;height:30px;}
.listitem a, .listitem a:hover, .listitem a:visited, .listitem a:link{
	display:block;
	padding-top:3px;
	color:#000000;
	text-decoration:none;
}

</style>

</head>

<body onload="setTimeout(scrollTo, 0, 0, 1);">
<div style="font-size:14px;padding-left:5px;padding-right:5px;height:20px;">
<a href=# onclick="showpanel(0);">Main</a>
&nbsp;|&nbsp;
<a href=# onclick="showpanel(4);">Views</a>
&nbsp;|&nbsp;
<a href=# onclick="showpanel(1);">Opened</a>
&nbsp;|&nbsp;
<a href=# onclick="showpanel(2);">Records</a>
&nbsp;|&nbsp;
<a href="login.php?from=<?echo $_SERVER['PHP_SELF'];?>">Logout</a>
</div>
<div id="panel0" style="height:395px;">
<div class="listitem"><a href=# onclick="showview(0);showpanel(4);">Landlords</a></div>
<div class="listitem"><a href=# onclick="showview(1);showpanel(4);">Properties</a></div>
<div class="listitem"><a href=# onclick="showview(2);showpanel(4);">Tenants</a></div>
<div class="listitem"><a href=# onclick="showview(3);showpanel(4);">Leases</a></div>
</div>

<div id="panel1" style="height:395px;display:none;">
<div id="tabtitles"></div>
</div>

<div id="panel2" style="height:395px;display:none;">
<div id="tabviews"></div>
</div>

<div id="panel3" style="height:395px;display:none;">
</div>

<div id="panel4" style="height:395px;display:none;">
<div id="tooltitle" style="background-color:#444444;"></div>
<div id="views"></div>
</div>
<div id="statusinfo" style="display:none;"></div>
<script>
document.appsettings={codepage:'<?echo $codepage;?>',viewcount:<?echo $viewcount;?>};
</script>
<script src="iphone/nano.js"></script>
<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js"></script>
<script src="iphone/validators.js"></script>
<script src="iphone/autocomplete.js"></script>

<script>
//showview(0);
//addtab('welcome','Welcome','wk');
//setInterval(authpump,300000);
</script>
</body>
</html>
