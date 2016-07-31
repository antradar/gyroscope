<?php

function newtemplatetype(){
	$user=userinfo();
	if (!$user['groups']['systemplate']) apperror('Access denied');
?>
<div class="section">
	<div class="sectiontitle"><?tr('list_templatetype_add_tab');?></div>
	
<div class="col">
	
	<div class="inputrow">
		<div class="formlabel"><?tr('templatetype_label_templatetypename');?>:</div>
		<input class="inp" id="templatetypename_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('templatetype_label_templatetypekey');?>:</div>
		<input class="inp" id="templatetypekey_new">
	</div>
		

</div>
<div class="clear"></div>

		<div class="inputrow">
			<button onclick="addtemplatetype();"><?tr('button_templatetype_add');?></button>
		</div>

</div>
<?

}
