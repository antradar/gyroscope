<?php

include 'libscal.php';

function rptcale(){
?>
<div class="section">
	<div class="sectiontitle">Event Calendar</div>
	
	<div style="width:80%;max-width:650px;">
		<?php scal_makecal('rptcale',date('Y'),date('n'),true,'rptcale_data');?>
	</div>
	
</div>
<?php	
}

function rptcale_data($start,$end){
	global $db;
	
	$dstart=date('Y-n-j g:ia',$start);
	$dend=date('Y-n-j g:ia',$end);
	
	$obj=array('dstart'=>$dstart,'dend'=>$dend,'days'=>array());
		
	$query="select count(*) as c, from_unixtime(logdate,'%Y-%m-%d') as daykey from actionlog where logdate>=? and logdate<? group by from_unixtime(logdate,'%Y-%m-%d')";
	$rs=sql_prep($query,$db,array($start,$end));
	
	$days=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		$daykey=$myrow['daykey'];
		$daykey=preg_replace('/-0(\d)/','-$1',$daykey);
		$days[$daykey]=array(
			'count'=>$myrow['c']
		);
	}//while
	
	$obj['days']=$days;
	
	return $obj;	
}
