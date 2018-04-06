<?php

include 'lb.php';
include 'lang.php';
include 'forminput.php';

if (isset($usehttps)&&$usehttps) include 'https.php'; 
include 'connect.php';
include 'auth.php';
include 'xss.php';

$csrfkey=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g'));
$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].date('Y-m-j-H',time()-3600);
$csrfkey2=sha1($salt2.'csrf'.$_SERVER['REMOTE_ADDR'].date('Y-m-j-g',time()-3600));

$error_message='';

$passreset=0;

if (isset($_POST['lang'])&&in_array($_POST['lang'],array_keys($langs))) {
	$lang=$_POST['lang'];include 'lang/dict.'.$lang.'.php';  
	setcookie('userlang',$_POST['lang'],time()+3600*24*30*6); //6 months
}

$dkey=md5(GYROSCOPE_PROJECT);

if ( (isset($_POST['password'])&&$_POST['password']) || (isset($_POST['gyroscope_login_'.$dkey])&&$_POST['gyroscope_login_'.$dkey]) ){	
	
	xsscheck();

	$cfk=$_POST['cfk'];
	if ($cfk!=$csrfkey&&$cfk!=$csrfkey2){
		$error_message=_tr('csrf_expire');
	} else {
	
		$password=md5($dbsalt.$_POST['password']);
		$raw_login=$_POST['gyroscope_login_'.$dkey];
		$login=str_replace("'",'',$raw_login);
		
		$query="select * from ".TABLENAME_USERS." left join gss on ".TABLENAME_USERS.".gsid=gss.gsid where login='$login' and active=1 and virtualuser=0";
		$rs=sql_query($query,$db);  
		
		$passok=0;
		
		if ($myrow=sql_fetch_array($rs)){
			$enc=$myrow['password'];
			$dec=decstr($enc,$_POST['password'].$dbsalt);
			if ($password==$dec) $passok=1;
		}
		
		if ($passok){
			
			$userid=$myrow['userid'];
			$gsid=$myrow['gsid'];
			$gsexpiry=$myrow['gsexpiry']+0;
			$gstier=$myrow['gstier']+0;
			$passreset=$myrow['passreset'];
			
			$needcert=$myrow['needcert'];
			$certid=$_POST['certid'];
			$certhash=md5($dbsalt.$certid);
			$certhash_=$myrow['certhash'];
			
			$needkeyfile=$myrow['needkeyfile'];
			$keyfileokay=1;
			$smscode=$myrow['smscode'];
			
			if ($needkeyfile){
				$keyfilename=$_FILES['keyfile']['tmp_name'];
				if ($keyfilename==''){
					$keyfileerror='A key file is required to sign in';
					$keyfileokay=0;
				} else {
					$keyfile=file_get_contents($keyfilename);
					$keyfilehash=sha1($dbsalt.$keyfile);
					$keyfilehash_=$myrow['keyfilehash'];
					if ($keyfilehash!=$keyfilehash_){
						$keyfileerror='Invalid Key File';
						$keyfileokay=0;
					}
				}
					
			}//needkeyfile
			
			$smserror='';
			$usesms=$myrow['usesms'];
			$smsokay=1;
			if ($smskey=='') $usesms=0;
			
			if ($usesms){
				if ($_POST['smscode']==''||md5($salt.$_POST['smscode'])!=$smscode){
					$smsokay=0;
					$smserror='Invalid SMS code';
				}
			}
			
			$dispname=$myrow['dispname'];
			
			$certokay=1;
			
			$certerror='';
			
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
			
			if (isset($_POST['passreset'])&&$_POST['passreset']){
				$op=md5($dbsalt.$_POST['password']);
				$np=$_POST['newpassword'];
				$np2=$_POST['newpassword2'];
				if ($np!=$np2||$op==$np||trim($np)==''){
					if ($np==$op) $error_message=_tr('new_password_must_be_different');
					if ($np!=$np2) $error_message=_tr('mismatching_password');
					if (trim($np)=='') $error_message=_tr('must_provide_new_password');
				} else {
					$newpass=encstr(md5($dbsalt.$np),$np.$dbsalt);
					$query="update ".TABLENAME_USERS." set password='$newpass', passreset=0 where userid=$userid";
					sql_query($query,$db);
					$passreset=0;	
				}	  
			}
			  	  	  
			if ($passreset){
			
			} else {
				if ($keyfileokay&&$certokay&&$smsokay){	  
					$groupnames=$myrow['groupnames'];
					$auth=md5($salt.$userid.$groupnames.$salt.$raw_login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
					
					setcookie('auth',$auth,null,null,null,null,true);
					setcookie('gsid',$gsid,null,null,null,null,true);
					setcookie('gsexpiry',$gsexpiry,null,null,null,null,true);
					setcookie('gstier',$gstier,null,null,null,null,true);
					setcookie('userid',$userid,null,null,null,null,true);
					setcookie('login',$login,null,null,null,null,true);
					setcookie('dispname',$dispname,null,null,null,null,true);
					setcookie('groupnames',$groupnames,null,null,null,null,true);
					
					if (isset($_POST['lang'])){
						if (!in_array($_POST['lang'],array_keys($langs))) $_POST['lang']=$deflang;
						setcookie('userlang',$_POST['lang'],time()+3600*24*30*6); //keep for 6 months
					}
					
					//reset SMS code
					if ($usesms){
						$query="update ".TABLENAME_USERS." set smscode='' where userid=$userid";
						sql_query($query,$db);	
					}
					
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
					$error_message=trim(implode('<br>',array($smserror,$keyfileerror,$certerror)),'<br>');
				}//keyfileokay, certokay, smsokay
				
				
			}//passreset
		} else $error_message=_tr('invalid_password'); //passcheck
	
	}//csrf
	
} else {
	setcookie('userid',NULL,time()-3600);
	setcookie('gsid',NULL,time()-3600);
	setcookie('gsexpiry',NULL,time()-3600);	
	setcookie('gstier',NULL,time()-3600);	
	setcookie('login',NULL,time()-3600);
	setcookie('dispname',NULL,time()-3600);
	setcookie('auth',NULL,time()-3600);
	setcookie('groupnames',NULL,time()-3600);	
}

?>
<html>
<head>
	<title><?echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="refresh" content="1800" />
	<meta name = "viewport" content = "width=device-width, user-scalable=no" />
	<meta name="theme-color" content="#9FA3A7" />	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<?include 'appicon.php';?>
	
<style>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;}
#loginbox__{width:320px;margin:0 auto;background-color:rgba(200,200,200,0.4);margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:left;}
.powered{color:#000000;text-align:right;font-size:12px;width:320px;margin:0 auto;padding-top:10px;}
#loginbutton{width:140px;-webkit-appearance: none;}
#cardlink, #passlink{display:none;text-align:center;padding-top:10px;}
#cardlink{display:none;}
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

<?if ($_GET['kpw']||preg_match('/kindle/i',$_SERVER['HTTP_USER_AGENT'])){?>
body{font-size:28px;}
#loginbox__{width:640px;background-color:#dedede;margin-top:150px;border-radius:8px;}
#loginbox_{padding:20px;}
.powered{font-size:24px;width:640px;padding-top:20px;}
#login, #password{height:45px;font-size:32px;line-height:32px;}
#loginbutton{height:auto;padding:6px 0;font-size:28px;width:280px;-webkit-appearance: none;}
<?}?>


</style>
</head>
<body>
<div id="loginbox__"><div id="loginbox_">
<div id="loginbox">
	<form method="POST" style="padding:20px;margin:0;padding-top:10px;" onsubmit="return checkform();" enctype="multipart/form-data">
	<img src="imgs/logo.png" style="margin:10px 0;width:100%;">
	<?if ($error_message!=''){?>
	<div id="loginerror" style="color:#ab0200;font-weight:bold;padding-top:10px;"><?echo $error_message;?></div>
	<?}?>
	
	<div style="padding-top:10px;"><?tr('username');?>: <?if ($passreset){?><b><?echo stripslashes($_POST['gyroscope_login_'.$dkey]);?></b> &nbsp; <a href="<?echo $_SERVER['PHP_SELF'];?>"><em><?tr('switch_user');?></em></a><?}?></div>
	<div style="padding-top:5px;padding-bottom:10px;">
	<input style="width:100%;<?if ($passreset) echo 'display:none;';?>" id="login" type="text" name="gyroscope_login_<?echo $dkey;?>" autocomplete="off" <?if ($passreset) echo 'readonly';?> value="<?if ($passreset) echo stripslashes($_POST['gyroscope_login_'.$dkey]);?>"></div>

	<div id="passview">
		<div><?tr('password');?>:</div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" id="password" type="password" name="password">
		</div>
		
		<div id="tfa_sms" style="display:none;">
			<div>SMS Code: (check your phone)</div>
			<div style="padding-top:5px;padding-bottom:15px;">
				<input id="smscode" name="smscode" style="width:100%;" autocomplete="off">
			</div>
		</div>
		
		<div id="tfa_keyfile" style="display:none;">
			<div style="<?if ($passreset) echo 'display:none;';?>">Key File:</div>
			<div style="padding-top:5px;padding-bottom:15px;<?if ($passreset) echo 'display:none;';?>">
				<input id="keyfile" type="file" name="keyfile">
				<input type="hidden" name="MAX_FILE_SIZE" value="4096">
			</div>
		</div>
	
	<?if ($passreset){?>
	<div><?tr('new_password');?>:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input style="width:100%;" id="password" type="password" name="newpassword"></div>
		
	<div><?tr('repeat_password');?>:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input style="width:100%;" id="password" type="password" name="newpassword2"></div>
	<input type="hidden" name="passreset" value="1">
	<?}?>

	<div style="width:100%;margin-bottom:10px;<?if (count($langs)<2) echo 'display:none;';?>"><select style="width:100%;" name="lang" onchange="document.skipcheck=true;">
	<?
	foreach ($langs as $langkey=>$label){
	?>
	<option value="<?echo $langkey;?>" <?if ($lang==$langkey) echo 'selected';?>><?echo $label;?></option>
	<?	
	}//foreach
	?>
	</select>
	</div>
	
	<div id="cardinfo"></div>
	
		<div  style="text-align:center;"><input id="loginbutton" type="submit" value="<?echo $passreset?_tr('change_password'):_tr('signin');?>"></div>
		<div id="cardlink">
			<a href=# onclick="cardauth();return false;">Load ID Card</a>
		</div>
	</div><!-- passview -->
	<div id="cardview" style="display:none;">
		<div style="text-align:center;"><input id="loginbutton" type="submit" value="<?tr('signin');?>" onclick="if (!cardauth()) return false;"></div>
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
	<?
	$version=GYROSCOPE_VERSION;
	if (VENDOR_VERSION!='') $version.=VENDOR_VERSION;
	if (VENDOR_NAME) $version.=' '.VENDOR_NAME;
	$power='Antradar Gyroscope&trade; '.$version;
	?>
	<div class="powered"><?tr('powered_by_',array('power'=>$power));?></div>
	
	<script src="nano.js"></script>
	<script>
		function checkform(){
			if (gid('loginerror')) gid('loginerror').innerHTML='';
			if (document.skipcheck) return true;
			if (gid('password').value=='') { //&&gid('certid').value==''
				gid('password').focus();
				if (gid('login').value=='') gid('login').focus();
				return false;
			}
			
			var tfa=false;
			
			if (!document.tfabypass){
			
				var res=ajxb('ajx_2facheck.php?','login='+encodeHTML(gid('login').value)+'&password='+encodeHTML(gid('password').value),function(rq){
					document.tfabypass=true;
					var tfas=rq.getResponseHeader('tfas');
					if (tfas!=null&&tfas!=''){
						tfa=true;
						var tfaparts=tfas.split(',');
						for (var i=0;i<tfaparts.length;i++){
							var part=tfaparts[i];
							if (gid('tfa_'+part)) {
								gid('tfa_'+part).style.display='block';
								var focalpoint=rq.getResponseHeader('focalpoint');
								if (focalpoint!=null&&focalpoint!=''&&gid(focalpoint)) gid(focalpoint).focus();
							}
						}	
					}
					
				});
				
			}
			
			if (tfa) return false;
			
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
	'nohttps':function(){gid('cardlink').style.display='none';},
	'inited':function(){gid('cardlink').style.display='block';}	
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
	  document.reader.getcert(function(cert){
	  if (cert){
		gid('certid').value=cert.certificateAsHex;
		gid('cardinfo').innerHTML=cert.CN;
		gid('cardinfo').style.display='block';
		return true;
	  }
	  });
	} else {//no reader
		alert('Smartcard reader not supported');
		return false;
	}
}

</script>

</body>
</html>
