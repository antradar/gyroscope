<?php

function calcgapins($gakey){
	
	$pins=array();
	
	$now=time()-120;
	$then=time()+120;
	
	for ($i=$now;$i<$then;$i+=30){
		$message=floor($i/30);
		$hmessage=dechex($message);
		$hmessage=str_pad($hmessage,16,'0',STR_PAD_LEFT);
		
		$bytes=array();
		foreach(str_split($hmessage,2) as $hex){
		    array_push($bytes,chr(hexdec($hex)));
		}
		
		$dmessage=implode('',$bytes);		
		
		$hash = hash_hmac('sha1',$dmessage,$gakey);
				
		$digits=array();
		foreach(str_split($hash,2) as $hex){
		    array_push($digits,hexdec($hex));
		}
		
		$offset=$digits[19]&0xf;
				
		$truncated=(
			(($digits[$offset+0] & 0x7f) << 24 ) |
			(($digits[$offset+1] & 0xff) << 16 ) |
			(($digits[$offset+2] & 0xff) << 8 ) |
			($digits[$offset+3] & 0xff)
		) % pow(10,6); //6 digits
		
		$pin=str_pad($truncated,6,'0',STR_PAD_LEFT);
		array_push($pins,$pin);
	}//for
		
	return $pins;

}
