<?php


function updategyroscope(){
	global $lang;
	global $viewcount;
	global $toolbaritems;
	global $userroles;

	$vs=explode('.',GYROSCOPE_VERSION);
	for ($i=0;$i<3;$i++) if (!isset($vs[$i])) $vs[$i]=0;
	
	$version=$vs[0]*1000+$vs[1]*100+$vs[2];	
?>
<div class="section">
<?		
	if (MOD_SERVER!=''){
		$req=array(
			'version'=>$version,
			'modkey'=>MOD_KEY,
			'userroles'=>$userroles,
			'viewcount'=>$viewcount,
			'toolbaritems'=>$toolbaritems
		);
		
		//echo '<pre>'; print_r($req); echo '</pre>';
		
		$url=MOD_SERVER.'?lang='.$lang.'&version='.$version.'&devmode='.$devmode.'&project='.urlencode(GYROSCOPE_PROJECT).'&vendor='.urlencode(VENDOR_NAME).'&vendorversion='.VENDOR_VERSION;
			
		$curl=curl_init($url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($req));
		
		$res=curl_exec($curl);
		curl_close($curl);
		
?>
	<div class="sectionheader">Modules</div>
	<?echo $res;?>
<?		
	}
		

	
	$devmode=($_SERVER['REMOTE_ADDR']=='127.0.0.1'||$_SERVER['REMOTE_ADDR']=='::1')?1:0;
	
	$gateway='https://www.antradar.com/gyroscope_updater.php';
	$url=$gateway.'?lang='.$lang.'&version='.$version.'&devmode='.$devmode.'&project='.urlencode(GYROSCOPE_PROJECT).'&vendor='.urlencode(VENDOR_NAME).'&vendorversion='.VENDOR_VERSION;
	
		
	$curl=curl_init($url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
	$res=curl_exec($curl);
	curl_close($curl);

?>
	<div class="sectionheader">Gyroscope Core <?echo $GYROSCOPE_VERSION;?></div>
	<?echo $res;?>
	<?
	if (trim($res)==''){
?>
<div class="warnbox">Cannot connect to version check server. This often means you are using an outdated SSL library that does not support TLS v1.2.</div>
<?		
	}
	?>
</div>
<?	
}
