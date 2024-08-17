<?php

include 'qr/qrlib.php'; 

function imgqrcode(){
	
	$data=SGET('data')??'';	
	if (isset($_SERVER['HTTP_SENDBLOB'])&&$_SERVER['HTTP_SENDBLOB']=='1') $data=SQET('data');
	
	header('Content-Type: image/png');
	QRcode::png($data, null, 'M', 8, 2);
	
}