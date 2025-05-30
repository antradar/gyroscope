<?php
include 'lb.php';

include 'auth.php';

login(1);

$c=file_get_contents('myservices.php');
preg_match_all('/case\s+(\S+?):/',$c,$matches);

$switches=array();
foreach ($matches[1] as $switch){
	$switch=trim($switch,"'");
	$switch=trim($switch,'"');
	$switches[$switch]=$switch;	
}

$cmds=array();

$sort=$_GET['sort']??'time_avg';

$sorts=array(
	'cmd'=>'cmd',
	'time_avg'=>'time',
	'cputime_avg'=>'cpu_time',
	'memx_avg'=>'mem',
	'ir'=>'i_r',
	'hit'=>'hits',
	//'memx'=>'mem_total',
	//'time'=>'time_total',
);


foreach ($switches as $switch){
	$hit=cache_get_entity_ver('metric_hit_'.$switch,1);
	if (!$hit) continue;
	
	$memx=cache_get_entity_ver('metric_memx_'.$switch,1);
	$time=cache_get_entity_ver('metric_time_'.$switch,1);
	$cputime=cache_get_entity_ver('metric_cputime_'.$switch,1);
	
	$cmds[$switch]=array(
		'cmd'=>$switch,
		'hit'=>$hit,
		'memx'=>$memx,
		'time'=>$time,
		'cputime'=>$cputime,
		'ir'=>$cputime>0?($time*100/$cputime):'-',
		'memx_avg'=>$memx/$hit,
		'time_avg'=>$time/$hit,
		'cputime_avg'=>$cputime/$hit,
	);
}

if (count($cmds)==0) die();

uasort($cmds,function($a,$b) use ($sort){
	
	$va=$a[$sort];
	$vb=$b[$sort];
	if ($va==$vb) return 0;

	$inverter=1;
	
	if ($sort=='cmd') $inverter=-1;
	
	return ($va<$vb?1*$inverter:-1*$inverter);
});

?>
<div style="padding:10px 0;">
Sort by: 
<?php foreach ($sorts as $skey=>$sname){
?>
<span onclick="ajxpgn('sysinfo_metrics','sysinfo_metrics.php?sort=<?php echo $skey;?>');" style="display:inline-block;padding:4px 8px;border:solid 1px #dedede;border-radius:5px;cursor:pointer;<?php if ($sort==$skey) echo 'color:#0000ff;';?>"><?php echo $sname;?></span>
<?php	
}
?>
</div>
<table width="100%" cellpadding="3">
<tr>
	<td><b>cmd</b></td>
	<td><b>time/ms</b></td>
	<td><b>cpu/&mu;s</b></td>
	<td><b>mem/mb</b></td>
	<td><b><acronym title="idle ratio">i.r.</acronym></b></td>
	<td><b>hits</b></td>

</tr>
<?php
	foreach ($cmds as $cmd){
?>
<tr>
	<td><?php echo $cmd['cmd'];?></td>
	<td><?php echo round($cmd['time_avg']);?></td>
	<td><?php echo round($cmd['cputime_avg']);?></td>
	<td><?php echo round($cmd['memx_avg']);?></td>
	<td><?php echo round($cmd['ir']);?></td>
	<td><?php echo $cmd['hit'];?></td>

</tr>

<?php		
	}//foreach
?>
</table>
<?php

//echo '<pre>'; print_r($cmds); echo '</pre>';

