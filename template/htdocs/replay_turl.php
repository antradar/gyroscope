<?php

function test_check($res,$httpstatus){
	//return array(0,'debug; always fail'); //debug
	
	$sec=date('s');
	if ($sec<30) return array(0,'test fail at the first half of a second');
	
	return array(1,'');
	
}

function test_fail($t,$res,$errmsg){
	echo "End of custom fail func call. [$errmsg]\r\n";
	
}

function test_success($t,$res){
	echo "Success: ".strlen($res)."\r\n";	
}