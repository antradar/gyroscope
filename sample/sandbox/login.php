<?
include 'lb.php';
//include 'https.php'; //enforcing HTTPS on production server
include 'connect.php';
include 'auth.php';
include 'xss.php';

setcookie('userid',NULL,time()-3600);
setcookie('login',NULL,time()-3600);
setcookie('auth',NULL,time()-3600);
setcookie('groupnames',NULL,time()-3600);

$csrfkey=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g'));
$csrfkey2=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g',time()-3600));

$error_message='';

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
  
  $query="select * from users where login='$login' and password='$password'";
  $rs=sql_query($query,$db);  
  if ($myrow=sql_fetch_array($rs)){
	  
	  $userid=$myrow['userid'];
	  
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
  } else $error_message='invalid username or password';
}
?>
<html>
<head>
	<title>Login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="refresh" content="1800" />
	<meta name = "viewport" content = "width = 350, init-scale=1, user-scalable = yes" />
<style>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;}
#loginbox__{width:320px;margin:0 auto;background-color:rgba(200,200,200,0.4);margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:left;}
</style>
</head>
<body>
<div id="loginbox__"><div id="loginbox_">
<div id="loginbox">
	<form method="POST" style="padding:20px;margin:0;padding-top:10px;">
	<img src="imgs/logo.png" style="margin:10px 0;">
	<?if ($error_message!=''){?>
	<div style="color:#ab0200;font-weight:bold;padding-top:10px;"><?echo $error_message;?></div>
	<?}?>
	
	<div style="padding-top:10px;">Username:</div>
	<div style="padding-top:5px;padding-bottom:10px;">
	<input style="width:100%" id="login" type="text" name="login" autocomplete="off"></div>
	<div>Password:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input style="width:100%;" type="password" name="password"></div>
	<div style="text-align:center;"><input style="width:100px;" type="submit" value="Sign In"></div>
	<input name="cfk" value="<?echo $csrfkey;?>" type="hidden">
	</form>
</div>
</div></div>	

	<div style="color:#000000;text-align:right;font-size:12px;width:300px;margin:0 auto;padding-top:10px;">Powered by Antradar Gyroscope&trade; <?echo GYROSCOPE_VERSION?></div>
	
	<script src="nano.js"></script>
	<script>
		gid('login').focus();
	</script>
</body>
</html>
