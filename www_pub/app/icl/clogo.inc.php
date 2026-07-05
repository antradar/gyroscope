<?php
include 'libnumfile.php';

function clogo($ctx=null){
	$user=userinfo($ctx);
	$gsid=$user['gsid'];

	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';

	$basedir=numfile_make_path($gsid,'../../protected/clogos/').'/';

	// Compute stem (path without extension)
	$stem=$basedir.$vendorhead.$gsid;

	gs_header($ctx, 'lfn', str_replace('../../protected/clogos/','',$stem.'.gif'));

	// Resolve to actual file, falling back to default
	if (file_exists($stem.'.gif')){
		$fn=$stem.'.gif';
	} else {
		$fn='../../protected/clogos/'.$vendorhead.'default.gif';
	}

	// Test sqr variant at the last moment
	$sqr=isset($_GET['mode'])&&$_GET['mode']==='sqr';
	if ($sqr&&file_exists($stem.'.sqr.gif')){
		$fn=$stem.'.sqr.gif';
	} elseif ($sqr){
		// No custom sqr, try default sqr then fall back to whatever we have
		$sqr_default='../../protected/clogos/'.$vendorhead.'default.sqr.gif';
		if (file_exists($sqr_default)) $fn=$sqr_default;
	}

	gs_header($ctx,'Content-Type', 'image/gif');
	echo file_get_contents($fn);
}