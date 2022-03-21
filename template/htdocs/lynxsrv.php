<?php
include 'lb.php';
if (isset($usehttps)&&$usehttps) include 'https.php';


include 'connect.php';
include 'settings.php';

include 'forminput.php';

include 'xss.php';
xsscheck(1);

include 'evict.php';
evict_check();

login();
?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Version" content="Gyroscope <?php echo GYROSCOPE_VERSION?>" />
</head>

<body>
<a href="lynx.php">Home</a>
&nbsp; &nbsp;
<a href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><?php tr('signout');?> "<?php echo $user['dispname'];?>"</a>
<br>
___________________________________________________
<br>&nbsp;
<br>

<?php
$mode=SGET('mode');
$key=SGET('key');

switch ($mode){
	case 'showview': case "addtab":
		$modkey=SGET('modkey');
		
		if ($mode=='showview') $cmd='slv_'.str_replace('.','__',$modkey);
		else $cmd=SGET('cmd');
		
		ob_start();
		switch ($cmd){//cherry pick worthy lists
			case 'slv_core__settings': include 'icl/listsettings.inc.php'; listsettings(); break;
			case 'slv_core__users': include 'icl/listusers.inc.php'; listusers(); break;
			
			case 'showuser': include 'icl/showuser.inc.php'; showuser(); break;
			default:
			
			echo "<b>".htmlspecialchars($cmd).'</b> is not supported in text mode.<br>Press <i><-</i> to go back to the previous screen.';
		}
		$res=ob_get_clean();
		
		$res=preg_replace('/addtab\(\'\S+?\',\'[\S\s]+?\',\'(\S+?)\'/',"\" href=\"lynxsrv.php?mode=addtab&cmd=$1\" rem=\"",$res);
		$res=preg_replace('/showview\(\'(\S+)?\'/',"\" href=\"lynxsrv.php?mode=showview&modkey=$1\" ",$res);
		
		$res=str_replace('<div class="listitem"','<label>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</label><br><div class="listitem"',$res);

		$res=str_replace('class="searchsubmit" value="."','value="[Search]"',$res);
		$res=preg_replace('/onclick="ajxpgn\(\'\S+?\',document\.appsettings\.codepage\+\'\?cmd=(\S+?)&key=\'\+encodeHTML\(gid\(\'\S+\'\)\.value\)\+\'&page=(\d+)/',
			"href=\"lynxsrv.php?mode=addtab&cmd=$1&page=$2&key=".urlencode($key)."\" rem=\"",$res);
			
		$res=preg_replace('/showuser\(\'(\S+)?\'/',"\" href=\"lynxsrv.php?mode=addtab&cmd=showuser&userid=$1\" rem=\"",$res);			
		$res=preg_replace('/showuser\((\d+)?,/',"\" href=\"lynxsrv.php?mode=addtab&cmd=showuser&userid=$1\" rem=\"",$res);
		$res=str_replace(
			'<form class="listsearch" onsubmit="_inline_lookupuser(gid(\'userkey\'));return false;"',
			'<form method="GET" action="lynxsrv.php"',$res);
		$res=str_replace('<input onfocus="document.hotspot=this;" id="userkey"','<input name="mode" value="showview" type="hidden"><input name="modkey" value="core.users" type="hidden"><input id="userkey" value="'.htmlspecialchars($key).'" name="key"',$res);
						
		//echo htmlspecialchars($res);
		
		$res=str_replace('<img ','<br ',$res);
		$res=preg_replace('/<button[\S\s]+?<\/button>/','',$res);
				
		echo $res;
		
	break;	
}

?>

</body>
</html>
