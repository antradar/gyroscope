<?php

function newuser(){
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	global $userroles;	
?>
<div class="section">
	<div class="sectiontitle"><?tr('list_user_add_tab');?></div>
	
	
	<div class="inputrow">
		<div class="formlabel"><?tr('username');?>:</div>
		<input class="inp" id="login_new" onblur="if (gid('dispname_new').value=='') gid('dispname_new').value=this.value;">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('dispname');?>:</div>
		<input class="inpmed" id="dispname_new" value="<?echo htmlspecialchars($dispname);?>" onfocus="this.select();">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_new" checked> <label for="active_new"><?tr('account_active');?></label>
		&nbsp; &nbsp;
		<input type="checkbox" id="virtual_new" onclick="if (this.checked) gid('userpasses_new').style.display='none'; else gid('userpasses_new').style.display='block';"> <label for="virtual_new"><?tr('account_virtual');?></label>
	</div>
	<div id="userpasses_new">
		<div class="inputrow">
			<div class="formlabel"><?tr('new_password');?>:</div>
			<input class="inp" id="newpass_new" type="password">
		</div>
		<div class="inputrow">
			<div class="formlabel"><?tr('repeat_password');?>:</div>
			<input class="inp" id="newpass2_new" type="password">
		</div>	
		<div class="inputrow">
			<input type="checkbox" id="passreset_new"> <label for="passreset_new"><?tr('account_login_reset');?></label>
		</div>
		
		<div class="inputrow">
			<div class="formlabel"><?tr('account_roles');?>:</div>
			<?foreach ($userroles as $role=>$label){
			?>
			<div style="padding-left:10px;margin-bottom:3px;">
				<input type="checkbox" id="userrole_<?echo $role;?>_new"> <label for="userrole_<?echo $role;?>_new"><?echo $label;?></label>
			</div>
			<?	
			}?>
		</div>		
	</div>
		
		<div class="inputrow">
			<button onclick="adduser();"><?tr('button_user_add');?></button>
		</div>

</div>
<?

}
