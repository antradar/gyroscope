<?php

function newuser(){
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	global $userroles;	
?>
<div class="section">
	<div class="sectiontitle">New User</div>
	
	
	<div class="inputrow">
		<div class="formlabel">Account Login:</div>
		<input class="inp" id="login_new">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_new" checked> active account
		&nbsp; &nbsp;
		<input type="checkbox" id="virtual_new" onclick="if (this.checked) gid('userpasses_new').style.display='none'; else gid('userpasses_new').style.display='block';"> virtual account
	</div>
	<div id="userpasses_new">
		<div class="inputrow">
			<div class="formlabel">Account Password:</div>
			<input class="inp" id="newpass_new" type="password">
		</div>
		<div class="inputrow">
			<div class="formlabel">Confirm Password:</div>
			<input class="inp" id="newpass2_new" type="password">
		</div>	
		<div class="inputrow">
			<input type="checkbox" id="passreset_new"> force password reset upon login
		</div>
		
		<div class="inputrow">
			<div class="formlabel">Roles:</div>
			<?foreach ($userroles as $role=>$label){
			?>
			<div style="padding-left:10px;margin-bottom:3px;">
				<input type="checkbox" id="userrole_<?echo $role;?>_new"> <?echo $label;?>
			</div>
			<?	
			}?>
		</div>		
	</div>
		
		<div class="inputrow">
			<button onclick="adduser();">Add User</button>
		</div>

</div>
<?

}
