<?php

include_once 'libnumfile.php';

function img_gsreplayframe($ctx=null){
	$frameid=GETVAL('frameid',$ctx);
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$rs=gsguard($ctx, $frameid,array('gsreplays','gsreplayframes'),array('gsreplayid-gsreplayid','frameid'),'gsreplays.gsreplayid');

	$gsreplayid=$rs['gsreplayid'];
	
	$basedir='../../protected/gsreplays/';
	$ext=$frameid.'.png';
	
	gs_header($ctx, 'Content-Type', 'image/png');
	numfile_stream_contents($gsreplayid,$ext,$basedir);				
		
}
