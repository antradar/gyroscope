<?php

include_once 'icl/listusermcpkeys.inc.php';

function addusermcpkey($ctx=null){
	$user=userinfo($ctx);

	if (isset($ctx)) $db=&$ctx->db; else global $db;

	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	checkgskey('addusermcpkey_'.$userid);
	
	//just random enough
	$rand=base64_encode(sha1(time().$gsid.makegskey('mcpkey_'.$userid.$gsid.mt_rand()).$userid.mt_rand()));
	$mcpkey=substr($rand,rand(0,20),30);
	
	$query="insert into mcpkeys(userid,mcpkey) values (?,?)";
	sql_prep($query,$db,array($userid,$mcpkey));
	
	listusermcpkeys($ctx);
	
}
