<?php

include 'qr/qrlib.php'; 

function imgqrcode(){
	$data=SGET('data');
	
	header('Content-Type: image/png');
	QRcode::png($data, null, 'M', 8, 2);
}