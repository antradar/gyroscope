<?php

include 'icl/showcreditcards.inc.php';

function delcreditcard(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
		
	$cardid=SQET('cardid');	
	
	checkgskey('delcreditcard_'.$cardid);

	$query="select * from gss where gsid=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
		
	$res=stripe_delmembercard($cardid,$customerid);
	if ($res['error']) apperror($res['error']['message']);
	
	logaction('deleted credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards();	
	
}