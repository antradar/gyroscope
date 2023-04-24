<?php

include 'icl/listreports.inc.php'; //for now

function dashreports(){
	global $uiconfig;
	
	header('tabctx: dash');
	if ($uiconfig['toolbar_position']=='top') header('newtitle: Reports');
	header('newloadfunc: '."ajxjs(self.showreport,'reports.js');");	
?>
<div class="section">
	<div class="sectiontitle">Reports</div>
	
	<div class="dashbuttons">
		<?php listreports(); ?>
		<div class="clear"></div>
	</div>
	
</div>
<?php	
}