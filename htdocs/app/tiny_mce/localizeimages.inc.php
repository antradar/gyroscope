<?php

function localizeimages($blogtext){
	global $blogpath;
			
	preg_match_all('/<img [\S\s]*?src="([\S\s]+?)"/i',$blogtext,$matches);
	$imgs=$matches[1];

	foreach ($imgs as $img){
		
		if (preg_match('/data\:\S+\;base64\,([\S\s]+)$/i',$img,$parts)){
			$res=base64_decode($parts[1]);
			$image=imagecreatefromstring($res);
			
			$imgkey=md5($res);
			$fn='../'.$blogpath.$imgkey;
			imagepng($image,$fn);
			imagedestroy($image);			
			
			$blogtext=str_replace($img,'../'.$blogpath.$imgkey,$blogtext);			
		
		}//data
		
		if (preg_match('/https?:\/\//',$img)){
		
			$curl=curl_init($img);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			
			
			$res=curl_exec($curl);
	
			$imgkey=md5($res);
					
			$fn='../'.$blogpath.$imgkey;
						
			$f=fopen($fn,'wb');
			fwrite($f,$res);
			fclose($f);
			
			$blogtext=str_replace($img,'../'.$blogpath.$imgkey,$blogtext);
		}
		
	}
	
	return $blogtext;	
}

