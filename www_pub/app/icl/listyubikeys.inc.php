<?php

function listyubikeys($ctx){
	global $saltroot;
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$userid=$user['userid'];
	$login=$user['login'];
	$dispname=$user['dispname'];
	
	$dlogin=htmlspecialchars(noapos($login));
	$dname=htmlspecialchars(noapos($dispname));
	
	$query="select yubimode from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$yubimode=intval($myrow['yubimode']);
	if ($yubimode==1) $yubimode=2;
?>
<div class="inputrow">
	<input type="checkbox" onclick="marktabchanged('account');" id="myaccount_yubimode" <?php if ($yubimode==2) echo 'checked';?>>
	<label for="myaccount_yubimode">security keys are optional</label>
</div>
<?php	
	$mchallenge=sha1($userid.$saltroot);

	$query="select * from ".TABLENAME_YUBIKEYS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$c=sql_affected_rows($db,$rs);
	$attids=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		$keyid=$myrow['keyid'];
		$challenge=sha1($userid.$keyid.$saltroot);

		$keyname=$myrow['keyname'];
		$attid=$myrow['attid'];
		$dattid=htmlspecialchars(noapos($attid));
		$passless=intval($myrow['passless']);
		array_push($attids,$attid);
	?>
	<div class="listtiem">
		<input name="_" class="inpshort" onchange="ajxjs(self.updateyubikeyname,'yubikey.js');updateyubikeyname(<?php echo $keyid;?>,this,'<?php emitgskey('updateyubikeyname_'.$userid.'_'.$keyid,'',$ctx);?>');" value="<?php echo htmlspecialchars($keyname);?>">
		&nbsp;
		<button class="labelbutton" onclick="ajxjs(self.testyubikey,'yubikey.js');testyubikey('<?php echo $challenge;?>',['<?php echo $dattid;?>'],'<?php emitgskey('testyubikey_'.$userid);?>','',$ctx);">Test</button>
		&nbsp; &nbsp;
		<input id="myaccount_passless<?php echo $keyid;?>" onclick="ajxjs(self.testyubikey,'yubikey.js');setyubikeypassless(<?php echo $keyid;?>,this,'<?php emitgskey('setyubikeypassless_'.$userid.'_'.$keyid,'',$ctx);?>');" <?php if ($passless) echo 'checked';?> type="checkbox"> 
		<label for="myaccount_passless<?php echo $keyid;?>">password-less</label>
		&nbsp; &nbsp;
		<button class="labelbutton warn" onclick="ajxjs(self.delyubikey,'yubikey.js');delyubikey(<?php echo $keyid;?>,'<?php emitgskey('delyubikey_'.$userid.'_'.$keyid,'',$ctx);?>');">Remove</button>
	</div>
	<?php	
	}//while
	
?>
<div id="myaccount_yubikeytest" style="display:none;padding:10px 20px;"></div>
<?php	
	//if ($c>0) return;
?>
<div class="inputrow">
	<button onclick="ajxjs(self.addyubikey,'yubikey.js');addyubikey('<?php echo $mchallenge;?>','<?php echo $userid;?>','<?php echo $dlogin;?>','<?php echo $dname;?>','<?php emitgskey('addyubikey_'.$userid,'',$ctx);?>');">Add a Credential</button>
	<?php if ($c>1){?>
	&nbsp; &nbsp;
	<button onclick="ajxjs(self.testyubikey,'yubikey.js');testyubikey('<?php echo $mchallenge;?>',<?php echo str_replace('"',"'",json_encode($attids));?>,'<?php emitgskey('testyubikey_'.$userid,'',$ctx);?>');">Test All</button>	
	<?php }?>
</div>


	
<?php		
}