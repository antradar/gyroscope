<?php
include_once 'icl/showdefleftcontent.inc.php';

function setmyquicklist($ctx=null){
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	$quicklist=GETVAL('quicklist',$ctx);
	$silent=intval(SGET('silent',1,$ctx));
	
	$query="update ".TABLENAME_USERS." set quicklist=? where userid=?";
	sql_prep($query,$db,array($quicklist,$userid));
	
	cache_inc_entity_ver('user_'.$gsid);
	
	if (!$silent) showdefleftcontent();
		
}