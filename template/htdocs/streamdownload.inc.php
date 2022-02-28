<?php

function streamdownload($fn,$mimetype=null,$ofn=null){
	clearstatcache();

	if (!isset($mimetype)){
		if (!isset($ofn)) die('one of the mimetype and ofn must be supplied');
		header('Content-Type: application/octet-stream');
		header("Content-disposition: attachment; filename=$ofn");		
	} else {
		header('Content-Type: '.$mimetype);
		if (isset($ofn)) header("Content-disposition: inline; filename=$ofn");				
	}

	if (!file_exists($fn)) die('invalid file');
	$buffersize=1024*512;
	$f=fopen($fn,'rb');
	while (!feof($f)){
		print(@fread($f,$buffersize));
		ob_flush();
		flush();
	}
	
	fclose($f);
	
	die();
	
}

