<?php

include 'icl/showcreditcards.inc.php';

function setdefaultcreditcard(){
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
	
	global $db;	
	
	$query="select * from gss where gsid=$gsid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
	
	$cardid=QETSTR('cardid');	
	
	$res=stripe_setdefaultmembercard($customerid,$cardid);
	if ($res['error']) apperror($res['error']['message']);
	
	logaction('update default credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards();	
	
}