<?php
/*
Memcache Adapter (c)Antradar Software 2010
*/

global $usecache;
global $cache;
global $cachetest;

$usecache=1;
$cache=null;
$cachetest=0; //set to 1 to use file-based cache

function cache_keepalive(){
	
	//return;
	
	global $usecache;
	if (!$usecache) return 0;

	global $cache;

	set_error_handler(function($en,$msg,$file,$line){
		global $cache;
		error_log("memcache disconnected. attempting to reconnect $file:$line $msg");
		memcache_close($cache);
		usleep(10000);
		$cache=null;
		cache_init();		
	},E_ALL);
	memcache_get($cache,'memcache_keepalive');
	restore_error_handler();

}

function cache_init(){
	global $usecache;
	if (!$usecache) return;

	global $cache;
	global $cachetest;

	if ($cachetest) return;
	
	if (is_object($cache)){
		error_log('duplicate memcache connection ignored');
		return;
	}	

	$cache=@memcache_connect('127.0.0.1',11211);
	if (!$cache) {
		error_log('cannot connect to memcached');
		//die('cannot connect to aux database');
	}
	
	memcache_set($cache,'memcache_keepalive','stayalive',null,3600);
}

function cache_flush(){
	global $usecache;
	if (!$usecache) return;
	global $cache;
	global $cachetest;

	$dir='cache/';
	if ($cachetest){
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir.$file)=='file') unlink($dir.$file);
			}
		closedir($dh);
		}		
	} else {
		cache_keepalive();
		memcache_flush($cache);
	}
}

function cache_set($key,$obj,$expiry){
	global $usecache;
	if (!$usecache) return;

	global $cache;
	global $cachetest;
	
	if ($cachetest){
		$f=fopen('cache/'.$key,'wb');
		fwrite($f,serialize($obj));
		fclose($f);
	} else {
		cache_keepalive();
		memcache_set($cache,$key,$obj,null,$expiry);
	}	

}

function cache_delete($key){
	global $usecache;
	if (!$usecache) return null;
	
	global $cache;
	global $cachetest;
	
	if ($cachetest){
		if (file_exists('cache/'.$key)) unlink('cache/'.$key);
		return;	
	}	
	cache_keepalive();
	return memcache_delete($cache,$key);
}

function cache_get($key){
	global $usecache;
	if (!$usecache) return null;

	global $cache;
	global $cachetest;

	if ($cachetest){
		if (!file_exists('cache/'.$key)) return null;
		$f=fopen('cache/'.$key,'rb');
		$c=fread($f,filesize('cache/'.$key));
		fclose($f);
		return unserialize($c);
	} else {
		cache_keepalive();
		return memcache_get($cache,$key);
	}

}

function cache_clearnav($swapkey,$objkey){
	$swaps=cache_get($swapkey);
	cache_delete($objkey);
	if (!$swaps) return;
	foreach ($swaps as $sk=>$sv) cache_delete($sk.$objkey);
	cache_delete($swapkey);
}

//cache_init();
//cache_set('test',array(1,2,3),300);
//cache_flush();
//print_r(cache_get('test'));

//cache_clearnav('swaps','gnavobj');