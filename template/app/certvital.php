<?php
date_default_timezone_set('UTC');

//enter full paths to all the certificate files on the server

$files=array(
);

$certs=array();
foreach ($files as $fn){
	$cert=certinfo($fn);
	//echo '<pre>'; print_r($cert); echo '</pre>';
	array_push($certs,array(
		'fn'=>basename($fn),
		'domain'=>$cert['domain'],
		'alts'=>implode(',',$cert['alts']),
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

	//echo '<pre>'; print_r($cert); echo '</pre>';

	$domain=$cert['subject']['CN'];
	$exp=$cert['validTo_time_t'];

	$alts=array();

	$altnames=explode(',',$cert['extensions']['subjectAltName']);

	foreach ($altnames as $altname){
		$altname=trim(str_replace('DNS:','',$altname));
		if ($altname!=''&&$altname!=$domain) array_push($alts,$altname);
	}

	return array('domain'=>$domain,'exp'=>$exp,'alts'=>$alts);
	
}
