<?
include 'connect.php';
include 'auth.php';

setcookie('userid',NULL,time()-3600);
setcookie('login',NULL,time()-3600);
setcookie('auth',NULL,time()-3600);
setcookie('groupnames',NULL,time()-3600);

$error_message='';

if ($_POST['password']||$_POST['login']){
  $password=md5($dbsalt.$_POST['password']);
  $raw_login=$_POST['login'];
  $login=mysql_real_escape_string($raw_login);
  
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
	  
	  if (isset($_GET['from'])) header('location: '.$_GET['from']);
	  else header('location:index.php');
	  
	  die();
  } else $error_message='invalid username or password';
}
?>
<html>
<head>
	<title>Login</title>
	<script src="nano.js"></script>
</head>
<body>
	<?echo $error_message;?>
	<form method="POST">
	<table>
		<tr>
			<td>Username:</td>
			<td><input id="login" type="text" name="login"></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Continue"></td>
		</tr>
	</table>
	</form>
	
	<script>
		gid('login').focus();
	</script>
</body>
</html>
