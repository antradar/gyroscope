<?php
include '../template/htdocs/memcache.php';
cache_init();

$test=cache_get('gyroscope_trace_req');

if ($argc==2&&$argv[1]=='show'){
	if (isset($test)&&$test){
		echo "\r\nPending Request:  ";
		foreach ($test as $k=>$v) echo "$k: \033[33m$v\033[0m  ";
		echo "\r\n\r\n";
	} else echo "\r\n\033[33m  No outstanding trace request found. Maybe it's executed already?\033[0m\r\n\r\n";
	die();	
}

if ($argc!=4){
	echo "\r\n\033[30m\033[1mTo place a trace request:\033[0m\r\n\033[1m  php ".$argv[0]." \033[33m\033[1mgsid userid cmd\033[0m\r\n\r\n";
	echo "\033[30m\033[1mTo check trace request status:\033[0m\r\n\033[1m  php ".$argv[0]." \033[33m\033[1mshow\033[0m\r\n\r\n";	
	die();	
}

$gsid=intval($argv[1]);
$userid=intval($argv[2]);
$cmd=$argv[3];

cache_set('gyroscope_trace_req',array(
	'gsid'=>$gsid,
	'userid'=>$userid,
	'cmd'=>$cmd
),3600);

echo "\r\n  Trace request placed:\r\n     gsid: \033[33m\033[1m$gsid\033[0m   userid: \033[33m\033[1m$userid\033[0m   cmd: \033[33m\033[1m$cmd\033[0m\r\n\r\n";

if (isset($test)&&$test){
	echo "\033[31m\033[1m  Warning: the new request replaces a previously scheduled trace request:\033[0m\r\n";
	echo "    \033[30m\033[1m";
	foreach ($test as $k=>$v){
		echo " $k: $v  ";	
	}
	echo "\033[0m\r\n\r\n";
}




