<?php

function sendsms($cell,$message,$ref='',$fromnumber=null){
	//write your own implementation here
	
	global $smsuser; //defined in connect.php
	global $smskey; //defined in connect.php
	
	if (!isset($smskey)||$smskey=='') return;
	
	$omessage=$message;	
	$message=urlencode($message);
	
	$req=array(
		'MessageBody'=>$omessage,
		'Reference'=>$ref,
	);	
	
	$url="https://secure.smsgateway.ca/services/message.svc/$smskey/$cell/Extended";
	if (isset($fromnumber)) {
		$url="https://secure.smsgateway.ca/services/message.svc/$smskey/$cell/ViaDedicated";
		$req['SenderNumber']=$fromnumber;
	}
	
	$usetextmagic=0;
	
	//https://https://my.textmagic.com/
	if ($usetextmagic) $url='https://rest.textmagic.com/api/v2/messages';
		
	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));	
	curl_setopt($curl,CURLOPT_POST,1);
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($req));	
	
	if ($usetextmagic){
		//TextMagic
		
		curl_setopt($curl,CURLOPT_HTTPHEADER,array('X-TM-Username: '.$smsuser,'X-TM-Key: '.$smskey));	
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,"text=$message&phones=$cell");
		
		////
	}
	
	
	$res=curl_exec($curl);
		
	return $res;
}

