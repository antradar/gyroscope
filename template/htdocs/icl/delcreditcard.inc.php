<?php

include 'icl/showcreditcards.inc.php';

function delcreditcard(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
		
	$cardid=QETSTR('cardid');	

	$query="select * from gss where gsid=$gsid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
		
	$res=stripe_delmembercard($cardid,$customerid);
	if ($res['error']) apperror($res['error']['message']);
	
	logaction('deleted credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards();	
	
}