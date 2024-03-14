<?php

function autopicktemplate(){
	//todo: apply gsguard if applicable
	global $db;
	
	$key=SGET('key');

	$query="select templateid,templatename from templates where templatename like ?";
	$rs=sql_prep($query,$db,array($key));
	
	$recid=0;
	$recname='';
	
	if ($myrow=sql_fetch_assoc($rs)){
		$recid=$myrow['templateid'];	
		$recname=$myrow['templatename'];
	}
	
	header('recid:'.$recid);
	header('recname:'.tabtitle($recname));
	
	
}