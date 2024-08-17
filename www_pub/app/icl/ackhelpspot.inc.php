<?php

function ackhelpspot(){
	global $db;
	$topic=SGET('topic');
	$user=userinfo();
	$userid=$user['userid'];

			
	checkgskey('ackhelpspot_'.$topic);
	
	global $helpspots;
	
	if (!isset($helpspots[$topic])) apperror('invalid help topic');
	
	
	cache_delete(TABLENAME_GSS.'userhelpspots_'.$userid);

	$query="select * from ".TABLENAME_USERHELPSPOTS." where userid=? and helptopic=?";
	$rs=sql_prep($query,$db,array($userid,$topic));
	if ($myrow=sql_fetch_assoc($rs)) return;
	
	$query="insert into ".TABLENAME_USERHELPSPOTS." (userid,helptopic) values (?,?)";
	sql_prep($query,$db,array($userid,$topic));
	
	
}