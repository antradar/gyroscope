<?php

function scal_makecal($calid,$defyear,$defmon,$roto=true,$datafunc=null,$woffset=0){
	//woffset: 1-mon as first day of week; 6-sat as first day of week
	
	if (isset($datafunc)&&is_string($datafunc)&&is_callable($datafunc)){
		$start=mktime(0,0,0,$defmon-1,1,$defyear);
		$end=mktime(0,0,0,$defmon+2,0,$defyear);
	
?>
<input id="scal_datafunc_<?php echo $calid;?>" value="<?php echo $datafunc;?>" type="hidden">
<input id="scal_today_<?php echo $calid;?>" value="<?php echo date('Y-n-j');?>" type="hidden">
<input id="scal_woffset_<?php echo $calid;?>" value="<?php echo $woffset;?>" type="hidden">
<div id="scal_dataloader_<?php echo $calid;?>" style="display:none;">
	<textarea id="scal_data_<?php echo $calid;?>" class="inplong" style="display:none;"><?php echo json_encode($datafunc($start,$end));?></textarea>
</div>
<?php
	}
?>
<div class="scal_title" style="text-align:center;padding:10px 0;position:relative;width:90%;margin:0 auto;">
	<a class="hovlink" id="scal_prevlink_<?php echo $calid;?>" style="position:absolute;top:10px;left:10px;">&laquo; Prev</a>
	<div id="scal_title_<?php echo $calid;?>">
		MMM YYYY
	</div>
	<a class="hovlink" id="scal_nextlink_<?php echo $calid;?>" style="position:absolute;top:10px;right:10px;">Next &raquo;</a>
</div>

<div class="scal_head" style="position:relative;">
<?php
$weekdays=array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

for ($i=0;$i<7;$i++){
?>
	<div class="scal_hcell dow_<?php echo ($i+$woffset)%7;?>" style="float:left;border:solid 1px;box-sizing:border-box;text-align:center;"><?php echo $weekdays[($i+$woffset)%7];?></div>
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
