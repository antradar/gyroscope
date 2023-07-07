<?php
include 'lb.php';
if ($usehttps) include 'https.php';

include 'connect.php';
include 'settings.php';

include 'evict.php';
evict_check();

login();
$user=userinfo();
$userid=$user['userid'];
$query="select * from ".TABLENAME_USERS." where userid=?";
$rs=sql_prep($query,$db,$userid);
$usermeta=sql_fetch_assoc($rs);

?>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<link href='iphone/gyrodemo_css.php' type='text/css' rel='stylesheet'>
	<link href='toolbar_kpw.css' type='text/css' rel='stylesheet'>
	<link href='iphone/kpw.css' type='text/css' rel='stylesheet'>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<?php
	include 'appicon.php';
	?>
<style>
body{font-family:helvetica;}
.menuitem{padding-left:10px;height:30px;float:left;margin-right:3px;}
.menuitem a, .menuitem a:hover, .menuitem a:visited, .menuitem a:link{
	display:block;
	padding-top:3px;
	color:#000000;
	text-decoration:none;
}
</style>

</head>
<body onload="setTimeout(scrollTo, 0, 0, 1);">

<div id="toolbg" style="position:fixed;width:100%;z-index:1000;top:0;background:#000000;"></div>
<div id="toolicons" style="position:fixed;width:100%;z-index:2000;top:0;">

	<?php
	$tcount=1;
	foreach ($toolbaritems as $ti) if (isset($ti['icon'])&&$ti['icon']!='') $tcount++;
	?>
	
	<div id="toollist" style="overflow:hidden;width:100%;"><div style="width:<?php echo 102*($tcount);?>px;">
	<div class="menuitem"><a onclick="showtab('welcome');document.viewindex=null;"><img width="64" height="64" src="imgs/t.gif" class="img-home"></a></div>
	<?php foreach ($toolbaritems as $modid=>$ti){
		if ($ti['type']==='break') continue;
		if ($ti['noiphone']) continue;	
		if ($ti['type']==='custom'){
		?>
		<?php echo $ti['iphone'];?>
		<?php	
			continue;
		}
		
		$action="showview('".$modid."',1,1);";
		if ($ti['action']!='') $action=$ti['action'];
		if (!isset($ti['icon'])||$ti['icon']=='') continue;
		
		if (isset($ti['groups'])){
			$canview=0;
			$gs=explode('|',$ti['groups']);
			foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
			if (!$canview) continue;	
		}
		
	?>
	<div class="menuitem"><a href=# onclick="<?php echo $action;?>return false;"><img class="<?php echo $ti['icon'];?>" src="imgs/t.gif" border="0" width="64" height="64"></a></div>
	<?php }?>

	</div></div>
	<span id="labellogin" style="display:none;"><?php echo $user['login'];?></span><span id="labeldispname" style="display:none;"><?php echo $user['dispname'];?></span>	
	<a onclick="if (document.websocket) document.websocket.onclose=null;" href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" style="position:absolute;top:20px;right:30px;"><img border="0" width="32" height="32" src="imgs/t.gif" class="admin-logout"></a>
</div><!-- toolicons -->
<div id="pusher" style="width:100%;height:100px;"></div>

<div style="display:none;">
	<img src="imgs/t.gif"><img src="imgs/hourglass.gif">
</div>
<div id="leftview" style="float:left;margin-left:10px;width:100%;">
	<div id="tooltitle" onclick="if (document.viewindex) reloadview(document.viewindex,0,1);" style="width:100%;position:fixed;top:100px;z-index:1000;height:50px;"></div>
	<div id="tooltitleshadow" style="width:100%;height:50px;"></div>
	<div id="lvviews">
	<?php foreach ($toolbaritems as $modid=>$ti){?>
		<div id="lv<?php echo $modid;?>" style="background-color:#ffffff;display:none;"></div>
	<?php }?>	
	</div>
	<div id="lkv" style="height:100%;">
		<div id="lkvtitle"><a id="lkvt"></a><img id="lkvx" src="imgs/t.gif" onclick="hidelookup();" width="30" height="24"></div>
		<div id="lkvc"></div>
	</div>
	
</div>
<div id="content" style="float:left;width:320px;">

	<div id="backlist" style="display:none;position:fixed;left:0;width:100%;z-index:1000;"><a id="backlistbutton"><img onclick="navback();" src="iphone/back_kpw.png"></a></div>
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
	<a id="fsclose" onclick="closefs();"><img width="20" height="20" class="img-closeall" src="imgs/t.gif"></a>
</div>
<div id="fsview"></div>

<script>
document.appsettings={codepage:'<?php echo $codepage;?>',binpages:<?php echo json_encode($binpages);?>,beepnewchat:<?php echo $usermeta['canchat']?'true':'false';?>,fastlane:'<?php echo $fastlane;?>', viewmode:'kpw', views:<?php echo json_encode(array_keys($toolbaritems));?>};
</script>
<script src="lang/dict.<?php echo $lang;?>.js"></script>
<script src="nano.js"></script>
<script src="iphone/tabs.js"></script>
<script src="iphone/viewport.js"></script>
<script src="validators.js"></script>
<script src="autocomplete.js"></script>

<script>

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
	
	if (!document.appsettings.cw) document.appsettings.cw=320;
	if (document.appsettings.cw<document.body.clientWidth) document.appsettings.cw=document.body.clientWidth;
		
	var cw=document.appsettings.cw;
	var vw=document.body.clientWidth;
	
	showdeck();
	gid('leftview').style.width=vw+'px';
	gid('leftview').style.marginLeft=0;
	gid('backlist').style.display='block';
	gid('backlistshadow').style.display='block';
	gid('tooltitle').style.width=vw+'px';
	gid('toollist').style.width=document.documentElement.clientWidth-50+'px';//'280px';
	gid('tabtitleshadow').style.display='none';
	gid('content').style.width=vw+'px';
	
	document.viewheight=vw+30;
	scaleall(document.body);
	document.iphone_portrait=1;
	
	hidelookup();
		
}

addtab('welcome','<?php tr('tab_welcome');?>','wk',null,null,{noclose:true});

function onrotate(){
	if (document.resizetimer) clearTimeout(document.resizetimer);
	document.resizetimer=setTimeout(function(){
		rotate();
		setTimeout(rotate,500);
	},100);
}

setInterval(authpump,60000); //comment out to disable authentication

addtab('welcome','Welcome','wk',null,null,{noclose:true});

//override
function scaleall(root){
  var i,j;
  var idh=ch();
  var idw=cw();
  
  var os=root.getElementsByTagName('div'); //AKB#2
  
	gid('tabviews').style.height=(idh-210)+'px';
	gid('lvviews').style.height=(idh-210)+'px';

  if (document.rowcount){
		gid('tabtitleshadow').style.height=(56*document.rowcount-1)	  
  }

  gid('lkv').style.height=(idh-145)+'px';
  gid('lkvc').style.height=(idh-150)+'px';
  
  gid('fsmask').style.width=idw+'px';
  gid('fsmask').style.height=idh+'px';

  gid('fsview').style.width=idw-40+'px';
  gid('fsview').style.height=idh-100+'px';
  
  gid('fstitlebar').style.width=idw-40+'px';   
  	   
}

scaleall(document.body);
</script>
</body>
</html>
