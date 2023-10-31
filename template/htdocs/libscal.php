<?php

function scal_makecal($calid,$defyear,$defmon,$roto=true,$datafunc=null){
	
	if (isset($datafunc)&&is_string($datafunc)&&is_callable($datafunc)){
		$start=mktime(0,0,0,$defmon-1,1,$defyear);
		$end=mktime(0,0,0,$defmon+2,0,$defyear);
	
?>
<input id="scal_datafunc" value="<?php echo $datafunc;?>" type="hidden">
<div id="scal_dataloader_<?php echo $calid;?>" style="display:none;">
	<textarea id="scal_data_<?php echo $calid;?>" class="inplong" style="display:none;"><?php echo json_encode($datafunc($start,$end));?></textarea>
</div>
<?php
	}
?>
<div id="scal_title_<?php echo $calid;?>" style="text-align:center;padding:10px 0;">
	MMM YYYY
</div>
<div id="scal_head" style="position:relative;">
<?php
$weekdays=array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

for ($i=0;$i<7;$i++){
?>
	<div style="float:left;width:14%;border:solid 1px;box-sizing:border-box;text-align:center;"><?php echo $weekdays[$i];?></div>
<?php	
}
?>
	<div class="clear"></div>
	<?php if ($roto){?>
	<div id="scal_rotation_indicator_<?php echo $calid;?>" style="display:none;position:absolute;top:80px;padding:30px;border:solid 1px #dedede;background:#ffffff;opacity:0.95;width:50%;margin-left:25%;text-align:center;">XX</div>
	<?php }?>
</div>
	
<div id="scal_frame_<?php echo $calid;?>" style="overflow:hidden;">

	<div class="scal_view" id="scal_view_<?php echo $calid;?>" style="height:300px;overflow:auto;">
		<div id="scal_prev_<?php echo $calid;?>" style="background_:#ff9999;"></div>
		<div id="scal_cur_<?php echo $calid;?>" defyear="<?php echo $defyear;?>" defmon="<?php echo $defmon;?>" style="background_:#aaffaa;"></div>
		<div id="scal_next_<?php echo $calid;?>" style="background_:#ccccff;"></div>
		
	</div>
</div>
<?php	
}
