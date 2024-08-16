<?php

include 'icl/showuserhelptopics.inc.php';

function resethelpspots(){

	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	
	checkgskey('resethelpspots_'.$userid);
	
	$query="delete from ".TABLENAME_USERHELPSPOTS." where userid=?";
	sql_prep($query,$db,$userid);
	
	cache_delete(TABLENAME_GSS.'userhelpspots_'.$userid);
	
	showuserhelptopics();
		
}
