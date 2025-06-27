<?php

include_once 'icl/dashmsgpipes.inc.php';

function delmsgpipe($ctx=null){
	$msgpipeid=QETVAL('msgpipeid',$ctx);
	checkgskey('delmsgpipe_'.$msgpipeid,$ctx);
	
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	if (!isset($user['groups']['msgpipe'])) apperror('Access denied',null,null,$ctx);
	
	gsguard($ctx,$msgpipeid,'msgpipes','msgpipeid');
	
	$query="delete from msgpipeusers where msgpipeid=?";
	sql_prep($query,$db,$msgpipeid);

	$query="delete from msgpipes where msgpipeid=?";
	sql_prep($query,$db,$msgpipeid);	
	
	dashmsgpipes($ctx);
	
}
