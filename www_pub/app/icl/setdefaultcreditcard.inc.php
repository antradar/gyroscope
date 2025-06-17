<?php

include 'icl/showcreditcards.inc.php';

function setdefaultcreditcard($ctx=null){
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	if (isset($ctx)) $db=$ctx->db; else global $db;	
	
	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
	
	$cardid=SQET('cardid',1,$ctx);	
	
	checkgskey('setdefaultcreditcard_'.$cardid,$ctx);
	
	$res=stripe_setdefaultmembercard($customerid,$cardid);
	if (isset($res['error'])) apperror($res['error']['message'],null,null,$ctx);
	
	logaction($ctx,'update default credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards($ctx);	
	
}