<?php

include 'icl/listtemplatetypes.inc.php';

function dashtemplatetypes(){
	//subdash, do not set the tabctx to dash
	header('tabctx: subdash');
	header('newtitle: Template Classes');
?>
<div class="section">
	<div class="sectiontitle">Template Classes</div>
	
	
		<?php listtemplatetypes(); ?>
	
		<div class="clear"></div>
</div>
<?php		
}