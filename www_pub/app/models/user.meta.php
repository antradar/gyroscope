<?php

function user_meta($userid,$gsid){
	global $db;
	
	$ver=cache_get_entity_ver('user_'.$gsid);
	$ckey='user_theme_'.$ver.'_'.$userid;
	
	$meta=cache_get($ckey);
	if (!$meta){
	
		$query="select * from ".TABLENAME_USERS." where userid=?";
		$rs=sql_prep($query,$db,$userid);
		$meta=sql_fetch_assoc($rs);
		
		cache_set($ckey,$meta,3600);
	}
	
	return $meta;
	
}