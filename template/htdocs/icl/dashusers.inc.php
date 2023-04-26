<?php

include 'icl/listusers.inc.php';

function dashusers(){
	
	header('tabctx: subdash');
	header('newtitle: Users');
?>
<div class="section userdash">
	
	<span class="dash_to_list" onclick="dash_to_quicklist();">&mapstoleft;</span>
		
	<div class="sectiontitle">Users</div>
	
		<?php listusers(); ?>
	
		<div class="clear"></div>
</div>
<?php		
}