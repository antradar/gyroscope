<?php
include 'libnumfile.php';

function clogo($ctx=null){
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';
	
	$basedir=numfile_make_path($gsid,'../../protected/clogos/').'/';
	$fn=$basedir.$vendorhead.$gsid.'.gif';

	gs_header($ctx, 'lfn', str_replace('../../protected/clogos/','',$fn));
			
	if (!file_exists($fn)) $fn='../../protected/clogos/'.$vendorhead.'default.gif';

	gs_header($ctx,'Content-Type', 'image/gif');
	echo file_get_contents($fn);	
}