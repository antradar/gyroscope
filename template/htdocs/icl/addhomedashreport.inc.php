<?php

include 'icl/listhomedashreports.inc.php';

function addhomedashreport(){
	
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];

	if (!$userid) apperror('Error saving report');
		
	$rptname=SGET('rptname');
	$rpttitle=SGET('rpttitle');
	$rptkey=SGET('rptkey');
	$rptlink=SGET('rptlink');
	$rpttabkey=SGET('rpttabkey');
	
	$query="insert into ".TABLENAME_HOMEDASHREPORTS." (userid,rpttabkey,rptkey,rpttitle,rptlink,rptname) values (?,?,?,?,?,?)";
	sql_prep($query,$db,array($userid,$rpttabkey,$rptkey,$rpttitle,$rptlink,$rptname));

	listhomedashreports();		
}

