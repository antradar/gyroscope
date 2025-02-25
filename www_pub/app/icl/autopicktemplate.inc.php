<?php

function autopicktemplate(){
	
	global $db;
	
	$key=SGET('key');
	$user=userinfo();
	$gsid=$user['gsid'];

	$query="select templateid,templatename from templates,templatetypes where templatename like ? and templatetypes.".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($key,$gsid));
	
	$recid=0;
	$recname='';
	
	if ($myrow=sql_fetch_assoc($rs)){
		$recid=$myrow['templateid'];	
		$recname=$myrow['templatename'];
	}
	
	header('recid:'.$recid);
	header('recname: '.tabtitle($recname));
	
	
}