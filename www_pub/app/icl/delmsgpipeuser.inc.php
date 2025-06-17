<?php

include_once 'icl/listmsgpipeusers.inc.php';

function delmsgpipeuser($ctx=null){
	$msgpipeid=GETVAL('msgpipeid');
	$userid=GETVAL('userid');
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) apperror('Access denied');	
	
	checkgskey('delmsgpipeuser_'.$userid,$ctx);
	
	gsguard($ctx,$msgpipeid,'msgpipes','msgpipeid');
	$query="delete from msgpipeusers where msgpipeid=? and msgpipeuserid=?";
	sql_prep($query,$db,array($msgpipeid,$userid));
	
	listmsgpipeusers($ctx,$msgpipeid);
}