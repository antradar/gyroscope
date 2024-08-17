<?php

/*
turl - a curl wrapper for outbound logging

 //remember to set app-specific timeouts
 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
 
*/
function turl_init($url){
	$curl=curl_init($url);
	return array('type'=>'turlobj','url'=>$url,'curl'=>$curl,'opts'=>array());	
}

function turl_setopt(&$turl,$flag,$val){
	$turl['opts'][$flag]=$val;
	curl_setopt($turl['curl'],$flag,$val);	
}


function turl_exec($turl,$extra='',$checkfunc=null,$failfunc=null,$successfunc=null,$attempts=null){
	global $db;
	global $vdb;
	
	if (is_array($turl)&&$turl['type']=='turlobj'){
		$opts=$turl['opts'];
		$curl=$turl['curl'];	
		
	} else $curl=$turl;
	
	if (!isset($db)) return curl_exec($curl);
		
	$res=curl_exec($curl); //this could take some time

	$after=curl_getinfo($curl);
	
	//print_r($after);
	
	$nettime=intval($after['total_time']*1000);
	$srvtime=intval(($after['total_time']-$after['pretransfer_time'])*1000);
	$netsize=intval($after['size_download']);
	
	$err=curl_errno($curl);
	$errmsg='';
	
	$rawurl=$after['url'];
	
	$urlinfo=parse_url($rawurl);
	$baseurl=$urlinfo['path'];
	$host=$urlinfo['host'];
	$params=$urlinfo['query']??'';
	
	$gsfunc='';
	if (is_array($extra)){
		$gsfunc=$extra['gsfunc'];
		$extra=$extra['extra'];	
	}
	
	$ip=$after['primary_ip'];
		
	$httpstatus=intval($after['http_code']);
	if ($err!=0) $httpstatus=5000+$err;
	
	$now=time();	
	
	if ($err==0&&isset($checkfunc)&&is_callable($checkfunc)) {
		list($checkokay,$errmsg)=$checkfunc($res,$httpstatus);
		if (!$checkokay){
			$err=6000;
			$httpstatus=6000;
		}
	}
	
	//by default, http errors do not trigger a retry
	//checkfunc can change this behavior
		
	if ($err!=0){//default fail processing
		$defattempts=array(15*60, 60*60, 90*60);
		if (!is_array($attempts)) $attempts=$defattempts;
		$attempt=intval($turl['attempt']);
		$nextattempt=$attempts[$attempt];
		
		$turl['extra']=$extra;
		$turl['checkfunc']=$checkfunc;
		$turl['failfunc']=$failfunc;
		$turl['successfunc']=$successfunc;
		
		$dbturl=$turl;
		unset($dbturl['url']);
		unset($dbturl['curl']);
		unset($dbturl['type']);		
		$dbturl['attempts']=$attempts;
		
		if (is_numeric($nextattempt)){
			//schedule the next attempt
			
			$query="insert into turlq(turlurl,turldate,turlattempt,turlnext,turlerr,turlopts) values (?,?,?,?,?,?)";
			sql_prep($query,$db,array($turl['url'],$now,$attempt+1,$now+$nextattempt,$errmsg,json_encode($dbturl)));	
		} else {
			//final attempt
			$query="insert into turlq(turlurl,turldate,turlattempt,turlnext,turlerr,turlopts,finalattempt) values (?,?,?,?,?,?,?)";
			sql_prep($query,$db,array($turl['url'],$now,$attempt+1,$now+$nextattempt,$errmsg,json_encode($dbturl),1));	
		}
		
		if (isset($failfunc)&&is_callable($failfunc)){
			$failfunc($turl,$res,$errmsg); //additional fail processing
		}
		
	} else {
		if (isset($successfunc)){
			if (is_callable($successfunc)) $successfunc($turl,$res);
			else echo "Warning: not callable [$successfunc]\r\n";
		}	
	}
	
	//echo "$baseurl $nettime $srvtime $netsize Status_$httpstatus\r\n"; return;
	

	if (isset($db)&&isset($vdb)){
	
		$query="insert into accesslogseq() values ()";
		$rs=sql_prep($query,$db);
		$logid=sql_insert_id($db,$rs);
		
		if ($logid%5000==0) {
			$query="delete from accesslogseq where logid<?";
			sql_prep($query,$logid);
		}
		
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
		
		
	}
	
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