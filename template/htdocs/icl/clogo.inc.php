<?php
include 'libnumfile.php';

function clogo(){
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';
	
	$basedir=numfile_make_path($gsid,'../../protected/clogos/').'/';
	$fn=$basedir.$vendorhead.$gsid.'.gif';
		
	if (!file_exists($fn)) $fn='../../protected/clogos/'.$vendorhead.'default.gif';

	header('Content-Type: image/gif');
	echo file_get_contents($fn);	
}