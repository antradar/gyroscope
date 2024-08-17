<?php

function newreportsetting(){
	$user=userinfo();
	if (!$user['groups']['devreports']) apperror('access denied');
?>
<div class="section">
	<div class="sectiontitle"><?php tr('list_reportsetting_add_tab');?></div>
	
<div class="col">
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportname');?>:</div>
		<input class="inp" id="reportname_new" onfocus="document.hotspot=this;">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportgroup');?>:</div>
		<input class="inp" id="reportgroup_new" onfocus="document.hotspot=this;">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportfunc');?>:</div>
		<input class="inp" id="reportfunc_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportkey');?>:</div>
		<input class="inp" id="reportkey_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportdesc');?>:</div>
		<textarea class="inplong" style="height:60px;" id="reportdesc_new" onfocus="document.hotspot=this;"></textarea>
	</div>
		

</div>
<div class="clear"></div>

		<div class="inputrow buttonbelt">
			<button onclick="addreportsetting('<?php emitgskey('addreportsetting','reportsettings');?>');"><?php tr('button_reportsetting_add');?></button>
		</div>

</div>
<?php

}
