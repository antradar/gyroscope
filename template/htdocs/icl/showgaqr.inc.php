<?php

include_once 'base32.php';
include_once 'encdec.php';
include 'makeslug.php';

function showgaqr($userid){
	global $db;
	global $codepage;
	global $dbsalt;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$query="select usega,gakey,login from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	$myrow=sql_fetch_assoc($rs);
	
	$enc_remote=0; //set $remote=1 in production, sync with testgapin.inc.php
		
	$usega=$myrow['usega'];
	$gakey=$myrow['gakey'];

	if ($gakey!='') $gakey=decstr($gakey,GYROSCOPE_PROJECT.'gakey-'.COLNAME_GSID.'-'.$gsid.'-'.$userid,$enc_remote); 
	$login=$myrow['login'];
	
	$gsproj_parts=explode(' ',GYROSCOPE_PROJECT);
	$gsproj=makeslug($gsproj_parts[0]);
	$dlogin=makeslug($login);
	
	$fresh=0;
	
	if ($gakey==''){
		$gakey=substr(encstr($dbsalt.$userid.time().rand(1,9999),$dbsalt),0,20);
		$dbgakey=$gakey;
		$dbgakey=encstr($gakey,GYROSCOPE_PROJECT.'gakey-'.COLNAME_GSID.'-'.$gsid.'-'.$userid,$enc_remote); //use remote encryption key
		$query="update ".TABLENAME_USERS." set gakey=? where userid=? and ".COLNAME_GSID."=?";
		sql_prep($query,$db,array($dbgakey,$userid,$gsid));
		$fresh=1;
	}
			
	$secret=Base32::encode($gakey);
			
	$url="otpauth://totp/$gsproj-$dlogin?secret=$secret&issuer=$gsproj&digits=6&period=30";
	
	//echo $url;

	if (!$fresh){		
?>	
	<a class="hovlink" onclick="showhide('myaccount_gakeyview');">show QR setup code</a>
<?php
	} //fresh
?>

	<div style="display:none<?php if ($fresh) echo 'a';?>" id="myaccount_gakeyview">
<?php
	if (!$fresh&&$usega){
		
?>
	<div style="width:180px;text-align:center;padding-top:10px;">
		<a class="hovlink" onclick="resetgakey('<?php emitgskey('resetgakey');?>');">revoke</a>
	</div>
<?php
	}	
	
	/*
	//showhide: ajxblobimg('myqrcode','<?php echo $codepage;?>?cmd=imgqrcode&data=<?php echo $url;?>','<?php echo $codepage;?>?cmd=imgqrcode','data=<?php echo $url;?>');
?>
			<img id="myqrcode" src="imgs/t.gif" style="background:#999999;" width="180">
<?php
	*/
?>
		<img id="myqrcode" src="<?php echo $codepage;?>?cmd=imgqrcode&data=<?php echo $url;?>" width="180">
	</div>
<?php	
			
}








