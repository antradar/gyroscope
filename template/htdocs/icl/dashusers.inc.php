<?php

include 'icl/listusers.inc.php';

function dashusers(){
	//subdash, do not set the tabctx to dash
	header('tabctx: subdash');
	header('newtitle: Users');
?>
<div class="section">
	<div class="sectiontitle">Users</div>
	
	
		<?php listusers(); ?>
	
		<div class="clear"></div>
</div>
<?php		
}