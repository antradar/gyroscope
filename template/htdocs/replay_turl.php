<?php

function test_check($res,$httpstatus){
	$len=strlen($res); //1540	
	if ($len>11540) return array(1,'');//okay
	return array(0,'too short');
	
}

function test_fail($t,$res,$errmsg){
	print_r($t);
	echo "End of custom fail func call. [$errmsg]\r\n";
	
}