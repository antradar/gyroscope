<?php
include 'icl/listhelptopics.inc.php';

function dashhelptopics(){
	global $uiconfig;
	header('tabctx: dash');
	header("newloadfunc: ajxjs(self.showhelptopic,'helptopics.js');");
	if ($uiconfig['toolbar_position']=='top') header('newtitle: '._tr('icon_helptopics'));
	

?>
<div class="section">
	<div class="sectiontitle"><?php tr('icon_helptopics');?></div>
	
	
		<?php listhelptopics(); ?>
	
		<div class="clear"></div>
</div>

<?php		
}
