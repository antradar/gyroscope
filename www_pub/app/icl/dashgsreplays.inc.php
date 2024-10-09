<?php
include 'icl/listgsreplays.inc.php';

function dashgsreplays(){
	header("newloadfunc: ajxjs(self.showgsreplay,'gsreplays.js');");
	header('newtitle: Replay Clips');
	header('tabctx: subdash');	

?>
<div class="section">
	<div class="sectiontitle">Replay Clips</div>
	
	
		<?php listgsreplays(); ?>
	
		<div class="clear"></div>
</div>

<?php		
}
