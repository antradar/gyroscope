<?php
/*
TCP-Based Multi-Threading Functions for Gyroscope
*/



function gsx_begin(){
	global $gsx_reg;
	$gsx_reg=array();
	
	ob_start();
}

function gsx_exec($method,$args,...$params){
	global $GSX_ENABLED;
	global $gsx_reg;
		
	
	if (!$GSX_ENABLED){ //invoke location function directly
		
		$func=new ReflectionFunction($method);
		$func->invokeArgs($params);
			
		return;	
	}
	
	if (!$gsx_reg[$method]) $gsx_reg[$method]=array();
	
	$idx=count($gsx_reg[$method]);
	
	$callkey=$method.'-'.$idx.'-rand'.time();
	
	$gsx_reg[$method][$callkey]=array('args'=>$args,'params'=>$params,'content'=>'GSX_UNRESOLVED #'.$idx.' ');
	
	echo "GSX_PLUG_".$callkey;
	
}


function gsx_return($obj){

	global $GSX_ENABLED;
	if (!$GSX_ENABLED) return;
	
	echo 'GSX_RETVAL_BEGIN';
	echo json_encode($obj);
	echo 'GSX_RETVAL_END';
			
}

function gsx_end($custom_servers=null){
	
	global $gsx_reg;
	global $GSX_SERVERS;
	global $GSX_ENABLED;

	$c=ob_get_clean();
	
	if (!$GSX_ENABLED) {echo $c; return;}
			
	
	$servers=$GSX_SERVERS;
	if (isset($custom_servers)) $servers=$custom_servers;	
		
	
	/// prep the calls ////
	
	global $_COOKIE;
	
	global $gsxkey;
	
	$cookies=array();
	
	foreach ($_COOKIE as $k=>$v) array_push($cookies,$k.'='.rawurlencode($v));
	
	$headers=array(
		'User_Agent: GSX',
		'GSXIP: '.$_SERVER['REMOTE_ADDR'],
		'Cookie: '.implode(';',$cookies)
	);
	
	$curls=array();
	
	$gets=array();
	foreach ($_GET as $k=>$v) if (!in_array($k,array('cmd','hb'))) {
		if (is_array($v)) {
			foreach ($v as $sk=>$item) array_push($gets,$k.'['.$sk.']='.rawurlencode($item));
			
		} else {
			array_push($gets,$k.'='.rawurlencode($v));
		}
	}	
	
	array_push($gets,'hb='.time());
	
	$mh = curl_multi_init();
	
	foreach ($gsx_reg as $method=>$calls){
		
		$headers['GSXAUTH']='GSXAUTH: '.md5($gsxkey.'-'.$method.'-'.date('Y-n-j-H'));
		
		$serveridx=0;
		
		foreach ($calls as $k=>$v){
			
			$gateway=$servers[$serveridx%count($servers)];
			
			if (stripos($gateway,'?')!==false) $url=$gateway.'&cmd='.$method.'&'.implode('&',$gets);
			else $url=$gateway.'?cmd='.$method.'&'.implode('&',$gets);
						
			$curl=curl_init($url);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			
			$data=array();
			foreach ($v['args'] as $idx=>$arg){
				array_push($data,$arg.'='.rawurlencode($v['params'][$idx]));	
			}
			
			curl_setopt($curl,CURLOPT_POSTFIELDS,implode('&',$data));
			
			// $res=curl_exec($curl); //defer this to async calls			
			// $gsx_reg[$method][$k]['content']=$res;
			
			$gsx_reg[$method][$k]['curl']=$curl;			
			curl_multi_add_handle($mh,$curl);
			
			$serveridx++;
		}
	}
	
	
	
	/// start blending ////
	
	do {
	    curl_multi_exec($mh, $running);
	    curl_multi_select($mh);
	} while ($running > 0);


	$retvals=array();
	
	foreach ($gsx_reg as $method=>$calls){
		foreach ($calls as $k=>$v) {
			$res=curl_multi_getcontent($v['curl']);
			
			if (preg_match('/GSX_RETVAL_BEGIN([\S\s]+?)GSX_RETVAL_END/',$res,$matches)){
				$retval=json_decode($matches[1],1);
				$res=preg_replace('/GSX_RETVAL_BEGIN([\S\s]+?)GSX_RETVAL_END/','',$res);
				array_push($retvals,$retval);
			}
			
			$c=str_replace('GSX_PLUG_'.$k,$res,$c);
			curl_multi_remove_handle($curls[$idx]);
		}	
	}
	
	curl_multi_close($mh);
	
	/// blending ends ////
		
	echo $c;
	
		
	//echo '<pre>'; print_r($gsx_reg); echo '</pre>';
	
	$gsx_reg=null;
	
	return $retvals;
	
}







