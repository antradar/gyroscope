<?php

include 'icl/rptcale.inc.php';

function scal_rptcale_data(){
	
	$year=GETVAL('year');
	$mon=GETVAL('mon');
	
	$calid=SGET('calid');
	
	$start=mktime(0,0,0,$mon-1,1,$year);
	$end=mktime(0,0,0,$mon+2,0,$year);
			
	
?>
<textarea id="scal_data_<?php echo $calid;?>" class="inplong" style="display:none;"><?php echo json_encode(rptcale_data($start,$end));?></textarea>
<?php	
		
}