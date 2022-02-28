<?php

include 'icl/showuserprofile.inc.php';

function removeuserprofilepic(){
	$user=userinfo();
	$userid=$user['userid']+0;
	global $db;
	
	checkgskey('removeuserprofilepic_'.$userid);	
		
	$query="update ".TABLENAME_USERS." set haspic=0 where userid=?";
	sql_prep($query,$db,$userid);
		
	$fn='../../protected/userpics/'.$userid.'.png';
	if (file_exists($fn)) unlink($fn);
		
	logaction("removed profile picture of user #$userid",array('userid'=>$userid));
		
	
	showuserprofile($userid);
		
}
