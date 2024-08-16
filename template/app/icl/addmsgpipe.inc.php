<?php

include 'icl/dashmsgpipes.inc.php';

function addmsgpipe(){
	checkgskey('addmsgpipe');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!isset($user['groups']['msgpipe'])) apperror('Access denied');
	
	$msgpipekey=SQET('msgpipekey');
	$msgpipename=SQET('msgpipename');
	
	$query="insert into msgpipes(gsid,msgpipekey,msgpipename) values (?,?,?)";
	sql_prep($query,$db,array($gsid,$msgpipekey,$msgpipename));
	
	dashmsgpipes();
	
}
