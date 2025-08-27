<?php

include 'icl/edithelptopic.inc.php';
if (file_exists('vectorhelp.ext.php')){
	include 'vectorhelp.ext.php';
}

function updatehelptopic($ctx=null){
	$helptopicid=GETVAL('helptopicid',$ctx);	
	$helptopictitle=SQET('helptopictitle',1,$ctx);
	$helptopickeywords=SQET('helptopickeywords',1,$ctx);
	$helptopictext=SQET('helptopictext',1,$ctx);
	$helptopictext=str_replace('<p>&nbsp;</p>','',$helptopictext); // '>&nbsp;</' -> '></'

	if (isset($ctx)) $db=&$ctx->db; else global $db;
	global $vdb;
	global $enable_vectorhelp;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	if (!$user['groups']['helpedit']) die('access denied');
	
	checkgskey('updatehelptopic_'.$helptopicid,$ctx);

	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?"; //gsid=? and 
	$rs=sql_prep($query,$db,array($helptopicid)); //$gsid,
	$before=sql_fetch_assoc($rs);

	$query="update ".TABLENAME_HELPTOPICS." set helptopictitle=?,helptopickeywords=?,helptopictext=? where helptopicid=?";
	sql_prep($query,$db,array($helptopictitle,$helptopickeywords,$helptopictext,$helptopicid));
	
	if (isset($enable_vectorhelp)&&$enable_vectorhelp&&isset($vdb)&&is_callable('vectorhelp_register')){
		vectorhelp_register($helptopicid,$helptopictitle,$helptopickeywords,$helptopictext);
	}

	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?"; //gsid=? and 
	$rs=sql_prep($query,$db,array($helptopicid)); //$gsid,
	$after=sql_fetch_assoc($rs);
	
	$dbchanges=array('helptopicid'=>$helptopicid,'helptopictitle'=>"$helptopictitle");
	$dbchanges=array_merge($dbchanges,diffdbchanges($before,$after,array('helptopictext'))); //arg3-masks, arg4-omits
	
	logaction($ctx, "updated Help #$helptopicid $helptopictitle",
		$dbchanges,
		array('rectype'=>'helptopic','recid'=>$helptopicid));

	edithelptopic($ctx, $helptopicid);
}
