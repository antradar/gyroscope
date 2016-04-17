<?
include 'lb.php';
//include 'https.php'; //enforcing HTTPS on production server
include 'connect.php';
include 'auth.php';
include 'xss.php';



$csrfkey=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g'));
$csrfkey2=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g',time()-3600));

$error_message='';

$passreset=0;

if ($_POST['password']||$_POST['login']){
xsscheck();

	$cfk=$_POST['cfk'];
	if ($cfk!=$csrfkey&&$cfk!=$csrfkey2){
		header('HTTP/1.0 403 Forbidden');
		header('X-STATUS: 403');
		header('Location: index.php');
		die('Access Denied');		
	}
	
  $password=md5($dbsalt.$_POST['password']);
  $raw_login=$_POST['login'];
  $login=str_replace("'",'',$raw_login);
  
  $query="select * from ".TABLENAME_USERS." where login='$login' and password='$password' and active=1 and virtual=0";
  $rs=sql_query($query,$db);  
  if ($myrow=sql_fetch_array($rs)){
	  
	  $userid=$myrow['userid'];
	  $passreset=$myrow['passreset'];

	  $needcert=$myrow['needcert'];
	  $certid=$_POST['certid'];
	  $certhash=md5($dbsalt.$certid);
	  $certhash_=$myrow['certhash'];

	  $certokay=1;

	  if ($needcert){
		if ($certhash!=$certhash_){
			$certerror='Invalid ID card';
			$certokay=0;
		}
		if ($certid=='') {
			$certerror='A smart card is required to sign in';
			$certokay=0;
		}
	  }

	if ($_POST['passreset']){
		$op=$_POST['password'];
		$np=$_POST['newpassword'];
		$np2=$_POST['newpassword2'];
		if ($np!=$np2||$op==$np||trim($np)==''){
			if ($np==$op) $error_message='new password must be different';
			if ($np!=$np2) $error_message='new passwords mismatch';
			if (trim($np)=='') $error_message='you must specify a new password';
		} else {
			$newpass=md5($dbsalt.$np);
			$query="update users set password='$newpass', passreset=0 where userid=$userid";
			sql_query($query,$db);
			$passreset=0;	
		}	  
	}
		  	  	  
	  if ($passreset){

	  } else {

	if ($certokay){	  
	  $groupnames=$myrow['groupnames'];
	  $auth=md5($salt.$userid.$groupnames.$salt.$raw_login);
	  
	  setcookie('auth',$auth);
	  setcookie('userid',$userid);
	  setcookie('login',$login);
	  setcookie('groupnames',$groupnames);
	  
	  if (isset($_GET['from'])&&trim($_GET['from'])!='') {
		  $from=$_GET['from'];
		  $from=str_replace('://','',$from);
		  $from=str_replace("\r",'-',$from);
		  $from=str_replace("\n",'-',$from);
		  $from=str_replace(":",'-',$from);
		  header('Location: '.$from);
	  } else header('Location:index.php');
	  
	  die();
		} else {
			$error_message=$certerror;
		}
  	}
  } else $error_message='invalid username or password';
} else {
	setcookie('userid',NULL,time()-3600);
	setcookie('login',NULL,time()-3600);
	setcookie('auth',NULL,time()-3600);
	setcookie('groupnames',NULL,time()-3600);	
}
?>
<html>
<head>
	<title>Login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="refresh" content="1800" />
	<meta name = "viewport" content = "width = device-width, init-scale=1, user-scalable=0" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<style>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;}
#loginbox__{width:320px;margin:0 auto;background-color:rgba(200,200,200,0.4);margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:left;}
.powered{color:#000000;text-align:right;font-size:12px;width:320px;margin:0 auto;padding-top:10px;}
#loginbutton{width:140px;-webkit-appearance: none;}
#cardlink, #passlink{text-align:center;padding-top:10px;}

#cardinfo{padding:5px;font-size:12px;padding-left:26px;background:#fcfcdd url(imgs/smartcard.png) no-repeat 5px 50%;margin-bottom:10px;display:none;}

@media screen and (max-width:400px){
	#loginbox__,.powered{width:90%;}
	#loginbox__{margin-top:50px;}
}

@media screen and (max-width:300px){
	#loginbutton{width:auto;padding-left:5px;padding-right:5px;}
}

@media screen and (max-width:260px){
	.powered{text-align:center;}
	.powered span{display:block;padding-top:3px;}
}
</style>
</head>
<body>
<div id="loginbox__"><div id="loginbox_">
<div id="loginbox">
	<form method="POST" style="padding:20px;margin:0;padding-top:10px;" onsubmit="return checkform();">
	<img src="imgs/logo.png" style="margin:10px 0;width:100%;">
	<?if ($error_message!=''){?>
	<div style="color:#ab0200;font-weight:bold;padding-top:10px;"><?echo $error_message;?></div>
	<?}?>
	
	<div style="padding-top:10px;">Username: <?if ($passreset){?><b><?echo stripslashes($_POST['login']);?></b> &nbsp; <a href="<?echo $_SERVER['PHP_SELF'];?>"><em>switch user</em></a><?}?></div>
	<div style="padding-top:5px;padding-bottom:10px;">
	<input style="width:100%;<?if ($passreset) echo 'display:none;';?>" id="login" type="text" name="login" autocomplete="off" <?if ($passreset) echo 'readonly';?> value="<?if ($passreset) echo stripslashes($_POST['login']);?>"></div>

	<div id="passview">
		<div>Password:</div>
		<div style="padding-top:5px;padding-bottom:15px;">
		<input style="width:100%;" id="password" type="password" name="password"></div>
	

	<?if ($passreset){?>
	<div>New Password:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input style="width:100%;" id="password" type="password" name="newpassword"></div>
		
	<div>Confirm New Password:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input style="width:100%;" id="password" type="password" name="newpassword2"></div>
	<input type="hidden" name="passreset" value="1">
	<?}?>

	<div id="cardinfo"></div>
	
		<div  style="text-align:center;"><input id="loginbutton" type="submit" value="<?echo $passreset?'Update Password':'Sign In';?>"></div>
		<div id="cardlink">
			<a href=# onclick="cardauth();return false;">Load ID Card</a>
		</div>
	</div><!-- passview -->
	<div id="cardview" style="display:none;">
		<div style="text-align:center;"><input id="loginbutton" type="submit" value="Sign In" onclick="if (!cardauth()) return false;"></div>
		<div id="passlink">
			<a href=# onclick="passview();return false;">Sign in with password</a>
		</div>
	</div>
	<input name="cfk" value="<?echo $csrfkey;?>" type="hidden">
	<div style="display:none;"><textarea name="certid" id="certid"></textarea></div>
	</form>
	&nbsp;
</div>
</div></div>	

	<div class="powered">Powered by Antradar Gyroscope&trade; <?echo GYROSCOPE_VERSION?><?if (VENDOR_VERSION!='') echo '.'.VENDOR_VERSION;?><?if (VENDOR_NAME) echo ' '.VENDOR_NAME.' Edition';?></div>
	
	<script src="nano.js"></script>
	<script>
		function checkform(){
			if (gid('password').value=='') { //&&gid('certid').value==''
				gid('password').focus();
				return false;
			}
			return true;
		}
		<?if ($passreset){?>
		gid('password').focus();
		<?}else{?>	
		gid('login').focus();
		<?}?>
	</script>

<script src="smartcard.js"></script>
<script>
smartcard_init('reader',{
	'noplugin':function(){gid('cardlink').style.display='none';},
	'nohttps':function(){gid('cardlink').style.display='none';}
});

function cardview(){
	gid('passview').style.display='none';
	gid('cardview').style.display='block';
}

function passview(){
	gid('cardview').style.display='none';
	gid('passview').style.display='block';
}

function cardauth(){
/*
	if (gid('login').value=='') {
		gid('login').focus();
		return;
	}
*/
	if (document.reader){
	  cert=document.reader.getcert();
	  if (cert){
		gid('certid').value=cert.certificateAsHex;
		gid('cardinfo').innerHTML=cert.CN;
		gid('cardinfo').style.display='block';
		return true;
	  }//cert
	} else {//no reader
		alert('Smartcard reader not supported');
		return false;
	}
}

</script>

</body>
</html>
