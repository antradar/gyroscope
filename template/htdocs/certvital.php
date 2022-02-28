<?php
/*
This web resource is remotely called by utils/certscan.php
*/
date_default_timezone_set('UTC');

//enter full paths to all the certificate files on the server
$files=array(

);

$certs=array();
foreach ($files as $fn){
	$cert=certinfo($fn);
	array_push($certs,array(
		'fn'=>basename($fn),
		'domain'=>$cert['domain'],
		'exp'=>$cert['exp'],
		'valid'=>$cert['exp']<time()?0:1,
		'dexp'=>date('Y-n-j',$cert['exp']),
		'dvalid'=>$cert['exp']<time()?'EXPIRED':'OK'
	));
}

//echo '<pre>'; print_r($certs); echo '</pre>';
echo json_encode($certs);

function certinfo($fn){
	$certdata=file_get_contents($fn);

	$cert=openssl_x509_parse($certdata);

	$domain=$cert['subject']['CN'];
	$exp=$cert['validTo_time_t'];

	return array('domain'=>$domain,'exp'=>$exp);
	
}
