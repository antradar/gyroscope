<?php
date_default_timezone_set('UTC');

//declare a list of certvital locations

$links=array(

);

$certs=array();

foreach ($links as $link){
	$urlparts=parse_url($link);
	$host=$urlparts['host'];
	echo "> $host\r\n";
	$rcerts=wget($link);
	if ($rcerts=='') echo "Error contacting $link\r\n";
	foreach ($rcerts as $cert) {
		$cert['host']=$host;
		array_push($certs,$cert);
	}
}

usort($certs,function($a,$b){
	$va=$a['exp'];
	$vb=$b['exp'];
	if ($va==$vb) return 0;
	if ($va<$vb) return -1; else return 1;
});

echo "\r\n";

foreach ($certs as $cert){
	echo ' '.($cert['valid']?'-':'x');
	echo '  '.date('Y-m-d',$cert['exp']).'  '.$cert['domain'].' ('.$cert['fn'].' @'.$cert['host'].')'."\r\n";
}

echo "\r\n";

function wget($url){
	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	$res=curl_exec($curl);
	$obj=json_decode($res,1);
	return $obj;
}
