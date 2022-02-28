<?php

include 'icl/listhelptopics.inc.php';

function swaphelptopicpos(){
	$src=GETVAL('tmid');
	$dst=GETVAL('targetid');

	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['helpedit']) die('access denied');
	
	checkgskey('swaphelptopicpos');

	global $db;

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

	if ($ssrc==$sdst-1){listhelptopics(); return;}

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
	
	listhelptopics();
}

