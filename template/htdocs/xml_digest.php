<?php

function xml_digest($raw,$killtags=array(),$watchtags=array()){
	$data=$raw;
	if (count($killtags)>0) $data=str_replace($killtags,'',$raw);
	$data=trim($data);	
		
	$obj=null;
	$errors=array();
	$tokens=array();
	$keytokens=array();
	$xml=null;
	
	try{
		$xml=@ new SimpleXMLElement($data);
		
	} catch (Exception $e) {
	 	$xml=null;
	}

	if (isset($xml)){
		$obj=json_decode(json_encode($xml),1);
		xml_digest_get_tokens($obj,$tokens,$keytokens,$watchtags);
	}	
	
	$tokens=array_values($tokens);
	
	$strtokens=':'.implode('|',$tokens);
	
	/*
	print_r($keytokens);
	
	echo "\r\n".$strtokens."\r\n"; 
	echo strlen($raw).', '.strlen($strtokens).', '.(strlen($strtokens)/strlen($raw))."\r\n";
	die();
	*/
	
	//print_r($tokens); die();

	
	//print_r($obj); die();
	
	return array($obj,$strtokens,array(),$keytokens);
}

function xml_digest_get_tokens($obj,&$tokens,&$keytokens,$watchtags=array()){
	foreach ($obj as $k=>$v){
		$token=strtolower(trim($k));
		if (is_numeric($token)) continue;
		
		if ($token!=''&&in_array($token,$watchtags)) $tokens[$token]=$token;
		if ($token!='') $keytokens[$token]=$token;
		
		if ($v=='') continue;
		if (!is_array($v)){
			
			
			//simple version, keeping the entire phrase
			$token=trim(strtolower($v));
			$tokens[md5($token)]=$token;
			
			
			/*
			//scan "pure" tokens. rely on multiMatchAllIndices in ClickHouse
			$v=str_replace(array(',','.','-','?','*','/','$'),' ',$v);
			$parts=explode(' ',$v);
			foreach ($parts as $part){
				$token=trim(strtolower($part));
				if ($token!='') $tokens[$token]=$token;	
			}
			*/
		
			continue;	
		}
		
		if (is_array($v)) xml_digest_get_tokens($v,$tokens,$keytokens,$watchtags);
	}
}


