<?php

function passtest($pass){
	
	global $_SERVER;
	
	$netpass='';
	$low=null;
	$high=null;
	
	for ($i=0;$i<strlen($pass);$i++){
		if (!isset($low)||$low>ord($pass[$i])) $low=ord($pass[$i]);
		if (!isset($high)||$high<ord($pass[$i])) $high=ord($pass[$i]);
				
		if ($i==0) $netpass.=$pass[$i];
		else {
			if (abs(ord($pass[$i])-ord($pass[$i-1]))>1) $netpass.=$pass[$i];
		}	
	}
	
	$netlen=ceil(strlen($netpass)/2);
	
	if ($netlen>8) $netlen=8;
	
	$base=ceil(($high-$low)/2);
	
	$strength=pow($base,$netlen);
	
	if ($strength<=1) $rawgrade=0; else $rawgrade=log($strength);
	
	$grade=floor($rawgrade/10);
	
	if ($grade>2) $grade=2; //0 - weak, 1 - okay, 2- strong
	
	//echo $strength.' '.$rawgrade.' '.$grade; return;
	
	//return array('grade'=>1,'found'=>0); //uncomment to fully bypass
	
	//return array('grade'=>$grade,'found'=>0); //uncomment to bypass lookup
		
	$hash=sha1($pass);
	$seed=substr($hash,0,5);
	
	$url="https://api.pwnedpasswords.com/range/$seed"; //production
	
	if ($_SERVER['REMOTE_ADDR']=='127.0.0.1'||$_SERVER['REMOTE_ADDR']=='::1') $url="http://www.antradar.com/pwned.php?range=$seed";
	
	$curl=curl_init($url);

	curl_setopt($curl,CURLOPT_VERBOSE,0);
		
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);

	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,1);
	
	$res=curl_exec($curl);
	
	$found=0;
	
	$lines=explode("\n",$res);
	foreach ($lines as $line){
		$parts=explode(':',$line);
		if ($seed.strtolower($parts[0])==$hash){
			$found=intval($parts[1]);
			break;	
		}	
	}
	
	if ($found>1) $grade=0;
	
	return array('grade'=>$grade,'found'=>$found);
	
		
}



