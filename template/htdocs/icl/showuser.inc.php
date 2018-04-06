<?php
include 'icl/showkeyfilepad.inc.php';

function showuser($userid=null){
	global $smskey;
	
	if (!isset($userid)) $userid=GETVAL('userid');
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	$gsid=$user['gsid']+0;
	
	global $db;
	global $userroles;
	
	$query="select * from ".TABLENAME_USERS." where userid=$userid and gsid=$gsid";
	$rs=sql_query($query,$db);
	
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
	$smscell=$myrow['smscell'];
	
	if ($smskey=='') $usesms=0;
		
	header('newtitle: '.tabtitle($login));
	
	makechangebar('user_'.$userid,"updateuser($userid);");
?>



<div class="section">
	<div class="sectiontitle"><?echo $login;?></div>

	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?tr('username');?>:</div>
		<input class="inpmed" id="login_<?echo $userid;?>" value="<?echo htmlspecialchars($login);?>" onchange="marktabchanged('user_<?echo $userid;?>');" onblur="if (gid('dispname_<?echo $userid;?>').value==''&&this.value!='') {var val=this.value.charAt(0).toUpperCase()+this.value.slice(1);gid('dispname_<?echo $userid;?>').value=val;}">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('dispname');?>:</div>
		<input class="inpmed" id="dispname_<?echo $userid;?>" value="<?echo htmlspecialchars($dispname);?>" onchange="marktabchanged('user_<?echo $userid;?>');" onfocus="this.select();">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_<?echo $userid;?>" <?if ($active) echo 'checked';?>  onchange="marktabchanged('user_<?echo $userid;?>');"> <label for="active_<?echo $userid;?>"><?tr('account_active');?></label>
		&nbsp;&nbsp;
		<input type="checkbox" id="virtual_<?echo $userid;?>" <?if ($virtual) echo 'checked';?>  onchange="marktabchanged('user_<?echo $userid;?>');" onclick="if (this.checked) gid('userpasses_<?echo $userid;?>').style.display='none'; else gid('userpasses_<?echo $userid;?>').style.display='block';"> <label for="virtual_<?echo $userid;?>"><?tr('account_virtual');?></label>
	</div>
	<div id="userpasses_<?echo $userid;?>" style="<?if ($virtual) echo 'display:none;';?>">
	<div class="inputrow">
		<div class="formlabel"><?tr('new_password');?>:</div>
		<input class="inp" id="newpass_<?echo $userid;?>" type="password" onchange="marktabchanged('user_<?echo $userid;?>');">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('repeat_password');?>:</div>
		<input class="inp" id="newpass2_<?echo $userid;?>" type="password" onchange="marktabchanged('user_<?echo $userid;?>');">
	</div>
	
	<div class="inputrow">
		<input type="checkbox" onclick="marktabchanged('user_<?echo $userid;?>');" id="passreset_<?echo $userid;?>" <?if ($passreset) echo 'checked';?>> <label for="passreset_<?echo $userid;?>"><?tr('account_login_reset');?></label>
	</div>

	<div class="inputrow" id="cardsettings_<?echo $userid;?>">
		<div class="formlabel">ID Card: &nbsp; 
			<span style="font-weight:normal;" id="cardstatus_<?echo $userid;?>"><?echo $certname;?></span> <a class="labelbutton" onclick="loadsmartcard(<?echo $userid;?>);">load card</a>
			<span style="display:none;"><textarea id="cert_<?echo $userid;?>" value=""></textarea></span>
		</div>
		<input onchange="marktabchanged('user_<?echo $userid;?>');" type="checkbox" id="needcert_<?echo $userid;?>" <?if ($needcert) echo 'checked';?>> <label for="needcert_<?echo $userid;?>">card must be present at sign-in</label>

	</div>
	
	<div class="inputrow">
		<input type="checkbox" <?if ($smskey=='') echo 'disabled';?> id="usesms_<?echo $userid;?>" <?if ($usesms) echo 'checked';?> onclick="marktabchanged('user_<?echo $userid;?>');if (this.checked) {gid('smscellview_<?echo $userid;?>').style.display='block';gid('smscell_<?echo $userid;?>').focus();} else gid('smscellview_<?echo $userid;?>').style.display='none';">
		<label style="<?if ($smskey=='') echo 'color:#666666;';?>" for="usesms_<?echo $userid;?>">use SMS code to enhance login</label>
	</div>
	
	<div id="smscellview_<?echo $userid;?>" style="padding-left:30px;display:none<?if ($usesms) echo 'a';?>;">
		<div class="inputrow">
			Cell: <input class="inpmed" id="smscell_<?echo $userid;?>" value="<?echo htmlspecialchars($smscell);?>" onchange="marktabchanged('user_<?echo $userid;?>');">
		</div>
	</div>
	
	<div class="inputrow">
		<input onchange="marktabchanged('user_<?echo $userid;?>');" type="checkbox" id="userneedkeyfile_<?echo $userid;?>" <?if ($needkeyfile) echo 'checked';?>> <label for="userneedkeyfile_<?echo $userid;?>">enhance login with a key file</label>
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('account_roles');?>:</div>
		<?foreach ($userroles as $role=>$label){
		?>
		<div style="padding-left:10px;margin-bottom:3px;">
			<input onchange="marktabchanged('user_<?echo $userid;?>');" type="checkbox" id="userrole_<?echo $role;?>_<?echo $userid;?>" <?if (in_array($role,$groups)) echo 'checked';?>> 
			<label for="userrole_<?echo $role;?>_<?echo $userid;?>"><?echo $label;?></label>
		</div>
		<?	
		}
		?>
	</div>	
	</div><!-- userpasses -->
	
	<div class="inputrow">
		<button onclick="updateuser(<?echo $userid;?>);"><?tr('button_update');?></button>
		&nbsp; &nbsp;
		<button class="warn" onclick="deluser(<?echo $userid;?>);"><?tr('button_delete');?></button>
	</div>


	</div>
	
	<div class="col">
		<div class="sectionheader">Key File</div>
		<?showkeyfilepad('keyfileeditor_'.$userid,$userid);?>
		
	</div>

	<div class="clear"></div>
</div>
<?
}
