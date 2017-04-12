<?php

function showuser($userid=null){
	if (!isset($userid)) $userid=GETVAL('userid');
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	global $db;
	global $userroles;
	
	$query="select * from ".TABLENAME_USERS." where userid=$userid";
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
	
	header('newtitle: '.tabtitle($login));
	
?>
<div class="section">
	<div class="sectiontitle"><?echo $login;?></div>

	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?tr('username');?>:</div>
		<input class="inpmed" id="login_<?echo $userid;?>" value="<?echo htmlspecialchars($login);?>" onblur="if (gid('dispname_<?echo $userid;?>').value=='') gid('dispname_<?echo $userid;?>').value=this.value;">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('dispname');?>:</div>
		<input class="inpmed" id="dispname_<?echo $userid;?>" value="<?echo htmlspecialchars($dispname);?>" onfocus="this.select();">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_<?echo $userid;?>" <?if ($active) echo 'checked';?>> <label for="active_<?echo $userid;?>"><?tr('account_active');?></label>
		&nbsp;&nbsp;
		<input type="checkbox" id="virtual_<?echo $userid;?>" <?if ($virtual) echo 'checked';?> onclick="if (this.checked) gid('userpasses_<?echo $userid;?>').style.display='none'; else gid('userpasses_<?echo $userid;?>').style.display='block';"> <label for="virtual_<?echo $userid;?>"><?tr('account_virtual');?></label>
	</div>
	<div id="userpasses_<?echo $userid;?>" style="<?if ($virtual) echo 'display:none;';?>">
	<div class="inputrow">
		<div class="formlabel"><?tr('new_password');?>:</div>
		<input class="inp" id="newpass_<?echo $userid;?>" type="password">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('repeat_password');?>:</div>
		<input class="inp" id="newpass2_<?echo $userid;?>" type="password">
	</div>
	
	<div class="inputrow">
		<input type="checkbox" id="passreset_<?echo $userid;?>" <?if ($passreset) echo 'checked';?>> <label for="passreset_<?echo $userid;?>"><?tr('account_login_reset');?></label>
	</div>

	<div class="inputrow" id="cardsettings_<?echo $userid;?>">
		<div class="formlabel">ID Card: &nbsp; 
			<span style="font-weight:normal;" id="cardstatus_<?echo $userid;?>"><?echo $certname;?></span> <a class="labelbutton" onclick="loadsmartcard(<?echo $userid;?>);">load card</a>
			<span style="display:none;"><textarea id="cert_<?echo $userid;?>" value=""></textarea></span>
		</div>
		<input type="checkbox" id="needcert_<?echo $userid;?>" <?if ($needcert) echo 'checked';?>> card must be present at sign-in

	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('account_roles');?>:</div>
		<?foreach ($userroles as $role=>$label){
		?>
		<div style="padding-left:10px;margin-bottom:3px;">
			<input type="checkbox" id="userrole_<?echo $role;?>_<?echo $userid;?>" <?if (in_array($role,$groups)) echo 'checked';?>> 
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

	</div>
	<div class="clear"></div>
</div>
<?
}
