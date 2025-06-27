<?php

include 'icl/showcreditcards.inc.php';

function addcreditcard($ctx=null){
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('addcreditcard',$ctx);
		
	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance',null,null,$ctx);
	
	$customerid=$myrow['stripecustomerid'];	
	
	$token=SQET('token',1,$ctx);
	
	
	$res=stripe_addmembercardtoken($customerid,$token);
	if (isset($res['error'])) apperror($res['error']['message'],null,null,$ctx);
	
	logaction($ctx,'added credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));
	
	showcreditcards($ctx);	
	
	
}