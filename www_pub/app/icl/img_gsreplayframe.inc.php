<?php

include 'libnumfile.php';

function img_gsreplayframe(){
	$frameid=GETVAL('frameid');
	global $db;
	
	$rs=gsguard($frameid,array('gsreplays','gsreplayframes'),array('gsreplayid-gsreplayid','frameid'),'gsreplays.gsreplayid');

	$gsreplayid=$rs['gsreplayid'];
	
	$basedir='../../protected/gsreplays/';
	$ext='.gsreplay_'.$gsreplayid.'.png';
	
	header('Content-Type: image/png');
	numfile_stream_contents($frameid,$ext,$basedir);				
		
}
