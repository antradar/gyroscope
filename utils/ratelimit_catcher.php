<?php

include '../www_pub/app/lb.php'; //change this path

$redis=new Redis();
$redis->connect($WSS_INTERNAL_HOST,REDIS_PORT);


$ckey='host_ratelimit_'.GS_HOST_ID.'_general';

$pids=$redis->sRandMember($ckey.'_pids',1000);

$cmd="ps -eo pid,comm | grep php-fpm | grep -v grep | awk '{print $1}'";
$current_pids=explode("\n",trim(shell_exec($cmd)));


foreach ($pids as $pid){
	
	if (!in_array($pid,$current_pids)){
		
		$redis->srem($ckey.'_pids',$pid);
		$count=$redis->get($ckey.'_pid_'.$pid);
		$redis->del($ckey.'_pid_'.$pid);
		
		echo "Decremented zombie #$pid by $count\r\n";
		
		cache_ratelimit_release($count);
	} else {
		echo "Still alive #$pid\r\n";	
	}	
}





