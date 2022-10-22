<?php

function _ssh2_connect($server,$port,$methods=null,$callbacks=null){
	$errors=array();
	set_error_handler(function($en,$msg,$file,$line) use (&$errors){
		error_log($msg);
		array_push($errors,$msg);
	},E_ALL);
	if (!is_callable('ssh2_connect')){
		array_push($errors,'missing PHP extension ssh2');
	} else {
		$ssh=ssh2_connect($server,$port,$methods,$callbacks);
	}
	restore_error_handler();
	return array($ssh,$errors);
}

