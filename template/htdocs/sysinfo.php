<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width" />
	<title>Gyroscope Fitness Test</title>
<style>
.clear{clear:both;}

body{padding:0;margin:0;font-family:arial, sans-serif; font-size:15px;text-align:center;}

#canvas{padding:20px;padding-top:40px;text-align:left;width:560px;margin:0 auto;}

.testrow{margin-bottom:6px;padding-bottom:4px;border-bottom:solid 1px #999999;}
.testitem, .testresult{float:left;}
.testitem{width:58%;margin-right:2%;}
.testresult{width:40%;}
.res_yes{color:#00ae00;}
.res_no{color:#ab0200;}

@media screen and (max-width:700px){
	#canvas{width:80%;}
}

@media screen and (max-width:600px){
	#canvas{width:auto;}	
}

@media screen and (max-width:420px){
	.testitem{width:68%;}
	.testresult{width:30%;}
}

@media screen and (max-width:310px){
	.testitem{width:78%;}
	.testresult{width:20%;}
}	
}
</style>
</head>
<body>
<div id="canvas">
<?php

$a=12147483648;
$b=$a|0;

include 'connect.php';
include 'auth.php';

$query="show variables like '%character%'";
$rs=sql_query($query,$db);

$csets=array();

while ($myrow=sql_fetch_assoc($rs)){
	$csets[$myrow['Variable_name']]=$myrow['Value'];
}

//echo '<pre>'; print_r($csets); echo '</pre>';

$seg=memory_get_usage(1)/1024/256;
if ($seg<1) $seg=sprintf('%.2f',$seg);

$tests=array(
'Gyroscope Version'=>array('res'=>2,'message'=>GYROSCOPE_VERSION),
'Segment size'=>array('res'=>2,'message'=>$seg),
'MySQL client charset'=>array('res'=>$csets['character_set_client']=='latin1','message'=>$csets['character_set_client']),
'MySQL connection charset'=>array('res'=>$csets['character_set_connection']=='latin1','message'=>$csets['character_set_connection']),
'MySQL database charset'=>array('res'=>$csets['character_set_database']=='latin1','message'=>$csets['character_set_database']),
'MySQL results charset'=>array('res'=>$csets['character_set_results']=='latin1','message'=>$csets['character_set_results']),
'MySQL server charset'=>array('res'=>$csets['character_set_server']=='latin1','message'=>$csets['character_set_server']),
'64-bit integer'=>array('res'=>$a==$b,'message'=>''),
'Date beyond 2038'=>array('res'=>'2412-12-12'==date('Y-n-j',13978113132),'message'=>'')

);

$classes=array('no','yes','');

foreach ($tests as $test=>$result){
	$res=$result['res'];
	$message=$result['message'];
	if ($message=='') $message=$res?'Yes':'No';
	
	$resclass=$classes[$res];
	
?>
<div class="testrow">
	<div class="testitem"><?echo $test;?></div>
	<div class="testresult"><span class="res_<?echo $resclass;?>"><?echo $message;?></span></div>
	<div class="clear"></div>
</div>
<?		
}

?>
</div>
</body>
</html>
