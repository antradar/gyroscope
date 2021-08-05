<?php

include 'icl/showcreditcards.inc.php';

function addcreditcard(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('addcreditcard');
		
	$query="select * from gss where gsid=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];	
	
	$ccname=$_POST['ccname'];
	$ccnum=$_POST['ccnum'];
	$ccv=$_POST['ccv'];
	$expmon=$_POST['expmon'];
	$expyear=$_POST['expyear'];
	
	
	$res=stripe_addmembercard($customerid,$ccname,$ccnum,$expmon,$expyear,$ccv);
	if ($res['error']) apperror($res['error']['message']);
	
	logaction('added credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));
	
	showcreditcards();	
	
	
}