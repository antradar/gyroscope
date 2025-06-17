<?php

include 'icl/showcreditcards.inc.php';

function delcreditcard($ctx=null){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
		
	$cardid=SQET('cardid',1,$ctx);	
	
	checkgskey('delcreditcard_'.$cardid,$ctx);

	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance',null,null,$ctx);
	
	$customerid=$myrow['stripecustomerid'];
		
	$res=stripe_delmembercard($cardid,$customerid);
	if (isset($res['error'])) apperror($res['error']['message'],null,null,$ctx);
	
	logaction($ctx,'deleted credit card',array('type'=>'creditcard'),array('rectype'=>'creditcards','recid'=>0));

	showcreditcards($ctx);	
	
}