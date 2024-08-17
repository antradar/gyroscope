<?php

include 'icl/dashmsgpipes.inc.php';

function delmsgpipe(){
	$msgpipeid=QETVAL('msgpipeid');
	checkgskey('delmsgpipe_'.$msgpipeid);
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!isset($user['groups']['msgpipe'])) apperror('Access denied');
	
	gsguard($msgpipeid,'msgpipes','msgpipeid');
	
	$query="delete from msgpipeusers where msgpipeid=?";
	sql_prep($query,$db,$msgpipeid);

	$query="delete from msgpipes where msgpipeid=?";
	sql_prep($query,$db,$msgpipeid);	
	
	dashmsgpipes();
	
}
