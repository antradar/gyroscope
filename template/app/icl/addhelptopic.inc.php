<?php

include 'icl/edithelptopic.inc.php';

function addhelptopic(){
	
	$helptopictitle=SQET('helptopictitle');
	$helptopickeywords=SQET('helptopickeywords');
	
	global $db;
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('addhelptopic');
	
	if (!$user['groups']['helpedit']) die('access denied');	
	
	$query="insert into ".TABLENAME_HELPTOPICS." (helptopictitle,helptopickeywords) values (?,?) ";
	$rs=sql_prep($query,$db,array($helptopictitle,$helptopickeywords));
	$helptopicid=sql_insert_id($db,$rs);

	if (!$helptopicid) {
		apperror(_tr('error_creating_record'));
	}
	
	$query="update ".TABLENAME_HELPTOPICS." set helptopicsort=helptopicid where helptopicid=$helptopicid";
	sql_query($query,$db);
	
	logaction("added Help #$helptopicid $helptopictitle",array('helptopicid'=>$helptopicid,'helptopictitle'=>"$helptopictitle"));
	
	header('newrecid:'.$helptopicid);
	header('newkey:helptopic_'.$helptopicid);
	header('newparams:showhelptopic&helptopicid='.$helptopicid);
	header('newloadfunc: inithelptopictexteditor('.$helptopicid.');reloadview("core.helptopics","helptopiclist");');
	
	edithelptopic($helptopicid);
	
}

