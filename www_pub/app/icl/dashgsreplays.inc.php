<?php
include 'icl/listgsreplays.inc.php';

function dashgsreplays(){
	header("newloadfunc: ajxjs(self.showgsreplay,'gsreplays.js');");
	header('newtitle: '._tr('icon_gsreplays'));
	header('tabctx: subdash');	

?>
<div class="section">
	<div class="sectiontitle"><?php tr('icon_gsreplays');?></div>
	
	
		<?php listgsreplays(); ?>
	
		<div class="clear"></div>
</div>

<?php		
}
