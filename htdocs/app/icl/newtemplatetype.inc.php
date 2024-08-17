<?php

function newtemplatetype(){
	$user=userinfo();
	if (!$user['groups']['systemplate']) apperror('Access denied');
			
?>
<div class="section">
	<div class="sectiontitle"><?php tr('list_templatetype_add_tab');?></div>
	
<div class="col">
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('templatetype_label_templatetypename');?>:</div>
		<input class="inp" id="templatetypename_new" onfocus="document.hotspot=this;">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('templatetype_label_templatetypekey');?>:</div>
		<input class="inp" id="templatetypekey_new">
	</div>
		

</div>
<div class="clear"></div>

		<div class="inputrow buttonbelt">
			<button onclick="addtemplatetype('<?php emitgskey('addtemplatetype','systemplate');?>');"><?php tr('button_templatetype_add');?></button>
		</div>

</div>
<?php

}
