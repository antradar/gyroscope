<?php
include 'makeslug.php';
include 'encdec.php';

function downloadgskeyfile(){
	global $db;
	global $salt;
	global $dbsalt;

	$user=userinfo();
	$gsid=$user['gsid'];
	$myuserid=$user['userid'];
	
	
	$keyfile=$_POST['keyfileinfo'];
	$userid=SQET('keyfileuserid');
	
	if (!$userid) apperror('Invalid request');
	if ($myuserid!=$userid&&!$user['groups']['accounts']) apperror('Access denied');
	
	checkgskey('downloadgskeyfile_'.$userid);	

	$query="select * from users where userid=? and gsid=?";
	$rs=sql_prep($query,$db,array($userid,$gsid));
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Access denied');
		
	$fn=makeslug($myrow['login']).'.key';
	
	
	$keyfile=encstr($keyfile,time().$salt.md5($keyfile));

	$keyfilehash=sha1($dbsalt.$keyfile);
	
	$query="update users set keyfilehash=? where userid=? and gsid=? ";
	sql_prep($query,$db,array($keyfilehash,$userid,$gsid));

	header('Content-Type: application/octet-stream');
	header("Content-disposition: attachment; filename=$fn");
			
	echo $keyfile;
}