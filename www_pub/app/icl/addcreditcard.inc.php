<?php

include 'icl/showcreditcards.inc.php';

function addcreditcard(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('addcreditcard');
		
	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];	
	
	$token=SQET('token');
	
	
	$res=stripe_addmembercardtoken($customerid,$token);
	if (isset($res['error'])) apperror($res['error']['message']);
	
	logaction('added credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));
	
	showcreditcards();	
	
	
}