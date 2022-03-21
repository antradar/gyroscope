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
</head>

<body>

<a href="login.php?from=<?php echo $_SERVER['PHP_SELF'];?>" onmouseover="hintstatus(this,'Logout');"><?php tr('signout');?> "<?php echo $user['dispname'];?>"</a>
<br>
___________________________________________________
<br>&nbsp;<br>
<?php
foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']==='break') {
		echo '<br>~~~~~~~~<br>';continue;	
	}
	if (isset($ti['type'])&&$ti['type']==='custom'){
	?>
	<?php echo $ti['desktop'];?>
	<?php	
		continue;
	}
	
	$binmode='null';
	if ($ti['bingo']==1) $binmode=1;
	
	$action="mode=showview&modkey=$modid&bingo=$binmode";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	if (!isset($ti['icon'])||$ti['icon']=='') continue;

	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}
		
?>
<a href="lynxsrv.php?<?php echo $action;?>"><?php echo $ti['title']?></a>
<br>
<label>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~</label>
<br>
<?php
}//foreach
?>
<a href="lynxsrv.php?mode=addtab&cmd=showaccount"><?php tr('account_settings');?></a><br>


</body>
</html>
