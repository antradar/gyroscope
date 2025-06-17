<?php

include_once 'icl/listmsgpipeusers.inc.php';

function addmsgpipeuser($ctx=null){
	$msgpipeid=GETVAL('msgpipeid');
	$userid=GETVAL('userid');
	
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) apperror('Access denied',null,null,$ctx);
	
	checkgskey('addmsgpipeuser_'.$msgpipeid,$ctx);
	
	gsguard($ctx,$msgpipeid,'msgpipes','msgpipeid');
	$query="select msgpipeuserid from msgpipeusers where msgpipeid=? and userid=?";
	$rs=sql_prep($query,$db,array($msgpipeid,$userid));
	if (!$myrow=sql_fetch_assoc($rs)){
		$query="insert into msgpipeusers(msgpipeid,userid) values (?,?)";
		sql_prep($query,$db,array($msgpipeid,$userid));
	}
	
	listmsgpipeusers($ctx,$msgpipeid);
}