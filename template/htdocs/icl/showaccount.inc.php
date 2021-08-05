<?php
include 'icl/showkeyfilepad.inc.php';
include 'icl/showuserhelptopics.inc.php';

include 'icl/showgaqr.inc.php';
include 'inbound/libmsgraph.php';


function showaccount(){
	global $smskey;
	
	$user=userinfo();

	global $db;
	
	$userid=$user['userid'];
	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	
	$needkeyfile=$myrow['needkeyfile'];
	$usesms=$myrow['usesms'];
	$smscell=$myrow['smscell'];
	$usega=$myrow['usega'];
	$gakey=$myrow['gakey'];
	
	$usegamepad=$myrow['usegamepad'];
	
	$login=$myrow['login'];
	
	$rtoken=$myrow['msgraphtoken'];
	
	if ($gakey=='') $usega=0;
	
	if ($smskey=='') $usesms=0;

	makechangebar('account',"setaccountpass();"); //disabled for now
		
?>
<div class="section">

<div class="sectiontitle"><?php tr('account_settings');?></div>
<div class="inputrow"><?php echo htmlspecialchars($login);?></div>

<div class="col">
	<div class="sectionheader"><?php tr('password');?></div>
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('current_password');?>:</div>
		<input class="inp" id="accountpass" type="password" oninput="this.onchange();" onchange="marktabchanged('account');">
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('new_password');?>: &nbsp; &nbsp; <span style="font-weight:normal;color:#ab0200;" id="accountpasswarn"></span></div>
		<input class="inp" autocomplete="new-password" id="accountpass1" type="password" onkeyup="_checkpass(this,'accountpasswarn');" onchange="checkpass(this,'accountpasswarn');">
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('repeat_password');?>:</div>
		<input class="inp" id="accountpass2" type="password">
	</div>

	<div class="inputrow">
		<input type="checkbox" <?php if ($smskey=='') echo 'disabled';?> id="myaccount_usesms" <?php if ($usesms) echo 'checked';?> onclick="marktabchanged('account'); if (this.checked) {gid('myaccount_smscellview').style.display='block';gid('myaccount_smscell').focus();} else gid('myaccount_smscellview').style.display='none';">
		<label style="<?php if ($smskey=='') echo 'color:#666666;'?>" for="myaccount_usesms">use SMS code to enhance login</label>
	</div>
	
	<div id="myaccount_smscellview" style="padding-left:30px;display:none<?php if ($usesms) echo 'a';?>;">
		<div class="inputrow">
			Cell: <input class="inpmed" id="myaccount_smscell" value="<?php echo htmlspecialchars($smscell);?>" oninput="this.onchange();" onchange="marktabchanged('account');">
		</div>
	</div>
	
	<div class="inputrow">
		<input type="checkbox" id="myaccount_usega" <?php if ($usega) echo 'checked';?> onclick="marktabchanged('account'); if (this.checked) {gid('myaccount_gaview').style.display='block';gid('myaccount_gatestpin').focus();} else gid('myaccount_gaview').style.display='none';">
		<label for="myaccount_usega"> use Google Authenticator</label>
	</div>
	<div id="myaccount_gaview" style="padding-left:30px;display:none<?php if ($usega) echo 'a';?>;">
		<div>
		<?php showgaqr($userid);?>
		</div>
		<div class="inputrow">
			Test PIN: <input class="inpshort" id="myaccount_gatestpin"> <button onclick="testgapin();">Test</button>
		</div>
	</div>
		
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('account');showhide('keyfilecontainer');" id="myaccount_needkeyfile" <?php if ($needkeyfile) echo 'checked';?>>
		<label for="myaccount_needkeyfile">use a key file to enhance login</label>
	</div>
	
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('account');" id="myaccount_usegamepad" <?php if ($usegamepad) echo 'checked';?>>
		<label for="myaccount_usegamepad">enable gamepad controls</label>
	</div>
	
	<div class="inputrow">
		<button onclick="setaccountpass();"><?php tr('button_update');?></button>
	</div>
	
</div>
<div id="keyfilecontainer" class="col" style="display:none<?php if ($needkeyfile) echo 'a';?>;">

	<div class="sectionheader">Key File</div>
	<?php showkeyfilepad('mykeyfile',$userid);?>
</div>
<div class="clear"></div>

<?php
	if (is_callable('msgraph_gettoken')){
	?>
	<div id="msconnector">
	<?php	
		$url=msgraph_authlink($userid);

		if ($rtoken!=''){
			$token=msgraph_gettoken($rtoken);
			$me=msgraph_request($token,'/me');
			//echo '<pre>'; print_r($me); echo '</pre>';
			if ($me['displayName']=='') $rtoken='';
		}
				
		if ($rtoken!=''){
			
	?>
	<div class="infobox">
		Your account is connected to the Microsoft Account <u><?php echo htmlspecialchars($me['displayName']);?></u>.
		<br><br>
		<a class="hovlink" onclick="window.open('<?php echo $url;?>','msconnect','width=800,height=500,toolbar=no');">Connect Again</a>
		&nbsp; &nbsp;
		<a class="hovlink" onclick="msgraphdisconnect();">Disconnect</a>

	</div>
	<?php		
		} else {
		
	?>
	<button onclick="window.open('<?php echo $url;?>','msconnect','width=800,height=500,toolbar=no');">Connect Microsoft Account</button>
	<?php
		}
	?>
	</div>
	<?php
	}//msgraph
	?>


	<div id="userhelptopics_<?php echo $userid;?>">
		<?php showuserhelptopics();?>
	</div>


</div><!-- section -->
<?php
	
}