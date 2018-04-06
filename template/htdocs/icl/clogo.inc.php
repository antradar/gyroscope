<?php

function clogo(){
	$user=userinfo();
	$gsid=$user['gsid']+0;
	$fn='../../protected/clogos/'.$gsid.'.gif';
	if (!file_exists($fn)) $fn='../../protected/clogos/default.gif';
	header('Content-Type: image/gif');
	echo file_get_contents($fn);	
}