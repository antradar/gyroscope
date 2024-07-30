<?php

if (gyroscope_trace_traceable()){
	$gsdbprofile=array();
	$gsdbprofile_fulltrace=1;
}

function gyroscope_trace_traceable(){
	global $user;
	global $gyroscope_trace_req;

	$tracekey='gyroscope_trace_req';
	$req=cache_get($tracekey);
	$gyroscope_trace_req=$req;
		
	if (!isset($req)||!$req) return false;
		
	$tcmd=$req['cmd'];
	$tuserid=$req['userid']??null;
	$tgsid=$req['gsid']??null;
	
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	$cmd=$_GET['cmd'];
	
	if ($cmd!=$tcmd) return false;
	
	if (isset($tuserid)&&$userid!=$tuserid) return false;
	if (isset($tgsid)&&$gsid!=$tgsid) return false;
	
	cache_delete($tracekey);
	
	return true;
		
}

function gyroscope_trace_dump(){
	global $gsdbprofile;
	global $gyroscope_trace_req;
	global $gsdbprofile_fulltrace;
	
	if (!isset($gsdbprofile_fulltrace)||!$gsdbprofile_fulltrace) return;	
	if (!isset($gyroscope_trace_req)||!$gyroscope_trace_req) return;
	
	$cmd=$gyroscope_trace_req['cmd'];
	$gsid=$gyroscope_trace_req['gsid'];
	$userid=$gyroscope_trace_req['userid'];
		
	$fn='/opt/gyroscope_traces.txt';
	if (!file_exists($fn)) return;
	
	$f=fopen($fn,'at');
	fwrite($f,date('Y-n-j H:i:s')."\r\n");
	fwrite($f,"$cmd GS_$gsid User_$userid\r\n"); 
	fwrite($f,print_r($gsdbprofile,1));
	
	$dbcalls=array();
	foreach ($gsdbprofile as $dbcall){
		$key=md5($dbcall['query']).'-'.md5($dbcall['file']).'-'.$dbcall['line'];
		if (!isset($dbcalls[$key])) {
			$dbcalls[$key]=$dbcall;
			$dbcalls[$key]['time']=0;
			$dbcalls[$key]['count']=0;
		}
		$dbcalls[$key]['time']+=$dbcall['time'];	
		$dbcalls[$key]['count']++;
	}

	$dbcalls=array_values($dbcalls);
		
	uasort($dbcalls,function($a,$b){
		$ta=$a['time']; $tb=$b['time'];
		if ($ta===$tb) return 0; if ($ta>$tb) return 1; else return -1;
	});		
	
	
	fwrite($f,"\r\n-- by Time -------\r\n");
	fwrite($f,print_r($dbcalls,1)."\r\n");
	
	uasort($dbcalls,function($a,$b){
		$ta=$a['count']; $tb=$b['count'];
		if ($ta===$tb) return 0; if ($ta>$tb) return 1; else return -1;
	});		
	
	foreach ($dbcalls as $idx=>$dbcall){
		if ($dbcall['count']==1) unset($dbcalls[$idx]);	
	}
	//if ($dbcalls[count($dbcalls)-1]['count']>1){
		fwrite($f,"\r\n-- by Count -------\r\n");
		
		fwrite($f,print_r($dbcalls,1)."\r\n");
	//}
	
	
	fwrite($f,"\r\n=================\r\n\r\n");
	fclose($f);
	
}

