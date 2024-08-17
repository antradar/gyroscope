<?php

include 'icl/listsettings.inc.php';

function dashsettings(){
	global $uiconfig;
	
	header('tabctx: dash');
	if ($uiconfig['toolbar_position']=='top') header('newtitle: Settings');

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