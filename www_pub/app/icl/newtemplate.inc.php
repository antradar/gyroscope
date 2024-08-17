<?php

function newtemplate($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=SGET('templatetypeid');
	
	header('parenttab: templatetype_'.$templatetypeid);
	
?>
<div class="section">
	<div class="sectiontitle"><?php tr('list_template_add_tab');?></div>
	
<div class="col">
	<div class="inputrow">
		<div class="formlabel"><?php tr('template_label_templatename');?>:</div>
		<input class="inp" id="templatename_new" onfocus="document.hotspot=this;">
	</div>

</div>
<div class="clear"></div>

		<div class="inputrow">
			<button onclick="addtemplate('<?php echo $templatetypeid?>','<?php emitgskey('addtemplate_'.$templatetypeid);?>');"><?php tr('button_template_add')?></button>
		</div>
</div>
<?php

}
