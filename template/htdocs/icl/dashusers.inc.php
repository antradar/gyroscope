<?php

include 'icl/listusers.inc.php';

function dashusers(){
	
	header('tabctx: subdash');
	header('newtitle: Users');
?>
<div class="section userdash">
	<div class="sectiontitle">Users</div>
	
	
		<?php listusers(); ?>
	
		<div class="clear"></div>
</div>
<?php		
}