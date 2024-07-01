<?php

include 'icl/listmsgpipeusers.inc.php';

function delmsgpipeuser(){
	$msgpipeid=GETVAL('msgpipeid');
	$userid=GETVAL('userid');
	
	global $db;
	
	$user=userinfo();
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) apperror('Access denied');	
	
	checkgskey('delmsgpipeuser_'.$userid);
	
	gsguard($msgpipeid,'msgpipes','msgpipeid');
	$query="delete from msgpipeusers where msgpipeid=? and msgpipeuserid=?";
	sql_prep($query,$db,array($msgpipeid,$userid));
	
	listmsgpipeusers($msgpipeid);
}