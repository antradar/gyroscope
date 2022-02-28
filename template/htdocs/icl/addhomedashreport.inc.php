<?php

include 'icl/listhomedashreports.inc.php';

function addhomedashreport(){
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	$userid=$user['userid'];

	if (!$userid) apperror('Error saving report');
		
	$rptname=SGET('rptname');
	$rpttitle=SGET('rpttitle');
	$rptkey=SGET('rptkey');
	$rptlink=SGET('rptlink');
	$rpttabkey=SGET('rpttabkey');
	$bingo=GETVAL('bingo');
	
	$query="insert into ".TABLENAME_HOMEDASHREPORTS." (gsid,userid,rpttabkey,rptkey,rpttitle,rptlink,rptname,bingo) values (?,?,?,?,?,?,?,?)";
	sql_prep($query,$db,array($gsid,$userid,$rpttabkey,$rptkey,$rpttitle,$rptlink,$rptname,$bingo));

	listhomedashreports();		
}

