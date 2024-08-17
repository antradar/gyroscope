<?php

include 'icl/showcreditcards.inc.php';

function setdefaultcreditcard(){
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	global $db;	
	
	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
	
	$cardid=SQET('cardid');	
	
	checkgskey('setdefaultcreditcard_'.$cardid);
	
	$res=stripe_setdefaultmembercard($customerid,$cardid);
	if (isset($res['error'])) apperror($res['error']['message']);
	
	logaction('update default credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards();	
	
}