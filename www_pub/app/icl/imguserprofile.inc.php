<?php

function imguserprofile(){
	$user=userinfo();
	$userid=$user['userid']+0;
	
	$vendorhead='';
	if (TABLENAME_GSS!='gss') $vendorhead=TABLENAME_GSS.'_';
	
	$fn='../../protected/userpics/'.$vendorhead.$userid.'.png';
	
	$thumb=SGET('thumb');
	
	$mimetype='image/png';
	
	if (!file_exists($fn)) {
		$fn='imgs/profile.png';
		if ($thumb==1) {
			$fn='imgs/t.gif';
			$mimetype='image/gif';
		}
	}
	
	header('Content-Type: '.$mimetype);
	$c=file_get_contents($fn);
	echo $c;
	
	die();
}