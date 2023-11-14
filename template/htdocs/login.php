<?php

include 'lb.php';
include 'lang.php';
include 'forminput.php';
include 'gsratecheck.php';

include 'encdec.php';
include 'bcrypt.php';


if (isset($usehttps)&&$usehttps) include 'https.php'; 
include 'connect.php';
include 'auth.php';
if (!isset($fedbypass)) include 'xss.php';
include 'passtest.php';

include 'icl/calcgapins.inc.php';
include 'libcbor.php';

$csrfkey=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-g'));
$salt2=$saltroot.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-H',time()-3600);
$csrfkey2=sha1($salt2.'csrf'.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-g',time()-3600));

$error_message='';

$passreset=0;

header('gsfunc: login');

$textmode=0;
$ua=$_SERVER['HTTP_USER_AGENT'];
if (preg_match('/^lynx\//i',$ua)) $textmode=1;

if (isset($_GET['watch'])&&$_GET['watch']==1||preg_match('/sm\-r\d+/i',$_SERVER['HTTP_USER_AGENT'])){
	$roundwatchframe=1;
}

if (isset($_POST['lang'])&&in_array($_POST['lang'],array_keys($langs))) {
	$lang=$_POST['lang'];include 'lang/dict.'.$lang.'.php';  
	setcookie('userlang',$_POST['lang'],time()+3600*24*30*6,null,null,$usehttps,true); //6 months
}

$dark=isset($_COOKIE['userdarkmode'])?intval($_COOKIE['userdarkmode']):0;

$dkey=md5(GYROSCOPE_PROJECT);

$deflogin=isset($_COOKIE['fingername'])?$_COOKIE['fingername']:'';

if ( (isset($_POST['password'])&&$_POST['password']) || (isset($_POST['gyroscope_login_'.$dkey])&&$_POST['gyroscope_login_'.$dkey]) ){	
	
	if (!isset($fedbypass)) xsscheck();

	$cfk=$_POST['cfk'];
	if (!isset($fedbypass)&&$cfk!=$csrfkey&&$cfk!=$csrfkey2){
		$error_message=_tr('csrf_expire');
	} else {
	
		$password=md5($dbsalt.$_POST['password']);
		$login=SQET('gyroscope_login_'.$dkey);
		
		
		list($rateok,$penalty)=gsratecheck_verify($_SERVER['REMOTE_ADDR'],$login);
		
		if ($rateok){
			$query="select * from ".TABLENAME_USERS." left join ".TABLENAME_GSS." on ".TABLENAME_USERS.".".COLNAME_GSID."=".TABLENAME_GSS.".".COLNAME_GSID." where lower(login)=lower(?) and active=1 and virtualuser=0";
			$rs=sql_prep($query,$db,array($login)); 
		} else {
			$error_message='Too many login attempts.<br>Try again in '.duration_format($penalty);	
		}
		
		
		$passok=0;
		
		$nopass=isset($_POST['loginnopass'])?intval($_POST['loginnopass']):0;
		
			
		if ($rateok&&$myrow=sql_fetch_array($rs)){
			
			$useyubi=intval($myrow['useyubi']);
			$yubimode=intval($myrow['yubimode']); //0-both password and key required, 1-key required, pass optional, 2-pass required, key optional
			$passreset=$myrow['passreset'];
			
			if ($passreset) $useyubi=0;
			/*
			//legacy code during transition
			$enc=$myrow['password'];
			$dec=decstr($enc,$_POST['password'].$dbsalt);
			if ($password==$dec) $passok=1;
			*/
			if ($useyubi&&($nopass||$yubimode==0||$yubimode==2)){
				$passok=0;
				$yubiok=0;
				$userid=$myrow['userid'];
				$query="select count(*) as c from ".TABLENAME_YUBIKEYS." where userid=?";
				$rs2=sql_prep($query,$db,$userid);
				$myrow2=sql_fetch_assoc($rs2);
				$c=$myrow2['c'];
				if ($c==0) $error_message='no security devices were found ';
				else {
					$attidbin=hex2bin($_POST['attid']);
					$attid=base64_encode($attidbin);
					$clientdata=$_POST['clientdata'];
					$signature=base64_encode(hex2bin($_POST['signature']));
					$clientauth=base64_encode(hex2bin($_POST['clientauth']));
					
					$query="select * from ".TABLENAME_YUBIKEYS." where userid=? and attid=?";
					$rs2=sql_prep($query,$db,array($userid,$attid));
					if (!$myrow2=sql_fetch_assoc($rs2)){
						$error_message="Cannot find a key in the registry.";	
					} else {
						$keyid=$myrow2['keyid'];
						if ($myrow2['passless']==1) $yubimode=1;
						$kty=$myrow2['kty'];
						$alg=$myrow2['alg'];
						$crv=$myrow2['crv']; $x=$myrow2['x']; $y=$myrow2['y'];
						$n=$myrow2['n']; $e=$myrow2['e'];
						$lastsigncount=$myrow2['lastsigncount'];
						$newsigncount=0;
						$res=cbor_validate($kty,$alg,$crv,$x,$y,$n,$e,$clientdata,$clientauth,$signature,1,$lastsigncount,$newsigncount,$err);
						$yubiok=$res;
						if ($res==1){
							$passok=1;
							setcookie('fingername',$login,time()+3600*24*30*6,null,null,$usehttps,true);
							$query="update ".TABLENAME_YUBIKEYS." set lastsigncount=? where keyid=?";
							sql_prep($query,$db,array($newsigncount,$keyid));
						}
							
					}
					
					
				}
				
				if ($yubimode==0||$yubimode==2){
					$passok=password_verify($dbsalt.$_POST['password'],$myrow['password']);
					if (!$passok) {
						$error_message="Invalid password";
						list($remlogin,$fpenalty)=gsratecheck_registerfail($_SERVER['REMOTE_ADDR'],$login);
						if ($remlogin<1) $error_message.="<br>Try again in another ".duration_format($fpenalty);
												
					} else {
						if (!$yubiok&&$yubimode!=2){
							$passok=0;
							$error_message="A security device is required for this account.";	
						}	
					}
				}
				
				if (!$nopass){
					setcookie('fingername',NULL,time()-3600,null,null,$usehttps,true);
				}
				
			} else {
				$passok=password_verify($dbsalt.$_POST['password'],$myrow['password']); //bcrypt uses its internal salt, $dbsalt here is just padding
				setcookie('fingername',NULL,time()-3600,null,null,$usehttps,true);
				if (!$passok) $passreset=0;
			}
		} else {
			password_hash($dbsalt.time(),PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));	
		}
		
		
		
		if ($passok){
			
			$userid=$myrow['userid'];
			$gsid=$myrow[COLNAME_GSID];
			$gsexpiry=intval($myrow['gsexpiry']);
			$gstier=intval($myrow['gstier']);
			$passreset=$myrow['passreset'];
			
			$needcert=$myrow['needcert'];
			$certid=strtoupper(SQET('certid'));
			$certhash=md5($dbsalt.$certid);
			$certhash_=$myrow['certhash'];
			
			$needkeyfile=$myrow['needkeyfile'];
			$keyfileokay=1;
			$smscode=$myrow['smscode'];
			
			$usega=$myrow['usega'];
			$gakey=$myrow['gakey'];
			
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
			if ($gakey=='') $usega=0;

			$enc_remote=0; //sync with showgaqr, testgapin

			if ($usega&&$gakey!='') $gakey=decstr($gakey,GYROSCOPE_PROJECT.'gakey-'.COLNAME_GSID.'-'.$gsid.'-'.$userid,$enc_remote); //remote key
						
			$gaokay=1;
			
			if ($usesms){
				if ($_POST['smscode']==''||md5($salt.$_POST['smscode'])!=$smscode){
					$smsokay=0;
					$smserror='Invalid SMS code';
				}
			}
			
			if ($usega){
				$gapin=str_replace(array(' ','-','.'),'',$_POST['gapin']);
				if ($gapin=='') $gaokay=0;
				else {
					$gapins=calcgapins($gakey);
					if (!in_array($gapin,$gapins)) $gaokay=0;
				}
				$gaerror='Invalid Authenticator PIN';	
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
					//$newpass=encstr(md5($dbsalt.$np),$np.$dbsalt);
					$passcheck=passtest($np);
					if ($passcheck['grade']==0){
						$error_message='A weak password cannot be used.';
					} else {
						$newpass=password_hash($dbsalt.$np,PASSWORD_DEFAULT);
						//all 2fa's will be cleared here
						$query="update ".TABLENAME_USERS." set password=?, passreset=0, usega=0, usesms=0, needcert=0, needkeyfile=0, useyubi=0 where userid=?";
						sql_prep($query,$db,array($newpass,$userid));
						$passreset=0;
					}	
				}	  
			}
			  	  	  
			if ($passreset){
			
			} else {
				if ($keyfileokay&&$certokay&&$smsokay&&$gaokay){	 
					
					//get a random number
					$randmode=0;
					if (is_callable('random_bytes')) $randmode=1;
					if (is_callable('openssl_random_pseudo_bytes')&&$randmode==0) $randmode=2;
					switch ($randmode){
						case 0: $rand=mt_rand(); break;
						case 1: $rand=random_bytes(32); break;
						case 2: $rand=openssl_random_pseudo_bytes(32); break;
					}
					
					$rand=substr(base64_encode($rand),0,16);
										
					$groupnames=$myrow['groupnames'];
					$auth=md5($salt.$userid.$groupnames.$salt.$login.$salt.$dispname.$salt.$gsid.$salt.$gsexpiry.$salt.$gstier);
					
					$dowoffset=$myrow['dowoffset']??0;
					
					setcookie('auth',$auth,null,null,null,$usehttps,true);
					setcookie('gsid',$gsid,null,null,null,$usehttps,true);
					setcookie('gsexpiry',$gsexpiry,null,null,null,$usehttps,true);
					setcookie('gstier',$gstier,null,null,null,$usehttps,true);
					setcookie('userid',$userid,null,null,null,$usehttps,true);
					setcookie('login',$login,null,null,null,$usehttps,true);
					setrawcookie('dispname',rawurlencode($dispname),null,null,null,$usehttps,true);
					setcookie('groupnames',$groupnames,null,null,null,$usehttps,true);
					setcookie('gsfrac',$rand,null,null,null,$usehttps,true);
					setcookie('dowoffset',$dowoffset,time()+3600*24*30*6,null,null,$usehttps,true); //6 months
					
					if (isset($_POST['lang'])){
						if (!in_array($_POST['lang'],array_keys($langs))) $_POST['lang']=$deflang;
						setcookie('userlang',$_POST['lang'],time()+3600*24*30*6,null,null,$usehttps,true); //keep for 6 months
					}
					
					gsratecheck_reset($_SERVER['REMOTE_ADDR'],$login);
					
					//reset SMS code
					if ($usesms){
						$query="update ".TABLENAME_USERS." set smscode='' where userid=?";
						sql_prep($query,$db,$userid);	
					}
					
					//rehash password
					if (password_needs_rehash($myrow['password'],PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST))){
						$np=password_hash($dbsalt.$_POST['password'],PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));
						$query="update ".TABLENAME_USERS." set password=? where userid=?";
						sql_prep($query,$db,array($np,$userid));
					}
					
					
					if (isset($_GET['from'])&&trim($_GET['from'])!='') {
					  $from=$_GET['from'];
					  $from=str_replace('//','',$from);
					  $from=str_ireplace(array('%2f%2f','%5c','\\'),'',$from);
					  $from=str_replace(array("\r","\n",':'),'-',$from);
					  if ($from===$_GET['from']) header('Location: '.$from);
					  else header('Location: index.php');
					} else header('Location:index.php');
					die();
				} else {
					$error_message=trim(implode('<br>',array($gaerror,$smserror,$keyfileerror,$certerror)),'<br>');
				}//keyfileokay, certokay, smsokay
				
				
			}//passreset
		} else {
			if (!$nopass&&$error_message=='') {
				$error_message=_tr('invalid_password'); //passcheck
				list($remlogin,$fpenalty)=gsratecheck_registerfail($_SERVER['REMOTE_ADDR'],$login);
				if ($remlogin<1) $error_message.="<br>Try again in another ".duration_format($fpenalty);
						
			}
		}
	
	}//csrf
	
} else {
	if (!isset($fedbypass)) xsscheck(1);
	setcookie('userid',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('gsid',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('gsexpiry',NULL,time()-3600,null,null,$usehttps,true);	
	setcookie('gstier',NULL,time()-3600,null,null,$usehttps,true);	
	setcookie('login',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('dispname',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('auth',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('groupnames',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('gsfrac',NULL,time()-3600,null,null,$usehttps,true);
	setcookie('chatid',NULL,time()-3600,'/',null,$usehttps);	
	setcookie('chatauth',NULL,time()-3600,'/',null,$usehttps);	
}

?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "viewport" content = "width=device-width, user-scalable=no" />
	<meta name="theme-color" content="#9FA3A7" />	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<?php include 'appicon.php';?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">
<style>
<?php
$framecolor='rgba(200,200,200,0.4)';
if (isset($SQL_READONLY)&&$SQL_READONLY) $framecolor='rgba(255,200,100,0.4)';
?>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;<?php if ($dict_dir=='rtl') echo 'direction:rtl;';?>}
#logo_light,#logo_dark{margin:10px 0;width:100%;}
#logo_light{display:block;}
#logo_dark{display:none;}

#yubikeysetup a, #yubikeysetup a:hover, #yubikeysetup a:link, #yubikeysetup a:visited{text-decoration:none;color:#187CA6;}
#yubikeysetup a:hover{text-decoration:underline;}

#loginbox__{width:320px;margin:0 auto;background-color:<?php echo $framecolor;?>;margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:<?php if ($dict_dir=='rtl') echo 'right'; else echo 'left';?>;}
.powered{color:#000000;text-align:right;font-size:12px;width:320px;margin:0 auto;padding-top:10px;}
#loginbutton,.loginbutton{color:#ffffff;background:#187CA6;padding:8px 20px;border-radius:3px;border:none;cursor:pointer;box-shadow:0px 1px 2px #c9c9c9;-webkit-appearance:none;text-decoration:none;}
#loginbutton:focus, #loginbutton:hover{background:#29ABE1;}
#loginbutton:active, #loginbuttonbutton:active{box-shadow:1px 1px 3px #999999;}

#fingerprint{cursor:pointer;vertical-align:middle;}
#fingerprint img{width:22px;border-radius:3px;}

#cardlink, #passlink{display:none;text-align:center;padding-top:10px;}
#cardlink{display:none;}
#cardinfo{padding:5px;font-size:12px;padding-left:26px;background:#fcfcdd url(imgs/smartcard.png) no-repeat 5px 50%;margin-bottom:10px;display:none;}

.lfinp,.lfsel{border:solid 1px #999999;display:block;margin-bottom:5px;border-radius:3px;}
.lfinp:active, .lfinp:focus, .lfsel:active, .lfsel:focus{outline:0;border:solid 2px #29ABE1;}
.lfinp{font-size:18px;-webkit-appearance:none;}
.lfsel{font-size:15px;}

#lang{padding:5px 0;}

@media screen and (min-width:20px){
	.lfinp{padding:5px;box-sizing:border-box;height:34px;line-height:32px;font-size:15px;}
}

@media screen and (max-width:400px){
	#loginbox__,.powered{width:90%;}
	#loginbox__{margin-top:50px;}
}

@media screen and (max-width:300px){
	#loginbutton{width:auto;padding-left:15px;padding-right:15px;}
}

@media screen and (max-width:260px){
	.powered{text-align:center;}
	.powered span{display:block;padding-top:3px;}
}

<?php if (SGET('kpw')||preg_match('/kindle/i',$_SERVER['HTTP_USER_AGENT'])){?>
body{font-size:28px;}
#loginbox__{width:640px;background-color:#dedede;margin-top:150px;border-radius:8px;}
#loginbox_{padding:20px;}
.powered{font-size:24px;width:640px;padding-top:20px;}
#login, #password{height:45px;font-size:32px;line-height:32px;}
#loginbutton{height:auto;padding:6px 0;font-size:28px;width:280px;-webkit-appearance: none;}
<?php }?>

<?php
if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php
}

if ($dark==0||$dark==1){
?>
	body{background-image:url(imgs/dbgtile.png);}
	#loginbox{background: #21262D;color:#C9D1D9;}
	input,#lang{background:#0D1117;color:#C2C3C5;}
	#loginbutton{box-shadow:none;border:solid 1px #388BFD;}
	#loginbutton:hover{background:#125B7A;}
	#logo_light{display:none;}
	#logo_dark{display:block;}
	.powered{color:#8B949E;}
	#fingerprint{filter:invert(1);}
	#yubikeysetup a, #yubikeysetup a:hover, #yubikeysetup a:link, #yubikeysetup a:visited{text-decoration:none;color:#29ABE1;}	
<?php	

}//if dark==0||dark==1
	
if ($dark==0){
?>
}
<?php	
}
?>

</style>
<?php if (isset($roundwatchframe)&&$roundwatchframe){?>
<style>
	body{background-image:url(imgs/dbgtile.png);font-size:22px;}
	#loginbox__{margin-top:100px;border-radius:40px;}
	#loginbox{background:#21262D;color:#C9D1D9;border-radius:40px;overflow:hidden;}
	input,#lang{background:#0D1117;color:#C2C3C5;}
	#loginbutton{box-shadow:none;border:solid 1px #388BFD;font-size:22px;}
	#loginbutton:hover{background:#125B7A;}
	.lfinp{margin-bottom:20px;}
	#logo_light{display:none;margin-bottom:20px;}
	#logo_dark{display:block;margin-bottom:20px;}
	.powered{text-align:center;color:#8B949E;padding-bottom:160px;font-size:17px;}
	#fingerprint{filter:invert(1);}
</style>
<?php }?>
</head>
<body>
<div id="loginbox__"><div id="loginbox_">
<div id="loginbox">
	<form id="loginform" method="POST" style="padding:20px;margin:0;padding-top:10px;" onsubmit="return checkform();">
	<?php if (!$textmode){?>
		<img id="logo_light" src="imgs/logo.png" alt="Gyroscope Logo">
		<img id="logo_dark" src="imgs/dlogo.png">
	<?php } else { ?>
		<i><?php echo GYROSCOPE_PROJECT;?></i><br>
		<i><?php echo str_pad('-',strlen(GYROSCOPE_PROJECT),'-');?></i>
		<br>&nbsp;
		<br>
	<?php }?>
	
	<?php if ($error_message!=''){?>
	<div id="loginerror" style="line-height:1.4em;color:#ab0200;font-weight:bold;padding-top:10px;"><?php echo $error_message;?></div>
	<?php }?>
		
	<div style="padding-top:10px;"><label for="login"><?php tr('username');?>:</label> <?php if ($passreset){?><b><?php echo stripslashes($_POST['gyroscope_login_'.$dkey]);?></b> &nbsp; <a href="<?php echo $_SERVER['PHP_SELF'];?>"><em><?php tr('switch_user');?></em></a><?php }?></div>
	<div style="padding-top:5px;padding-bottom:10px;">
	<input style="width:100%;<?php if ($passreset) echo 'display:none;';?>" class="lfinp" id="login" type="text" name="gyroscope_login_<?php echo $dkey;?>" autocomplete="off" <?php if ($passreset) echo 'readonly';?> value="<?php if ($passreset) echo stripslashes($_POST['gyroscope_login_'.$dkey]); else echo htmlspecialchars($deflogin);?>"></div>
	
	<div id="passview">
		<div><label for="password"><?php tr('password');?>:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" class="lfinp" id="password" type="password" name="password">
		</div>
		<?php if (!$textmode){?>
		<div id="tfa_sms" style="display:none;">
			<div><label for="smscode">SMS Code: (check your phone)</label></div>
			<div style="padding-top:5px;padding-bottom:15px;">
				<input class="lfinp" id="smscode" name="smscode" style="width:100%;" autocomplete="off">
			</div>
		</div>
		<?php }?>
		<div id="tfa_ga" style="display:none;">
			<div><label for="gapin">Google Authenticator PIN:</label></div>
			<div style="padding-top:5px;padding-bottom:15px;">
				<input class="lfinp" id="gapin" name="gapin" style="width:100%;" autocomplete="off">
			</div>
		</div>		
		
		<div id="tfa_keyfile" style="display:none;">
		</div>
	
	<?php if ($passreset){?>
	<div><label for="password"><?php tr('new_password');?>:</label></div>
	<div style="padding-top:5px;padding-bottom:15px;">
		<input class="lfinp" style="width:100%;" id="password" type="password" name="newpassword" onkeyup="_checkpass(this);" onchange="checkpass(this);">
		<div style="font-weight:normal;color:#ab0200;" id="passwarn"></div>
	</div>
	
		
	<div><?php tr('repeat_password');?>:</div>
	<div style="padding-top:5px;padding-bottom:15px;">
	<input class="lfinp" style="width:100%;" id="password" type="password" name="newpassword2"></div>
	<input type="hidden" name="passreset" value="1">
	<?php }?>

	<?php if (!$textmode){?>
	<div style="width:100%;margin-bottom:10px;<?php if (count($langs)<2) echo 'display:none;';?>"><select id="lang" style="width:100%;" class="lfsel" name="lang" onchange="document.skipcheck=true;">
	<?php 
	foreach ($langs as $langkey=>$label){
	?>
	<option value="<?php echo $langkey;?>" <?php if ($lang==$langkey) echo 'selected';?>><?php echo $label;?></option>
	<?php	
	}//foreach
	?>
	</select>
	</div>
	<?php } ?>
	
	<div id="cardinfo"></div>
	
		<?php if (!$textmode){?>
	
		<div style="display:none;">
			<input id="loginnopass" name="loginnopass" type="checkbox" value="1">
			<input id="attid" name="attid" autocomplete="off">
			<input id="clientdata" name="clientdata" autocomplete="off">
			<input id="signature" name="signature" autocomplete="off">
			<input id="clientauth" name="clientauth" autocomplete="off">
			
		</div>
			
		<div  style="text-align:center;">
			<input id="loginbutton" type="submit" value="<?php echo $passreset?_tr('change_password'):_tr('signin');?>">
			<a style="display:none<?php if (!$passreset) echo 'a';?>;" id="fingerprint" onclick="yubilogin();return false;" href=#><img src="imgs/fingerprint.png" border="0"></a>
		</div>	
		<div id="yubikeysetup" style="display:none;padding-top:20px;text-align:center;">
			<a href="<?php echo YUBIHELP;?>" target=_blank>how to use security keys?</a> 
		</div>
		<div id="tfa_cert" style="display:none;">
		<div style="text-align:center;padding-top:20px;">Smart Card Needed</div>
		<div id="cardlink">
			<a href=# onclick="cardauth();return false;">Load ID Card</a>
		</div></div>
		
		<?php } ?>
		
	</div><!-- passview -->
	
	<?php if (!$textmode){?>
	<div id="cardview" style="display:none;">
		<div style="text-align:center;"><input id="loginbutton" type="submit" value="<?php tr('signin');?>" onclick="if (!cardauth()) return false;"></div>
		<div id="passlink">
			<a href=# onclick="passview();return false;">Sign in with password</a>
		</div>
	</div>
	<?php } else {?>
		<br>&nbsp;<br>
		<input id="loginbutton" type="submit" value="[<?php tr('signin');?>]">
	<?php } ?>
	<input name="cfk" id="cfk" value="<?php echo $csrfkey;?>" type="hidden">
	
	<?php if (!$textmode){?>
	<div style="display:none;"><span id="nullloader"></span><textarea name="certid" id="certid"></textarea></div>
	<?php } ?>
	
	</form>
	

	<?php if (!$textmode){?>
	<div id="offlineform" style="padding:20px;margin:0;padding-top:10px;display:none;line-height:1.4em;">
		<img src="imgs/logo.png" style="margin:10px 0;width:100%;" alt="Gyroscope Logo">
		There is currently no network access, but you can take offline notes:
		<div style="padding-top:30px;text-align:center;">
			<a class="loginbutton" href="notes.php">Launch Notepad</a>
		</div>
	</div>
	&nbsp;
	<?php } ?>
	
</div>
</div></div>

<?php if (!$textmode){?>
<div id="homeadder" onclick="this.style.top='-150px';" style="position:fixed;top:-150px;left:0;background:rgba(0,0,0,0.4);width:100%;padding:20px 0;transition:top 500ms;display:none;">
	<img src="appicons/60x60.png" width="28" style="vertical-align:middle;margin-right:20px;">
	<button id="homeapp" style="font-size:12px;padding:4px 10px;border:solid 1px #8f8cf7;border-radius:3px;">Add to Home Screen</button>
</div>
<?php
}
?>
	
	<?php
	$version=GYROSCOPE_VERSION;
	if (VENDOR_VERSION!='') $version.=VENDOR_VERSION;
	if (VENDOR_NAME) $version.=' '.VENDOR_NAME;
	$power='Antradar Gyroscope&trade; '.$version;
	?>
	<div class="powered"><?php tr('powered_by_',array('power'=>$power));?></div>
	
	<?php if (!$textmode){?>
	
	<script src="nano.js?v=4_9"></script>
	<script>
		function checkform(){
			if (gid('yubikeysetup')) gid('yubikeysetup').style.display='none';
						
			if (navigator.onLine!=null&&!navigator.onLine){
				onlinestatuschanged();
				return false;
			}
			if (gid('loginerror')) gid('loginerror').innerHTML='';
			if (document.skipcheck) return true;
			
			
			if (gid('password').value=='') { //&&gid('certid').value==''
				if (gid('password')) gid('password').focus();
				if (gid('login')&&gid('login').value=='') gid('login').focus();
				return false;
			}
			
						
			var tfa=false;
			
			if (!document.tfabypas){
			
				ajxb('ajx_2facheck.php?','login='+encodeHTML(gid('login').value)+'&pass'+'word='+encodeHTML(gid('password').value),function(rq){
					tfa=tfa_callback(rq);
				});
				
			}
			
			if (tfa) return false;
			
			return true;
		}
		<?php if ($passreset){?>
		if (gid('password')) gid('password').focus();
		<?php }else{?>	
		if (gid('login')&&gid('login').value=='') gid('login').focus();
		if (gid('login')&&gid('login').value!=''&&gid('password')) gid('password').focus();
		<?php }?>
	</script>

<script src="smartcard.js"></script>
<script>
window.onload=function(){
	smartcard_init('reader',{
		'noplugin':function(){if (gid('cardlink')) gid('cardlink').style.display='none';},
		'nohttps':function(){if (gid('cardlink')) gid('cardlink').style.display='none';},
		'inited':function(){if (gid('cardlink')) gid('cardlink').style.display='block';}	
	});
};

function cardview(){
	gid('passview').style.display='none';
	gid('cardview').style.display='block';
}

function passview(){
	gid('cardview').style.display='none';
	gid('passview').style.display='block';
}

function tfa_callback(rq){
	var tfa=false;
	document.tfabypas=true;
	var fedurl=rq.getResponseHeader('fedurl');
	if (fedurl!=null&&fedurl!=''){
		gid('loginform').action=fedurl;
		gid('login').name=rq.getResponseHeader('fedloginfield');
	}
	var tfas=rq.getResponseHeader('tfas');
	if (tfas!=null&&tfas!=''){
		tfa=true;
		var tfaparts=tfas.split(',');
		var popyubi=false;

		for (var i=0;i<tfaparts.length;i++){
			var part=tfaparts[i];
			if (part=='yubi'){
				popyubi=true;
				continue;	
			}
			if (gid('tfa_'+part)) {
				gid('tfa_'+part).style.display='block';
				if (part=='keyfile') {
					gid('tfa_keyfile').innerHTML=rq.responseText;
					gid('loginform').setAttribute('enctype','multipart/form-data');
				}
				var focalpoint=rq.getResponseHeader('focalpoint');
				if (focalpoint!=null&&focalpoint!=''&&gid(focalpoint)) gid(focalpoint).focus();
			}
			
		}
		if (popyubi&&tfaparts.length==1) yubilogin();	
	}
	return tfa;
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

document.cfkitr=setInterval(function(){
	ajxpgn('nullloader','logpump.php?',0,0,null,function(rq){ //use logpump.gsb when applicable
		if (rq.responseText!=''&&rq.responseText!=null) gid('cfk').value=rq.responseText;	
	});
},30000);

_checkpass=function(d){
	if (d.timer) clearTimeout(d.timer);
	if (d.value==''){
		d.style.background='#ffffff';
		gid('passwarn').innerHTML='';
		return;	
	}
	d.timer=setTimeout(function(){
		checkpass(d);
	},300);
}

checkpass=function(d){
	if (d.value==''){
		d.style.background='#ffffff';
		gid('passwarn').innerHTML='';
		return;				
	}
	
	ajxpgn('passwarn','ajx_checkpass.php?cmd=checkpass',0,0,'pass='+encodeHTML(d.value),function(rq){
		var color=rq.getResponseHeader('passcolor');
		d.style.background=color;	
	});
}

yubilogin=function(){
	if (gid('login').value==''){
		if (gid('yubikeysetup')) gid('yubikeysetup').style.display='block';
		gid('login').focus();
		return;
	}
	
	var login=encodeHTML(gid('login').value);

	var tfa=false;
	if (!document.tfabypas){	
		ajxb('ajx_2facheck.php?','login='+encodeHTML(gid('login').value)+'&nopass=1',function(rq){
			tfa=tfa_callback(rq);
		});	
	}
	
	if (tfa) return;
	
	ajxpgn('certid','ajx_getyubikeys.php?login='+login,0,0,null,function(rq){
		var rawattids=rq.responseText;
		if (rawattids=='') {
			if (gid('yubikeysetup')) gid('yubikeysetup').style.display='block';	
			return;
		}
		
		if (gid('yubikeysetup')) gid('yubikeysetup').style.display='none';
		
		var attids=rawattids.split(',');
		var creds=[];
				
		for (var i=0;i<attids.length;i++){
			creds.push({type:'public-key',id:Uint8Array.from(atob(attids[i]), function(c){return c.charCodeAt(0);}).buffer});
		}

	gid('loginbutton').style.opacity=0.6;		
	gid('loginbutton').style.filter='saturate(0)';		
	navigator.credentials.get({
		publicKey:{
			challenge:stringToArrayBuffer('no-challenge'),
			pubKeyCredParams:[{'type':'public-key','alg':-7}],
			timeout: 30000,
			allowCredentials:creds
		}
	}).then(
		function(raw){
			gid('loginbutton').style.opacity=1;
			gid('loginbutton').style.filter='';		
			var ass={ //assertion
				id:arrayBufferToHex(raw.rawId),
				clientDataJSON:arrayBufferToString(raw.response.clientDataJSON),
				userHandle:arrayBufferToHex(raw.response.userHandle),
				signature:arrayBufferToHex(raw.response.signature),
				authenticatorData:arrayBufferToHex(raw.response.authenticatorData)
			}
			
			//console.log(ass);
			
			gid('attid').value=ass.id;
			gid('clientdata').value=ass.clientDataJSON;
			gid('signature').value=ass.signature;
			gid('clientauth').value=ass.authenticatorData;
			
			if (ass.id=='') return;
			gid('loginnopass').checked='checked';
						
			gid('loginform').submit();
		}
				
	).catch(function(err){
		gid('loginbutton').style.opacity=1;		
		gid('loginbutton').style.filter='';		
	});
		
			
	});
	
	//gid('password').value='nopass';gid('loginform').submit();
}

</script>

<script>
if (navigator.serviceWorker&&navigator.serviceWorker.register){
	navigator.serviceWorker.register('service_worker.js');
}

function onlinestatuschanged(){
	if (navigator.onLine){
		gid('loginform').style.display='block';
		gid('offlineform').style.display='none';
	} else {
		gid('loginform').style.display='none';
		gid('offlineform').style.display='block';
		gid('password').value='';	
	}
}

window.addEventListener('offline',onlinestatuschanged);
window.addEventListener('online',onlinestatuschanged);

window.addEventListener('beforeinstallprompt',function(e){
	if (gid('homeadder').locked) return false;
	gid('homeadder').style.display='block';
	setTimeout(function(){
		gid('homeadder').style.top=0;
		e.preventDefault();
		gid('homeapp').onclick=function(){
			gid('homeadder').style.display='none';
			gid('homeadder').locked=1;
			e.prompt();
		}
	},1500);
});

</script>
<?php
}
?>
</body>
</html>
