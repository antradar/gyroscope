<?php
include 'icl/listreportsettings.inc.php';

function dashreportsettings(){
	
	header("newloadfunc: ajxjs(self.showreportsetting,'reportsettings.js');");
	header('newtitle: '._tr('icon_reportsettings'));
	header('tabctx: subdash'); //uncomment if the list is tucked in listsettings.php
?>
<div class="section">
	<div class="sectiontitle"><?php tr('icon_reportsettings');?></div>
	
	
		<?php listreportsettings(); ?>
	
		<div class="clear"></div>
</div>

<?php		
}
