<?php

include 'libnumfile.php';

function gsreplay_submit_frame(){
	global $db;
	
	$gsreplayid=QETVAL('gsreplayid');
	$toffset=QETVAL('toffset');
	$itr=QETVAL('itr');

	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
		
	$query="select gsreplayid from gsreplays where gsreplayid=? and gsid=? and gsreplayuserid=?";
	$rs=sql_prep($query,$db,array($gsreplayid,$gsid,$userid));
	if (!$myrow=sql_fetch_assoc($rs)) apperror('access denied');

	$query="insert into gsreplayframes (gsreplayid,frametoffset,frameitr) values (?,?,?)";
	$rs=sql_prep($query,$db,array($gsreplayid,$toffset,$itr));
	$frameid=sql_insert_id($db,$rs);
			
	$binstr=SQET('binstr',1);
	if ($binstr!='') $frame=base64_decode($binstr);
	else $frame=file_get_contents($_FILES['frame']['tmp_name']);
	$basedir='../../protected/gsreplays/';
	$ext=$frameid.'.png';
	
	numfile_put_contents($gsreplayid,$ext,$basedir,$frame);		
	
	echo "uploaded frame #$frameid";	
}
