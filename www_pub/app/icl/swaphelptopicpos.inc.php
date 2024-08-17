<?php

include 'icl/listhelptopics.inc.php';

function swaphelptopicpos(){
	$src=GETVAL('tmid');
	$dst=GETVAL('targetid');
	$dstsort=null;

	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['helpedit']) die('access denied');
	
	checkgskey('swaphelptopicpos');

	global $db;

	$finaldst=0;
	if ($dst==-1) {
		$finaldst=1;
		$query="select helptopicid,helptopicsort from helptopics order by helptopicsort desc limit 1";
		$rs=sql_prep($query,$db);
		$myrow=sql_fetch_assoc($rs);
		$dst=$myrow['helptopicid'];
		$dstsort=$myrow['helptopicsort'];		
	}
		
	$query="select helptopicsort from helptopics where helptopicid=? ";
	$rs=sql_prep($query,$db,array($src));
	
	$myrow=sql_fetch_assoc($rs);
	$ssrc=$myrow['helptopicsort'];
	
	if (!$ssrc) apperror('Sorting index corrupted; cannot switch positions');
	
	$query="select helptopicsort from helptopics where helptopicid=? ";
	$rs=sql_prep($query,$db,array($dst));
	
	$myrow=sql_fetch_assoc($rs);
	$sdst=$myrow['helptopicsort'];
		
	if (!$sdst) apperror('Sorting index corrupted; cannot switch positions');
	
	//echo "$src $ssrc < > $dst $sdst";

	if ($ssrc==$sdst-1&&!$finaldst){listhelptopics(); return;}

	if ($ssrc<$sdst) $query="select * from helptopics where  helptopicsort>? and helptopicsort<? order by helptopicsort";
	if ($ssrc>=$sdst) $query="select * from helptopics where  helptopicsort<? and helptopicsort>=? order by helptopicsort desc";

	$prev=$ssrc;
	$rs=sql_prep($query,$db,array($ssrc,$sdst));
	while ($myrow=sql_fetch_assoc($rs)){
		$itemid=$myrow['helptopicid'];
		$itemsort=$myrow['helptopicsort'];
		$query="update helptopics set helptopicsort=? where  helptopicid=?";
		sql_prep($query,$db,array($prev,$itemid));
		$prev=$itemsort;	
	}//while
	
	$query="update helptopics set helptopicsort=? where  helptopicid=?";
	sql_prep($query,$db,array($prev,$src));
	
	if ($finaldst){
		$query="update helptopics set helptopicsort=? where helptopicid=?";
		sql_prep($query,$db,array($dstsort,$src));
		sql_prep($query,$db,array($prev,$dst));			
	}
		
	listhelptopics();
}

