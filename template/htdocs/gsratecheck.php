<?php

function gsratecheck_verify($ip,$login){
	global $cache;
	global $usecache;
	if (!$usecache) return array(1,0);
	
	$fkey=$ip.strtolower(trim($login));
	
	$threshold=5;
	
	$res=cache_get($fkey);
		
	if ($res&&isset($res['count'])&&is_numeric($res['count'])){
		$count=$res['count'];

		if ($count>=$threshold) return array(0,$res['exp']-time());	
	}
	
	return array(1,0);
	
}

function gsratecheck_reset($ip,$login){
	global $cache;
	global $usecache;
	
	if (!$usecache) return;
	
	$fkey=$ip.strtolower(trim($login));	
	cache_delete($fkey);
	
}

function gsratecheck_registerfail($ip,$login){
	global $cache;
	global $usecache;
	if (!$usecache) return;
		
	$fkey=$ip.strtolower(trim($login));
	$threshold=5;
	$penalty=60;
	$start=time();
	$exp=$start+$penalty;
	
	$res=cache_get($fkey);
	
	$count=1;
	
	if (!$res){
		cache_set($fkey,array('count'=>1,'exp'=>$exp),$penalty);		
	} else {
		if (isset($res['count'])&&is_numeric($res['count'])) $count=$res['count']+1;
		//var_dump($res); die();
		cache_set($fkey,array('count'=>$count,'exp'=>$exp),$penalty);
	}
	
	return array($threshold-$count,$penalty);
}