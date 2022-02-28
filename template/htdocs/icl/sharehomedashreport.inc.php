<?php

function sharehomedashreport(){
	global $db;
	$user=userinfo();
	$gsid=$user['gsid'];
	$userid=$user['userid'];

	$homedashreportid=GETVAL('homedashreportid');
	$shared=GETVAL('shared');

	$query="update ".TABLENAME_HOMEDASHREPORTS." set shared=? where homedashreportid=? and gsid=? and userid=?";
	sql_prep($query,$db,array($shared,$homedashreportid,$gsid,$userid));
	echo "Report share settings updated";

}
