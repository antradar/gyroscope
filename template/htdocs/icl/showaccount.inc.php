<?php
include 'icl/showkeyfilepad.inc.php';
include 'icl/showuserhelptopics.inc.php';

include 'icl/showgaqr.inc.php';
//include 'inbound/libmsgraph.php'; //uncomment if the file is available

include 'icl/showuserprofile.inc.php';
include 'icl/listyubikeys.inc.php';

function showaccount(){
		
	global $smskey;
	global $dict_weekdays;
	
	//ob_start();
	
	$user=userinfo();

	global $db;
		
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	
	$needkeyfile=$myrow['needkeyfile'];
	$usesms=$myrow['usesms'];
	$smscell=$myrow['smscell'];
	$usega=$myrow['usega'];
	$gakey=$myrow['gakey'];
	
	$quicklist=isset($myrow['quicklist'])?intval($myrow['quicklist']):0;
	$darkmode=isset($myrow['darkmode'])?intval($myrow['darkmode']):0;
	$dowoffset=isset($myrow['dowoffset'])?intval($myrow['dowoffset']):0;

	
	$useyubi=$myrow['useyubi'];
	
	$usegamepad=$myrow['usegamepad'];
	
	$login=$myrow['login'];
	
	$rtoken=$myrow['msgraphtoken'];
	
	if ($gakey=='') $usega=0;
	
	if ($smskey=='') $usesms=0;
	
	$canchat=$myrow['canchat'];

	makechangebar('account',"setaccountpass($darkmode);",''); //disabled for now
	makesavebar('account');	
?>
<div class="section">

<div class="sectiontitle">
	<a ondblclick="toggletabdock();"><?php tr('account_settings');?></a>
</div>
<div class="inputrow">Your Login: <?php echo htmlspecialchars($login);?></div>
<?php
if (isset($user['groups']['chats'])){
?>
<div class="inputrow">
<input type="checkbox" id="accountcanchat" <?php if ($canchat) echo 'checked';?> onclick="document.appsettings.beepnewchat=this.checked;ajxpgn('statusc',document.appsettings.binpages[1]+'?cmd=setcanchat&canchat='+(this.checked?1:0));"> <label for="accountcanchat">I'm available for a support chat</label>
</div>
<?php	
}
?>

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
		<input type="checkbox" id="myaccount_useyubi" <?php if ($useyubi) echo 'checked';?> onclick="marktabchanged('account'); if (this.checked) {gid('myaccount_yubikeys').style.display='block';} else gid('myaccount_yubikeys').style.display='none';">
		<label for="myaccount_useyubi">enable hardware security keys and screen lock <a class="hovlink" href="<?php echo YUBIHELP;?>" target=_blank>[?]</a></label>
	</div>
	
	<div id="myaccount_yubikeys" style="padding-left:30px;padding-bottom:10px;display:none<?php if ($useyubi) echo 'a';?>;">
		<?php listyubikeys();?>
	</div>

		
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('account');showhide('keyfilecontainer');" id="myaccount_needkeyfile" <?php if ($needkeyfile) echo 'checked';?>>
		<label for="myaccount_needkeyfile">use a key file to enhance login</label>
	</div>
	
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('account');" id="myaccount_usegamepad" <?php if ($usegamepad) echo 'checked';?>>
		<label for="myaccount_usegamepad">enable gamepad controls</label>
	</div>
	
	<div class="inputrow buttonbelt">
		<button onclick="setaccountpass(<?php echo $darkmode;?>);"><?php tr('button_update');?></button>
	</div>
	
</div>
<div class="col">
	<div class="sectionheader">Interface Preferences</div>
	
	<div class="inputrow" style="line-height:1.5em;display:none;">
		<div class="formlabel">QuickList:</div>
		<div class="infobox" style="padding:0">
			QuickList is always enabled on mobile devices.
		</div>
		
		<input type="radio" name="myaccount_quicklist" id="myaccount_quicklist_0" onclick="sv('myaccount_quicklist',0);marktabchanged('account');" <?php if ($quicklist==0) echo 'checked';?>>
		<label for="myaccount_quicklist_0">Off - show records in the main view</label><br>
		
		<input type="radio" name="myaccount_quicklist" id="myaccount_quicklist_1" onclick="sv('myaccount_quicklist',1);marktabchanged('account');" <?php if ($quicklist==1) echo 'checked';?>>
		<label for="myaccount_quicklist_1">On - show records in the independent left panel first</label><br>
		<input id="myaccount_quicklist" value="<?php echo $quicklist;?>" type="hidden">
	</div>
	
	<div class="inputrow" style="line-height:1.5em;">
		<div class="formlabel">Dark Mode:</div>

		<input type="radio" name="myaccount_darkmode" id="myaccount_darkmode_0" onclick="sv('myaccount_darkmode',0);marktabchanged('account');" <?php if ($darkmode==0) echo 'checked';?>>
		<label for="myaccount_darkmode_0">same as the web browser</label> <br>

		<input type="radio" name="myaccount_darkmode" id="myaccount_darkmode_1" onclick="sv('myaccount_darkmode',1);marktabchanged('account');" <?php if ($darkmode==1) echo 'checked';?>>
		<label for="myaccount_darkmode_1">use dark theme</label> <br>

		<input type="radio" name="myaccount_darkmode" id="myaccount_darkmode_2" onclick="sv('myaccount_darkmode',2);marktabchanged('account');" <?php if ($darkmode==2) echo 'checked';?>>
		<label for="myaccount_darkmode_2">use light theme</label> <br>
				
		<input id="myaccount_darkmode" value="<?php echo $darkmode;?>" type="hidden">
	</div>
	
	<div class="inputrow" style="line-height:1.5em;margin-bottom:30px;">
		<div class="formlabel">Week starts on:</div>
		<select class="inp" id="myaccount_dowoffset" onchange="marktabchanged('account');">
		<?php foreach ($dict_weekdays as $dow=>$label){?>
			<option <?php if ($dowoffset==$dow) echo 'selected';?> value="<?php echo $dow;?>"><?php echo $label;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="sectionheader">Profile Photo (270x270)</div>
	<div id="userprofile_<?php echo $userid;?>">
		<?php showuserprofile($userid);?>
	</div>
	
	<div id="keyfilecontainer" style="display:none<?php if ($needkeyfile) echo 'a';?>;">

		<div class="sectionheader">Key File</div>
		<?php showkeyfilepad('mykeyfile',$userid);?>
	</div>
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
	
<div style="padding-top:20px;"></div>

<style>
	.useccol0,.useccol1,.useccol2,.useccol3,.useccol4{float:left;}
	.useccol0{width:13%;margin-left:1%;margin-right:1%;}
	.useccol1{width:24%;margin-right:1%;text-align:center;}
	.useccol2{width:13%;margin-right:1%;}
	.useccol3{width:21%;margin-right:1%;}
	.useccol4{width:20%;margin-right:1%;}
</style>

<?php

	global $vdb;
	
	if (isset($vdb)){

	$query="select ip,city,xprov,xcountry,min(logdate) as mindate,max(logdate) as maxdate from accesslog where ".COLNAME_GSID."=$gsid and userid=$userid group by ip,city,xprov,xcountry order by maxdate desc limit 10";
	$rs=vsql_query($query,$vdb);
	$c=vsql_affected_rows($vdb,$rs);
	
	if ($c>0){
?>
<div class="sectionheader">Recent Account Access</div>
<div class="stable">
<div class="grid">
	<div class="gridrow">
		<div class="gridheader" style="color:#ffffff;">
			<div class="useccol0">Latest Access</div>
			<div class="useccol1">IP Address</div>
			<div class="useccol2">First Accessed</div>
			<div class="useccol3">City</div>
			<div class="useccol4">Country</div>
			<div class="clear"></div>
		</div>
	</div>
<?php
	}

	$idx=0;

	while ($myrow=vsql_fetch_assoc($rs)){
		$mindate=$myrow['mindate'];
		$maxdate=$myrow['maxdate'];

		$dmindate=date('Y-n-j g:ia',$mindate);
		$dmaxdate=date('Y-n-j g:ia',$maxdate);
	?>
	<div class="gridrow<?php if ($idx%2==0) echo ' even';?>">
		<div class="useccol0"><?php echo $dmaxdate;?></div>
		<div class="useccol1"><?php echo htmlspecialchars($myrow['ip']);?></div>
		<div class="useccol2"><?php echo $dmindate;?></div>
		<div class="useccol3"><?php echo htmlspecialchars(trim($myrow['city'].' '.$myrow['xprov']));?></div>
		<div class="useccol4"><?php echo htmlspecialchars($myrow['xcountry']);?></div>
		<div class="clear"></div>
	</div>
	<?php
		$idx++;
	}//while
	
	if ($c>0){
?>
	</div><!-- grid -->
	</div><!-- stable -->
<?php		
	}
?>


</div>

<div style="padding-top:40px;"></div>

<?php
} //vdb
?>

</div><!-- section -->
<?php
//	$output=ob_get_clean();
//	blobout($output);	
	
}