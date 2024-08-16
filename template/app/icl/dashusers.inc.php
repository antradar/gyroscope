<?php

include 'icl/listusers.inc.php';

function dashusers(){
	global $uiconfig;
	
	header('tabctx: subdash');
	header('newtitle: Users');
?>
<div class="section userdash">

	<?php
	if ($uiconfig['toolbar_position']=='top'){
	?>	
	<span class="dash_to_list" onclick="dash_to_quicklist();">&mapstoleft;</span>
	<?php
	}
	?>
		
	<div class="sectiontitle">Users</div>
	
		<?php listusers(); ?>
	
		<div class="clear"></div>
</div>
<?php		
}