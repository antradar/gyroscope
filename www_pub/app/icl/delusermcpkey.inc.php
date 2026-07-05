<?php

include_once 'icl/listusermcpkeys.inc.php';

function delusermcpkey($ctx=null){
	$user=userinfo($ctx);

	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$mcpkeyid=GETVAL('mcpkeyid',$ctx);

	$userid=$user['userid'];
	
	checkgskey('delusermcpkey_'.$mcpkeyid);
	
	$query="delete from mcpkeys where mcpkeyid=? and userid=?";
	sql_prep($query,$db,array($mcpkeyid,$userid));
	
	listusermcpkeys($ctx);
	
		
}