<?php

function newreportsetting(){

?>
<div class="section">
	<div class="sectiontitle"><?tr('list_reportsetting_add_tab');?></div>
	
<div class="col">
	
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportname');?>:</div>
		<input class="inp" id="reportname_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportgroup');?>:</div>
		<input class="inp" id="reportgroup_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportkey');?>:</div>
		<input class="inp" id="reportkey_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportdesc');?>:</div>
		<textarea class="inplong" style="height:60px;" id="reportdesc_new"></textarea>
	</div>
		

</div>
<div class="clear"></div>

		<div class="inputrow">
			<button onclick="addreportsetting();"><?tr('button_reportsetting_add');?></button>
		</div>

</div>
<?

}
