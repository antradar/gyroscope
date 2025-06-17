<?php

if (file_exists('vectorhelp.ext.php')){
	include 'vectorhelp.ext.php';
}

function delhelptopic($ctx=null){
	$helptopicid=SGET('helptopicid',1,$ctx);
	if (isset($ctx)) $db=$ctx->db; else global $db;
	global $vdb;
	global $enable_vectorhelp;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('delhelptopic_'.$helptopicid,$ctx);	
	
	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?";
	$rs=sql_prep($query,$db,array($helptopicid));
	if (!$myrow=sql_fetch_array($rs)) apperror('Invalid helptopic record',null,null,$ctx);
	
	$helptopictitle=$myrow['helptopictitle'];
	
	$query="delete from ".TABLENAME_HELPTOPICS." where helptopicid=?";
	sql_prep($query,$db,array($helptopicid));
	
	if (isset($enable_vectorhelp)&&$enable_vectorhelp&&isset($vdb)&&is_callable('vectorhelp_remove')){
		vectorhelp_remove($helptopicid);
	}	
	
	logaction($ctx,"deleted Help #$helptopicid $helptopictitle",
		array('helptopicid'=>$helptopicid,'helptopictitle'=>$helptopictitle),
		array('rectype'=>'helptopic','recid'=>$helptopicid));
}
