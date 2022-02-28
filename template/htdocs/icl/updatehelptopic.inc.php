<?php

include 'icl/edithelptopic.inc.php';

function updatehelptopic(){
	$helptopicid=SGET('helptopicid');	
	$helptopictitle=SQET('helptopictitle');
	$helptopickeywords=SQET('helptopickeywords');
	$helptopictext=SQET('helptopictext');
	$helptopictext=str_replace('<p>&nbsp;</p>','',$helptopictext); // '>&nbsp;</' -> '></'

	global $db;
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['helpedit']) die('access denied');
	
	checkgskey('updatehelptopic_'.$helptopicid);

	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?"; //gsid=? and 
	$rs=sql_prep($query,$db,array($helptopicid)); //$gsid,
	$before=sql_fetch_assoc($rs);

	$query="update ".TABLENAME_HELPTOPICS." set helptopictitle=?,helptopickeywords=?,helptopictext=? where helptopicid=?";
	sql_prep($query,$db,array($helptopictitle,$helptopickeywords,$helptopictext,$helptopicid));

	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?"; //gsid=? and 
	$rs=sql_prep($query,$db,array($helptopicid)); //$gsid,
	$after=sql_fetch_assoc($rs);
	
	$dbchanges=array('helptopicid'=>$helptopicid,'helptopictitle'=>"$helptopictitle");
	$dbchanges=array_merge($dbchanges,diffdbchanges($before,$after,array('helptopictext'))); //arg3-masks, arg4-omits
	
	logaction("updated Help #$helptopicid $helptopictitle",
		$dbchanges,
		array('rectype'=>'helptopic','recid'=>$helptopicid));

	edithelptopic($helptopicid);
}
