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

function cache_init(){
	global $usecache;
	if (!$usecache) return;

	global $cache;
	global $cachetest;

	if ($cachetest) return;

	$cache=@memcache_connect('127.0.0.1',11211) or die('cannot connect to aux database');	
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