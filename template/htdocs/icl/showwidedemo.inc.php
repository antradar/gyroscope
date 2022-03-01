<?php

function showwidedemo(){

	$wideid=GETVAL('wideid');
	$max=15;
?>
<div class="wideview">
	<div class="wideviewmenu_">
	<div class="wideviewmenu" id="lbookmarks_wide_<?php echo $wideid;?>"><?php /* add "transpose" to the class to keep the menu in mobile views */ ?>
		<div class="sectiontitle">Wide View Menu <?php echo $wideid;?></div>
		<?php
		for ($i=0;$i<$max;$i++){
		?>
		<div class="listitem" id="lbookmark_wide_<?php echo $wideid;?>_section<?php echo $i;?>"><a onclick="gototabbookmark('widesec_<?php echo $wideid;?>_<?php echo $i;?>');">Section <?php echo $i+1;?></a></div>
		<?php	
		}
		?>
	</div></div>
	
	<div class="section hasqnav">
		<div class="sectiontitle">Wide View</div>
		<input onfocus="pickdate(this);" id="wideviewdate_<?php echo $wideid;?>" class="inpmed">
		<?php makelookup('wideviewdate_'.$wideid,1);?>
		
		<?php for ($i=0;$i<$max;$i++){?>
		<div style="margin-bottom:200px;" id="bookmark_wide_<?php echo $wideid;?>_section<?php echo $i;?>">
			<div class="sectionheader" id="widesec_<?php echo $wideid;?>_<?php echo $i;?>">Section #<?php echo $i+1;?></div>
			
		</div>
		<?php }?>
		
	</div>
	
	<div class="qnav_">
		<div class="qnav">
		<?php for ($i=0;$i<$max;$i++){?>
			<a class="qnavitem" onclick="gototabbookmark('widesec_<?php echo $wideid;?>_<?php echo $i;?>');">S<b>ection </b><?php echo $i+1;?></a>
		<?php }?>
		</div>
	</div>

</div>
<?php	
		
}