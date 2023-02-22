<?php
include 'icl/showkeyfilepad.inc.php';
include 'icl/showuserhelptopics.inc.php';

function showuser($userid=null){
	global $smskey;
	global $userrolelocks;
	
	if (!isset($userid)) $userid=SGET('userid');
	
	$user=userinfo();
	if (!isset($user['groups']['accounts'])) die('Access denied');
	$gsid=$user['gsid'];
		
	$myuserid=$user['userid'];
	
	global $db;
	global $userroles;
	
	//vendor auth 1
	
	$query="select * from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=? ";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	
	if (!$myrow=sql_fetch_array($rs)) die('This user record has been removed');
	
	$login=$myrow['login'];
	$dispname=$myrow['dispname'];
	$active=$myrow['active'];
	$virtual=$myrow['virtualuser'];
	$passreset=$myrow['passreset'];
	$groupnames=$myrow['groupnames'];
	$groups=explode('|',$groupnames);
	$needcert=$myrow['needcert'];
	$certname=$myrow['certname'];
	if ($certname=='') $certname='<em>not set</em>';
	$needkeyfile=$myrow['needkeyfile'];
	
	$usesms=$myrow['usesms'];

	$usega=$myrow['usega'];
	$usegamepad=$myrow['usegamepad'];
		
	$smscell=$myrow['smscell'];
	
	if ($smskey=='') $usesms=0;
	
	//vendor auth 2
	
	$jsroles=str_replace('"',"'",json_encode(array_keys($userroles)));
		
	header('newtitle: '.tabtitle('<img src="imgs/t.gif" class="ico-user">'.htmlspecialchars($login)));
	
	makechangebar('user_'.$userid,"updateuser('$userid',$jsroles,'".makegskey('updateuser_'.$userid)."');",'');
	makesavebar('user_'.$userid);
	
?>



<div class="section hasqnav">
	<div class="sectiontitle" id="bmusertop_<?php echo $userid;?>"><a ondblclick="toggletabdock();"><?php echo htmlspecialchars($login);?></a></div>

	<div class="col">

	<?php
	//vendor auth 3
	?>
	
	<div id="bmusermain_<?php echo $userid;?>">

	<div class="inputrow">
		<div class="formlabel"><?php tr('username');?>:</div>
		<input class="inpmed" id="login_<?php echo $userid;?>" value="<?php echo htmlspecialchars($login);?>" oninput="this.onchange();" onchange="marktabchanged('user_<?php echo $userid;?>');" onblur="if (gid('dispname_<?php echo $userid;?>').value==''&&this.value!='') {var val=this.value.charAt(0).toUpperCase()+this.value.slice(1);gid('dispname_<?php echo $userid;?>').value=val;}">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('dispname');?>:</div>
		<input class="inpmed" id="dispname_<?php echo $userid;?>" value="<?php echo htmlspecialchars($dispname);?>" oninput="this.onchange();" onchange="marktabchanged('user_<?php echo $userid;?>');" onfocus="this.select();document.hotspot=this;">
	</div>
	
	<div class="inputrow">
		<input type="checkbox" id="active_<?php echo $userid;?>" <?php if ($active) echo 'checked';?>  onclick="marktabchanged('user_<?php echo $userid;?>');"> <label for="active_<?php echo $userid;?>"><?php tr('account_active');?></label>
		&nbsp;&nbsp;
		<input type="checkbox" id="virtual_<?php echo $userid;?>" <?php if ($virtual) echo 'checked';?>  onclick="if (this.checked) {gid('userpasses_<?php echo $userid;?>').style.display='none';gid('bmroles_<?php echo $userid;?>').style.display='none';} else {gid('userpasses_<?php echo $userid;?>').style.display='block';gid('bmroles_<?php echo $userid;?>').style.display='block';marktabchanged('user_<?php echo $userid;?>');}" onclick="if (this.checked) gid('userpasses_<?php echo $userid;?>').style.display='none'; else gid('userpasses_<?php echo $userid;?>').style.display='block';"> <label for="virtual_<?php echo $userid;?>"><?php tr('account_virtual');?></label>
	</div>
	</div><!-- bmusermain -->
		
	<div id="userpasses_<?php echo $userid;?>" style="<?php if ($virtual) echo 'display:none;';?>">
	<div class="inputrow">
		<div class="formlabel"><?php tr('new_password');?>: &nbsp; &nbsp; <span style="font-weight:normal;color:#ab0200;" id="passwarn_<?php echo $userid;?>"></span></div>
		<input class="inp" autocomplete="new-password" id="newpass_<?php echo $userid;?>" onkeyup="ajxjs(self.checkpass,'accounts.js');_checkpass(this,'passwarn_<?php echo $userid;?>');" type="password" onchange="marktabchanged('user_<?php echo $userid;?>');ajxjs(self.checkpass,'accounts.js');checkpass(this,'passwarn_<?php echo $userid;?>');">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('repeat_password');?>:</div>
		<input class="inp" id="newpass2_<?php echo $userid;?>" type="password" oninput="this.onchange();" onchange="marktabchanged('user_<?php echo $userid;?>');">
	</div>
	
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('user_<?php echo $userid;?>');" id="passreset_<?php echo $userid;?>" <?php if ($passreset) echo 'checked';?>> <label for="passreset_<?php echo $userid;?>"><?php tr('account_login_reset');?></label>
	</div>

	<div class="inputrow" id="cardsettings_<?php echo $userid;?>">
		<div class="formlabel">ID Card: &nbsp; 
			<span style="font-weight:normal;" id="cardstatus_<?php echo $userid;?>"><?php echo $certname;?></span> 
			<a class="labelbutton" id="smartcardloader_<?php echo $userid;?>" onclick="loadsmartcard(<?php echo $userid;?>);">load card</a>
			<span style="display:none;"><textarea id="cert_<?php echo $userid;?>" value=""></textarea></span>
		</div>
		<input onclick="marktabchanged('user_<?php echo $userid;?>');" type="checkbox" id="needcert_<?php echo $userid;?>" <?php if ($needcert) echo 'checked';?>> <label for="needcert_<?php echo $userid;?>">card must be present at sign-in</label>

	</div>
	
	<div class="inputrow">
		<input type="checkbox" <?php if ($smskey=='') echo 'disabled';?> id="usesms_<?php echo $userid;?>" <?php if ($usesms) echo 'checked';?> onclick="marktabchanged('user_<?php echo $userid;?>');if (this.checked) {gid('smscellview_<?php echo $userid;?>').style.display='block';gid('smscell_<?php echo $userid;?>').focus();} else gid('smscellview_<?php echo $userid;?>').style.display='none';">
		<label style="<?php if ($smskey=='') echo 'color:#666666;';?>" for="usesms_<?php echo $userid;?>">use SMS code to enhance login</label>
	</div>
	
	<div id="smscellview_<?php echo $userid;?>" style="padding-left:30px;display:none<?php if ($usesms) echo 'a';?>;">
		<div class="inputrow">
			Cell: <input class="inpmed" id="smscell_<?php echo $userid;?>" value="<?php echo htmlspecialchars($smscell);?>" oninput="this.onchange();" onchange="marktabchanged('user_<?php echo $userid;?>');">
		</div>
	</div>
	
	<?php if ($usega){?>
	<div class="inputrow">
		<input type="checkbox" id="unlockga_<?php echo $userid;?>" onclick="marktabchanged('user_<?php echo $userid;?>');"> <label for="unlockga_<?php echo $userid;?>">unlock Google Authenticator</label>
	</div>
	<?php }?>
	
	
	
	<div class="inputrow">
		<input onclick="marktabchanged('user_<?php echo $userid;?>');if (this.checked) {gid('keyfileview_<?php echo $userid;?>').style.display='block';gid('bmkeyfile_<?php echo $userid;?>').style.display='block';} else {gid('keyfileview_<?php echo $userid;?>').style.display='none';gid('bmkeyfile_<?php echo $userid;?>').style.display='none';}" type="checkbox" id="userneedkeyfile_<?php echo $userid;?>" <?php if ($needkeyfile) echo 'checked';?>> <label for="userneedkeyfile_<?php echo $userid;?>">enhance login with a key file</label>
		<?php makehelp('helpuserneedkeyfile_'.$userid,'once set, the generated key file has to be attached each time you sign in.');?>
	</div>
		
	<div class="inputrow">
		<input type="checkbox" id="usegamepad_<?php echo $userid;?>" <?php if ($usegamepad) echo 'checked';?> onclick="marktabchanged('user_<?php echo $userid;?>');"> <label for="usegamepad_<?php echo $userid;?>">enable gamepad controls</label>
	</div>


		
	<div class="inputrow" id="bmuserroles_<?php echo $userid;?>">
		<div class="formlabel"><?php tr('account_roles');?>:</div>
		<?php
		$warning=0;
		foreach ($userroles as $role=>$label){
		?>
		<div style="padding-left:10px;margin-bottom:3px;<?php if (in_array($role,$userrolelocks)&&!in_array($role,$groups)&&!isset($user['groups'][$role])) echo 'display:none;';?>">
			<input <?php if (in_array($role,$userrolelocks)&&!isset($user['groups'][$role])) echo 'disabled';?> onclick="marktabchanged('user_<?php echo $userid;?>');" type="checkbox" id="userrole_<?php echo $role;?>_<?php echo $userid;?>" <?php if (in_array($role,$groups)) echo 'checked';?>> 
			<label for="userrole_<?php echo $role;?>_<?php echo $userid;?>"><?php echo $label;?><?php if ($myuserid==$userid&&in_array($role,$userrolelocks)&&$user['groups'][$role]) {$warning=1;echo ' <span style="color:#ab0200;">*</span>';}?></label>
		</div>
		<?php	
		}
		?>
	</div>	
	</div><!-- userpasses -->

	
	<?php if ($warning){
	?>
	<div class="warnbox">
		* You are editing your own access. Once you uncheck the items that are marked with asterisks, you cannot re-grant yourself.
	</div>
	<?php	
	}?>
	
	<div class="inputrow buttonbelt">
		<button onclick="updateuser('<?php echo $userid;?>',<?php echo $jsroles;?>,'<?php emitgskey('updateuser_'.$userid,'accounts');?>');"><?php tr('button_update');?></button>
		&nbsp; &nbsp;
		<button class="warn" onclick="deluser('<?php echo $userid;?>','<?php emitgskey('deluser_'.$userid,'accounts');?>');"><?php tr('button_delete');?></button>
	</div>


	</div>
	
	<div class="col">
		<div id="keyfileview_<?php echo $userid;?>" style="display:none<?php if ($needkeyfile) echo 'a';?>;">
			<div class="sectionheader">Key File</div>
			<?php showkeyfilepad('keyfileeditor_'.$userid,$userid);?>
		</div>
	</div>

	<div class="clear"></div>

	<?php if ($userid==$myuserid){?>
	<div id="muserhelptopics_<?php echo $userid;?>">
		<?php showuserhelptopics();?>
	</div>
	<?php }
	
	
	?>
		
</div>
<div class="qnav_">
	<div class="qnav">
		<a class="qnavitem" onclick="gototabbookmark('bmusertop_<?php echo $userid;?>');">Ba<b>sic Info</b></a>
		<a id="bmroles_<?php echo $userid;?>" style="display:none<?php if (!$virtual) echo 'a';?>;" class="qnavitem" onclick="gototabbookmark('bmuserroles_<?php echo $userid;?>');">Ro<b>les</b></a>
		<a id="bmkeyfile_<?php echo $userid;?>" style="display:none<?php if ($needkeyfile) echo 'a';?>;" class="qnavitem" onclick="gototabbookmark('keyfileview_<?php echo $userid;?>');">Ke<b>y File</b></a>
	</div>
</div>
<?php
}
