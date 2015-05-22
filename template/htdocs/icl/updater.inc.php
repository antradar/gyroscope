<?php


function updategyroscope(){
		
	$vs=explode('.',GYROSCOPE_VERSION);
	$version=$vs[0]*1000+$vs[1]*100+$vs[2];
	
	$devmode=$_SERVER['REMOTE_ADDR']=='127.0.0.1'?1:0;
	
	$gateway='https://www.antradar.com/gyroscope_updater.php';
	$url=$gateway.'?version='.$version.'&devmode='.$devmode.'&project='.urlencode(GYROSCOPE_PROJECT).'&vendor='.urlencode(VENDOR_NAME).'&vendorversion='.VENDOR_VERSION;
	
		
	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	$res=curl_exec($curl);
		
	echo '<div class="section">'.$res.'</div>';
}
