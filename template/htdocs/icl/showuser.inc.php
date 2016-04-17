<?php

function showuser($userid=null){
	if (!isset($userid)) $userid=GETVAL('userid');
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	global $db;
	global $userroles;
	
	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	
	if (!$myrow=sql_fetch_array($rs)) die('This user record has been removed');
	
	$login=$myrow['login'];
	$active=$myrow['active'];
	$virtual=$myrow['virtual'];
	$passreset=$myrow['passreset'];
	$groupnames=$myrow['groupnames'];
	$groups=explode('|',$groupnames);
	$needcert=$myrow['needcert'];
	$certname=$myrow['certname'];
	if ($certname=='') $certname='<em>not set</em>';
	
	header('newtitle: '.$login);
	
?>
<div class="section">
	<div class="sectiontitle"><?echo $login;?></div>

	<div class="col">


	<div class="inputrow">
		<div class="formlabel">Account Login:</div>
		<input class="inpmed" id="login_<?echo $userid;?>" value="<?echo htmlspecialchars($login);?>">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_<?echo $userid;?>" <?if ($active) echo 'checked';?>> active account
		&nbsp;&nbsp;
		<input type="checkbox" id="virtual_<?echo $userid;?>" <?if ($virtual) echo 'checked';?> onclick="if (this.checked) gid('userpasses_<?echo $userid;?>').style.display='none'; else gid('userpasses_<?echo $userid;?>').style.display='block';"> virtual account
	</div>
	<div id="userpasses_<?echo $userid;?>" style="<?if ($virtual) echo 'display:none;';?>">
	<div class="inputrow">
		<div class="formlabel">New Password:</div>
		<input class="inp" id="newpass_<?echo $userid;?>" type="password">
	</div>
	<div class="inputrow">
		<div class="formlabel">Confirm Password:</div>
		<input class="inp" id="newpass2_<?echo $userid;?>" type="password">
	</div>
	
	<div class="inputrow">
		<input type="checkbox" id="passreset_<?echo $userid;?>" <?if ($passreset) echo 'checked';?>> force password reset upon login
	</div>

	<div class="inputrow" id="cardsettings_<?echo $userid;?>">
		<div class="formlabel">ID Card: &nbsp; 
			<span style="font-weight:normal;" id="cardstatus_<?echo $userid;?>"><?echo $certname;?></span> <a class="labelbutton" onclick="loadsmartcard(<?echo $userid;?>);">load card</a>
			<span style="display:none;"><textarea id="cert_<?echo $userid;?>" value=""></textarea></span>
		</div>
		<input type="checkbox" id="needcert_<?echo $userid;?>" <?if ($needcert) echo 'checked';?>> card must be present at sign-in

	</div>
	
	<div class="inputrow">
		<div class="formlabel">Roles:</div>
		<?foreach ($userroles as $role=>$label){
		?>
		<div style="padding-left:10px;margin-bottom:3px;">
			<input type="checkbox" id="userrole_<?echo $role;?>_<?echo $userid;?>" <?if (in_array($role,$groups)) echo 'checked';?>> <?echo $label;?>
		</div>
		<?	
		}?>
	</div>	
	</div><!-- userpasses -->
	
	<div class="inputrow">
		<button onclick="updateuser(<?echo $userid;?>);">Update</button>

		&nbsp; &nbsp;
		<button class="warn" onclick="deluser(<?echo $userid;?>);">Delete</button>


	</div>


	</div>
	<div class="col">

	</div>
	<div class="clear"></div>
</div>
<?
}
