<?php

/*
turl - a curl wrapper for outbound logging

 //remember to set app-specific timeouts
 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
 
*/

function turl_exec($curl,$extra=''){
	global $db;
	global $vdb;
	
	if (!isset($db)||!isset($vdb)) return curl_exec($curl);
		
	$res=curl_exec($curl); //this could take some time

	$after=curl_getinfo($curl);
	
	//print_r($after);
	
	$nettime=intval($after['total_time']*1000);
	$srvtime=intval(($after['total_time']-$after['pretransfer_time'])*1000);
	$netsize=intval($after['size_download']);
	
	$err=curl_errno($curl);
	
	$rawurl=$after['url'];
	
	$urlinfo=parse_url($rawurl);
	$baseurl=$urlinfo['path'];
	$host=$urlinfo['host'];
	$params=$urlinfo['query'];
	
	$gsfunc='';
	
	$ip=$after['primary_ip'];
		
	$httpstatus=intval($after['http_code']);
	if ($err!=0) $httpstatus=5000+$err;
	
	//echo "$baseurl $nettime $srvtime $netsize Status_$httpstatus\r\n"; return;
	
	$now=time();
	
	$query="insert into accesslogseq() values ()";
	$rs=sql_prep($query,$db);
	$logid=sql_insert_id($db,$rs);
	if ($logid>0){
		$query="insert into accesslog(
		logsitename,logid,ip,logdate,
		method,baseurl,gsfunc,
		httpstatus,netsize,nettime,srvtime,logextra
		) values (
		?,?,?,?,
		?,?,?,
		?,?,?,?,?
		)";
		
		vsql_prep($query,$vdb,array(
		$host,$logid,$ip,$now,
		'CURL',$baseurl,$gsfunc,
		$httpstatus,$netsize,$nettime,$srvtime,$extra
		),1);	
	}
		
	//log the aftermath
	
	return $res;
		
}

/*
include 'connect.php';


$curl=curl_init('http://www.antradar.com/tools/wait.php?s=1&hb='.time());

curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_POST,1);
curl_setopt($curl,CURLOPT_POSTFIELDS,'test');
curl_setopt($curl, CURLOPT_TIMEOUT, 2);


echo turl_exec($curl);
*/