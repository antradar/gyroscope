<?php

function makeslug($url){
	$url=str_replace("'",'',$url);
	$url=strtolower(preg_replace('/[^a-z\d ]/i','-',$url));
	$url=str_replace(' ','-',$url);
	$url=preg_replace('/-+/','-',$url);
	$url=trim($url,'-');
	return $url;	
}

