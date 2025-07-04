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

function cache_keepalive($ctx=null){
	
	//return;
	
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;

	set_error_handler(function($en,$msg,$file,$line) use (&$cache,$ctx){
		error_log("memcache disconnected. attempting to reconnect $file:$line $msg");
		memcache_close($cache);
		$cache=null;
		usleep(10000);
		cache_init($ctx);
		if (isset($ctx)) $cache=&$ctx->memcache;
	},E_ALL);
	memcache_get($cache,'memcache_keepalive');
	restore_error_handler();

}

function cache_init($ctx=null){
	global $usecache;
	if (!$usecache) return;

	global $cachetest;

	if ($cachetest) return;
	
	if (isset($ctx)) $cache=&$ctx->memcache; else global $cache;
	
	
	if (is_object($cache)){
		error_log('duplicate memcache connection ignored');
		return;
	}	

	$cache=@memcache_connect('127.0.0.1',11211);
	if (!$cache) {
		error_log('cannot connect to memcached');
		//die('cannot connect to aux database');
	}
	
	/*
	memcache_add_server($cache,'memcache_server_2',11211);
	*/
	
	memcache_set($cache,'memcache_keepalive','stayalive',null,3600);
}

function cache_get_entity_ver($entity,$skip_setting=0,$ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
		
	if (!defined('MEMCACHE_PREFIX')){
		define('MEMCACHE_PREFIX','');	
	}
	$verkey=MEMCACHE_PREFIX.':'.$entity.':ver';
	$ver=memcache_get($cache,$verkey);
	if ($skip_setting) return $ver;
	if (!$ver){
		$ver=1;
		memcache_set($cache,$verkey,$ver,null,3600*6);
	}
	return $ver;	
}

function cache_inc_entity_ver($entity,$offset=1,$ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
	
	if (!defined('MEMCACHE_PREFIX')){
		define('MEMCACHE_PREFIX','');	
	}
	$verkey=MEMCACHE_PREFIX.':'.$entity.':ver';
	$ver=memcache_increment($cache,$verkey,$offset);
	if (!$ver){
		$ver=$offset;
		memcache_set($cache,$verkey,$ver,null,3600*24*2+10);
	}
	return $ver;	
}

function cache_ratelimit($unit, $limit, $resgroup='general', $ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
		
	global $WSS_INTERNAL_HOST;
	global $redis;
	
	$ounit=$unit;
	if ($unit==0) $unit=1; //still count, but do not block
	$hostid='';
	if (defined('GS_HOST_ID')) $hostid='_'.GS_HOST_ID;
	$ckey='host_ratelimit'.$hostid.'_'.$resgroup;
	$used=memcache_increment($cache,$ckey,$unit);
	
	if (!$used){
		$used=$unit;
		memcache_set($cache,$ckey,$unit,null,0);
	}	
	
	if (defined('SYS_RESOURCE_PID_WATCHER')&&SYS_RESOURCE_PID_WATCHER==1){
		$pid=getmypid();
		if (!isset($redis)){
			$redis=new Redis();
			$redis->connect($WSS_INTERNAL_HOST,REDIS_PORT);	
		}
		
		$redis->sadd($ckey.'_pids',$pid);
		$redis->incr($ckey.'_pid_'.$pid,$unit);
	}
	

	if ($used>$limit&&$ounit!=0){
		memcache_decrement($cache,$ckey,$unit);
		header('HTTP/1.0 429 Too many requests. Slow down!');
		if (defined('SYS_RESOURCE_PID_WATCHER')&&SYS_RESOURCE_PID_WATCHER==1){
			$redis->srem($ckey.'_pids',$pid);
			$count=$redis->decr($ckey.'_pid_'.$pid,$unit);
			if ($count<=0) $redis->del($ckey.'_pid_'.$pid);
		}
		die();
	}
		
}

function cache_ratelimit_release($unit, $resgroup='general', $ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
		
	global $WSS_INTERNAL_HOST;
	global $redis;
	if ($unit==0) $unit=1;
	
	if (defined('GS_HOST_ID')) $hostid='_'.GS_HOST_ID;	
	$ckey='host_ratelimit'.$hostid.'_'.$resgroup;
	
	memcache_decrement($cache,$ckey,$unit);
	
	if (defined('SYS_RESOURCE_PID_WATCHER')&&SYS_RESOURCE_PID_WATCHER==1){
		$pid=getmypid();	
		if (!isset($redis)){
			$redis=new Redis();
			$redis->connect($WSS_INTERNAL_HOST,REDIS_PORT);
		}
		
		$redis->srem($ckey.'_pids',$pid);
		$count=$redis->decr($ckey.'_pid_'.$pid,$unit);
		if ($count<=0) $redis->del($ckey.'_pid_'.$pid);		
	}
	
}


function cache_flush($ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;

	$dir='cache/';
	if ($cachetest){
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir.$file)=='file') unlink($dir.$file);
			}
		closedir($dh);
		}		
	} else {
		cache_keepalive($ctx);
		memcache_flush($cache);
	}
}

function cache_set($key,$obj,$expiry,$ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;

	if (!defined('MEMCACHE_PREFIX')){
		define('MEMCACHE_PREFIX','');	
	}
	
	$key=MEMCACHE_PREFIX.$key;
		
	if ($cachetest){
		$f=fopen('cache/'.$key,'wb');
		fwrite($f,serialize($obj));
		fclose($f);
	} else {
		cache_keepalive($ctx);
		memcache_set($cache,$key,$obj,null,$expiry);
	}	

}

function cache_delete($key,$ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
	
	if (!defined('MEMCACHE_PREFIX')){
		define('MEMCACHE_PREFIX','');	
	}
	
	$key=MEMCACHE_PREFIX.$key;
	
	
	if ($cachetest){
		if (file_exists('cache/'.$key)) unlink('cache/'.$key);
		return;	
	}	
	cache_keepalive($ctx);
	return memcache_delete($cache,$key);
}

function cache_get($key,$ctx=null){
	global $usecache;
	global $cachetest;
	
	if (isset($ctx)) {$cache=&$ctx->memcache;$usecache=1;$cachetest=0;} else global $cache;
	
	if (!$usecache) return null;

	
	if (!defined('MEMCACHE_PREFIX')){
		define('MEMCACHE_PREFIX','');	
	}
	
	$key=MEMCACHE_PREFIX.$key;
	
	if ($cachetest){
		if (!file_exists('cache/'.$key)) return null;
		$f=fopen('cache/'.$key,'rb');
		$c=fread($f,filesize('cache/'.$key));
		fclose($f);
		return unserialize($c);
	} else {
		cache_keepalive($ctx);
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

/*
$ctx=new StdClass();
$ctx->memcache=@memcache_connect('127.0.0.1',11211);
sleep(10);
cache_keepalive($ctx);
cache_set('test',array(123),600,$ctx);

print_r(cache_get('test',$ctx));
*/
