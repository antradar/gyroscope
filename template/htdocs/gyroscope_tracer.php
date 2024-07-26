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
	fwrite($f,"\r\n=================\r\n\r\n");
	fclose($f);

}

