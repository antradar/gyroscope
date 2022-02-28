<?php

function clogo(){
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';
	
	$fn='../../protected/clogos/'.$vendorhead.$gsid.'.gif';
	
	if (!file_exists($fn)) $fn='../../protected/clogos/'.$vendorhead.'default.gif';
	header('Content-Type: image/gif');
	echo file_get_contents($fn);	
}