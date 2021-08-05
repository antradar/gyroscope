<?php

include 'icl/calcgapins.inc.php';
include 'encdec.php';

function testgapin(){
	global $db;
	
	$msg='Invalid PIN';
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	
	$query="select gakey from ".TABLENAME_USERS." where userid=? and ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	$myrow=sql_fetch_assoc($rs);
	
	$gakey=$myrow['gakey'];
		
	if ($gakey!='') $gakey=decstr($gakey,GYROSCOPE_PROJECT.'gakey-'.COLNAME_GSID.'-'.$gsid.'-'.$userid,1); //remote key

	$pin=str_replace(array(' ','-','.'),'',SQET('pin'));
	
	$pins=calcgapins($gakey);
	
	
	if (in_array($pin,$pins)){
		$msg='PIN Valid';	
	}

	
	header('pinres: '.tabtitle($msg));
		
		
}