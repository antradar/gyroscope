<?php

if (!function_exists('getallheaders')){
	function getallheaders(){
		$headers=array();
		foreach ($_SERVER as $k=>$v){
			if (substr($k,0,5)=='HTTP_'){
				$dk=str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
				$headers[$dk]=$v;
			}
		}
		return $headers;
	}
}
