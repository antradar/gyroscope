<?php

function myuser_reauth($ctx=null,$userid,$gsid){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$ckey=TABLENAME_GSS.'_'.$userid.'-'.$gsid;
	$myrow=cache_get($ckey);
	if ($myrow===false){
		$query="select gsexpiry,gstier,login,dispname,active,virtualuser,groupnames from 
		".TABLENAME_USERS.",".TABLENAME_GSS." 
		where userid=? and ".TABLENAME_USERS.".".COLNAME_GSID."=?
		and ".TABLENAME_USERS.".".COLNAME_GSID."=".TABLENAME_GSS.".".COLNAME_GSID;
		
		$rs=sql_prep($query,$db,array($userid,$gsid));
			
		$myrow=sql_fetch_assoc($rs);
		cache_set($ckey,$myrow,1800);
	}
	
	return $myrow;
	
	
}
