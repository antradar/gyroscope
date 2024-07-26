<?php
include '../template/htdocs/memcache.php';
cache_init();

if ($argc!=4){
?>
Usage: php <?php echo $argv[0];?> gsid userid cmd

<?php	
	die();	
}

$gsid=intval($argv[1]);
$userid=intval($argv[2]);
$cmd=$argv[3];

$test=cache_get('gyroscope_trace_req');
if (isset($test)&&$test){
	echo "Override previous trace request:\r\n";
	print_r($test);	
}

cache_set('gyroscope_trace_req',array(
	'gsid'=>$gsid,
	'userid'=>$userid,
	'cmd'=>$cmd
),3600);

echo "\r\nTrace request placed for GS_$gsid User_$userid on $cmd\r\n\r\n";



