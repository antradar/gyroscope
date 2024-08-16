<?php

include 'icl/listmsgpipeusers.inc.php';

function addmsgpipeuser(){
	$msgpipeid=GETVAL('msgpipeid');
	$userid=GETVAL('userid');
	
	global $db;
	
	$user=userinfo();
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) apperror('Access denied');
	
	checkgskey('addmsgpipeuser_'.$msgpipeid);
	
	gsguard($msgpipeid,'msgpipes','msgpipeid');
	$query="select msgpipeuserid from msgpipeusers where msgpipeid=? and userid=?";
	$rs=sql_prep($query,$db,array($msgpipeid,$userid));
	if (!$myrow=sql_fetch_assoc($rs)){
		$query="insert into msgpipeusers(msgpipeid,userid) values (?,?)";
		sql_prep($query,$db,array($msgpipeid,$userid));
	}
	
	listmsgpipeusers($msgpipeid);
}