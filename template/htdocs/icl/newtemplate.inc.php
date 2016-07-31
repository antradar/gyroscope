<?php

function newtemplate($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=GETVAL('templatetypeid');
	
	header('parenttab: templatetype_'.$templatetypeid);
	
?>
<div class="section">
	<div class="sectiontitle"><?tr('list_template_add_tab');?></div>
	
<div class="col">
	<div class="inputrow">
		<div class="formlabel"><?tr('template_label_templatename');?>:</div>
		<input class="inp" id="templatename_new">
	</div>

</div>
<div class="clear"></div>

		<div class="inputrow">
			<button onclick="addtemplate(<?echo $templatetypeid?>);"><?tr('button_template_add')?></button>
		</div>
</div>
<?

}
