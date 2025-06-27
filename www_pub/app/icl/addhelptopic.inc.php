<?php

include 'icl/edithelptopic.inc.php';

function addhelptopic($ctx=null){
	
	$helptopictitle=SQET('helptopictitle',1,$ctx);
	$helptopickeywords=SQET('helptopickeywords',1,$ctx);
	
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('addhelptopic',$ctx);
	
	if (!$user['groups']['helpedit']) apperror('access denied',null,null,$ctx);	
	
	$query="insert into ".TABLENAME_HELPTOPICS." (helptopictitle,helptopickeywords) values (?,?) ";
	$rs=sql_prep($query,$db,array($helptopictitle,$helptopickeywords));
	$helptopicid=sql_insert_id($db,$rs);

	if (!$helptopicid) {
		apperror(_tr('error_creating_record'),null,null,$ctx);
	}
	
	$query="update ".TABLENAME_HELPTOPICS." set helptopicsort=helptopicid where helptopicid=$helptopicid";
	sql_query($query,$db);
	
	logaction($ctx,"added Help #$helptopicid $helptopictitle",array('helptopicid'=>$helptopicid,'helptopictitle'=>"$helptopictitle"));
	
	gs_header($ctx, 'newrecid', $helptopicid);
	gs_header($ctx, 'newkey', 'helptopic_'.$helptopicid);
	gs_header($ctx, 'newparams', 'showhelptopic&helptopicid='.$helptopicid);
	gs_header($ctx, 'newloadfunc', 'inithelptopictexteditor('.$helptopicid.');reloadview("core.helptopics","helptopiclist");');
	
	edithelptopic($ctx,$helptopicid);
	
}

