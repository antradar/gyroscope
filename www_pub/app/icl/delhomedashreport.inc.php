<?php

include 'icl/listhomedashreports.inc.php';

function delhomedashreport(){
	
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];

	if (!$userid) apperror('Error deleting report');
		
	$homedashreportid=SGET('homedashreportid');

	checkgskey('delhomedashreport_'.$homedashreportid);
	
	$query="delete from ".TABLENAME_HOMEDASHREPORTS." where homedashreportid=? and userid=?";
	sql_prep($query,$db,array($homedashreportid,$userid));

	listhomedashreports();		
}

