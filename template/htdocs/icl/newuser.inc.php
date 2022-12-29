<?php

function newuser(){
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
	
	global $userroles;	
	global $userrolelocks;
	
	
	
	$jsroles=str_replace('"',"'",json_encode(array_keys($userroles)));
	
?>
<div class="section">
	<div class="sectiontitle"><?php tr('list_user_add_tab');?></div>
	
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('username');?>:</div>
		<input class="inp" id="login_new" 
			onfocus="if (self.gsnotes_listclips) gsnotes_listclips('user');"
			onblur="if (gid('dispname_new').value==''&&this.value!='') {var val=this.value.charAt(0).toUpperCase()+this.value.slice(1);gid('dispname_new').value=val;}"
		>
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('dispname');?>:</div>
		<input class="inpmed" id="dispname_new" value="" onfocus="document.hotspot=this;this.select();">
	</div>
	<div class="inputrow">
		<input type="checkbox" id="active_new" checked> <label for="active_new"><?php tr('account_active');?></label>
		&nbsp; &nbsp;
		<input type="checkbox" id="virtual_new" onclick="if (this.checked) gid('userpasses_new').style.display='none'; else gid('userpasses_new').style.display='block';"> <label for="virtual_new"><?php tr('account_virtual');?></label>
	</div>
	<div id="userpasses_new">
		<div class="inputrow">
			<div class="formlabel"><?php tr('new_password');?>: &nbsp; &nbsp; <span style="font-weight:normal;color:#ab0200;" id="passwarn_new"></span></div>
			<input class="inp" id="newpass_new" type="password" onkeyup="ajxjs(self.checkpass,'accounts.js');_checkpass(this,'passwarn_new');" type="password" onchange="ajxjs(self.checkpass,'accounts.js');checkpass(this,'passwarn_new');">
		</div>
		<div class="inputrow">
			<div class="formlabel"><?php tr('repeat_password');?>:</div>
			<input class="inp" id="newpass2_new" type="password">
		</div>	
		<div class="inputrow">
			<input type="checkbox" id="passreset_new"> <label for="passreset_new"><?php tr('account_login_reset');?></label>
		</div>
		
		<div class="inputrow">
			<div class="formlabel"><?php tr('account_roles');?>:</div>
			<?php foreach ($userroles as $role=>$label){
			?>
			<div style="padding-left:10px;margin-bottom:3px;<?php if (in_array($role,$userrolelocks)&&(!isset($user['groups'][$role])||!$user['groups'][$role])) echo 'display:none;';?>">
				<input <?php if (in_array($role,$userrolelocks)&&(!isset($user['groups'][$role])||!$user['groups'][$role])) echo 'disabled';?>  type="checkbox" id="userrole_<?php echo $role;?>_new"> <label for="userrole_<?php echo $role;?>_new"><?php echo $label;?></label>
			</div>
			<?php	
			}?>
		</div>		
	</div>
		
		<div class="inputrow">
			<button onclick="adduser(<?php echo $jsroles;?>,'<?php emitgskey('adduser');?>');"><?php tr('button_user_add');?></button>
		</div>

</div>
<?php

}
