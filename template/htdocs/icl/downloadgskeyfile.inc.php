<?php
include 'makeslug.php';

function downloadgskeyfile(){
	global $db;
	global $salt;
	global $dbsalt;

	$user=userinfo();
	$gsid=$user['gsid']+0;
	$myuserid=$user['userid'];
	
	$keyfile=$_POST['keyfileinfo'];
	$userid=$_POST['keyfileuserid'];
	
	if (!$userid) apperror('Invalid request');
	if ($myuserid!=$userid&&!$user['groups']['accounts']) apperror('Access denied');

	$query="select * from users where userid=$userid and gsid=$gsid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Access denied');
		
	$fn=makeslug($myrow['login']).'.key';
	
	
	$keyfile=encstr($keyfile,time().$salt.md5($keyfile));

	$keyfilehash=sha1($dbsalt.$keyfile);
	
	$query="update users set keyfilehash='$keyfilehash' where userid=$userid and gsid=$gsid ";
	sql_query($query,$db);

	header('Content-Type: application/octet-stream');
	header("Content-disposition: attachment; filename=$fn");
			
	echo $keyfile;
}