<?php
include 'icl/reauth.inc.php';

function deluser(){
	$userid=GETVAL('userid');
	global $db;
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('Access denied');
		
	$query="select * from ".TABLENAME_USERS." where userid=$userid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid user record');
	
	$login=$myrow['login'];
	
	$query="delete from ".TABLENAME_USERS." where userid=$userid";
	sql_query($query,$db);
	
	logaction("updated User #$userid <u>$login</u>",array('userid'=>$userid,'login'=>"$login"),array('rectype'=>'reauth','recid'=>$userid));
	reauth();
}
