<?php

include 'icl/listsettings.inc.php';

function dashsettings(){

	header('tabctx: dash');
	header('newtitle: Settings');

?>
<div class="section">
	<div class="sectiontitle">Settings</div>
	
	<div class="dashbuttons">
		<?php listsettings(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php		
}