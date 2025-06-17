<?php

include 'icl/showuserprofile.inc.php';

function removeuserprofilepic($ctx=null){
	$user=userinfo($ctx);
	$userid=$user['userid']+0;
	global $db;
	
	checkgskey('removeuserprofilepic_'.$userid,$ctx);	
		
	$query="update ".TABLENAME_USERS." set haspic=0 where userid=?";
	sql_prep($query,$db,$userid);
		
	$fn='../../protected/userpics/'.$userid.'.png';
	if (file_exists($fn)) unlink($fn);
		
	logaction($ctx,"removed profile picture of user #$userid",array('userid'=>$userid));
		
	
	showuserprofile($ctx,$userid);
		
}
