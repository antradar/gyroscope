<?php

/*
CBOR Library based on Thomas Bleeker's implementation
https://github.com/madwizard-org/webauthn-server/tree/master/src/Format
MIT License

DER routines based on Lukas Buchs' implementation
https://github.com/lbuchs/WebAuthn/blob/master/src/Attestation/AuthenticatorData.php
MIT License

CBOR Debugger:
https://cbor.nemo157.com/

Rewritten by Schien Dong

*/


function cbor_decode($buf,&$offset=0){
		
	$first=cbor_getval($buf,$offset,'byte');
	$offset++;
	$type=$first>>5;
	$val=$first&0b11111;
					
	if ($type==7) return cbor_parse_float($val,$buf,$offset);
	
	$val=cbor_parse_extralength($val,$buf,$offset);
		
	return cbor_parse_itemdata($type,$val,$buf,$offset);
	
}

function cbor_parse_simple($val){
	if ($val==21) return true;
	if ($val==22) return null;
	if ($val==20) return false;
	cbor_error('Unsupported simple value '.$val);	
}

function cbor_parse_float($val,$buf,&$offset){
	switch ($val){
	case 24: $v=cbor_getval($buf,$offset,'byte'); $offset++; return cbor_parsesimple($v); break;
	case 25: $v=cbor_getval($buf,$offset,'halffloat'); $offset+=2; return $v; break;
	case 26: $v=cbor_getval($buf,$offset,'float'); $offset+=4; return $v; break;
	case 27: $v=cbor_getval($buf,$offset,'double'); $offset+=8; return $v; break;
	case 28: case 29: case 30: cbor_error('reserved value used.'); break;
	case 31: cbor_error('indefinite length not supported.'); break;
	default: cbor_error('unrecognized float value '.$val);
	}//switch
	
	return cbor_parse_simple($val);
}

function cbor_parse_extralength($val,$buf,&$offset){
	switch ($val){
	case 24: $v=cbor_getval($buf,$offset,'byte'); $offset++; return $v; break;
	case 25: $v=cbor_getval($buf,$offset,'uint16'); $offset+=2; return $v; break;
	case 26: $v=cbor_getval($buf,$offset,'uint32'); $offset+=4; return $v; break;
	case 27: $v=cbor_getval($buf,$offset,'uint64'); $offset+=8; return $v; break;
	case 28: case 29: case 30: cbor_error('reserved value used.'); break;
	case 31: cbor_error('indefinite length not supported.'); break;
	default: return $val;
	}//switch
}

function cbor_parse_itemdata($type,$val,$buf,&$offset){
	switch ($type){
	case 0: return $val; break; //uint
	case 1: return -1-$val;	break; //nint
	case 2: $bytes=base64_encode(substr($buf,$offset,$val)); $offset+=$val; return $bytes; break; //encoded binary string
	case 3: $bytes=substr($buf,$offset,$val); $offset+=$val; return $bytes; break; //string
	case 4: return cbor_parse_array($buf,$offset,$val); break; //array
	case 5: return cbor_parse_map($buf,$offset,$val); break; //map
	case 6: return cbor_decode($buf,$offset); break; //tag
	default: cbor_error('unrecognized major type '.$type);
	}
}

function cbor_parse_array($buf,&$offset,$val){
	$arr=array();
	for ($i=0;$i<$val;$i++){
		array_push($arr,cbor_decode($buf,$offset));
	}
	return $arr;
}

function cbor_parse_map($buf,&$offset,$val){
	$map=array();
	for ($i=0;$i<$val;$i++){
		$mapkey=cbor_decode($buf,$offset);
		$mapval=cbor_decode($buf,$offset);
		if ($mapkey=='authData') $mapval=cbor_parse_authdata(base64_decode($mapval));
		$map[$mapkey]=$mapval;	
	}
	return $map;
}

function cbor_parse_authdata($data){
	
	$jsoffset=0;
	if (strlen($data)<152) $jsoffset=152-strlen($data);
	
	
	$rpidhash=substr($data,0,32);
	$ds=unpack('Cflags',substr($data,32,1)); $rawflags=$ds['flags'];
	$flags=array(
		'userpresent'=>!!($rawflags&1),
		'userverified'=>!!($rawflags&4),
		'attested'=>!!($rawflags&64),
		'extension'=>!!($rawflags&128),
		'raw'=>$rawflags
	);
	
	$offset=37;
	$attestdata=array();
	
	if ($flags['attested']){//parse attest data
		$ds=unpack('nlength',substr($data,53-$jsoffset,2)); $attlength=$ds['length'];
				
		$credid=substr($data,55-$jsoffset,$attlength);
		$offset+=$attlength;
		//extract public key
		$pkeyoffset=55-$jsoffset+$attlength;
		$pkeyres=cbor_decode($data,$pkeyoffset);
		$offset=$pkeyoffset;
						
		$credkey=array(
			'kty'=>$pkeyres[1],
			'alg'=>$pkeyres[3]
		);
		
		switch ($pkeyres[3]){
		case -7:
			$credkey['crv']=$pkeyres[-1];
			$credkey['x']=$pkeyres[-2];
			$credkey['y']=$pkeyres[-3];
		break;
		case -257:
			$credkey['n']=$pkeyres[-1];
			$credkey['e']=$pkeyres[-2];
		break;	
		}
		
		$attestdata=array(
			'aaguid'=>substr($data,37,max(0,16-$jsoffset)),
			'length'=>$attlength,
			'credid'=>base64_encode($credid),
			'credkey'=>$credkey
		);
				
	}
	
	$ds=unpack('Nsigncount',substr($data,33,4)); $signcount=$ds['signcount'];
	
	$authdata=array(
		'rpidhash'=>base64_encode($rpidhash),
		'flags'=>$flags,
		'signcount'=>$signcount,
		'attestdata'=>$attestdata
	);
		
	return $authdata;	
}


function cbor_getval($buf,$offset,$mode='byte'){
	if ($offset>strlen($buf)) apperror('cbor buffer out of bound. incompatible browser?'); //cbor_error
	
	switch ($mode){
	case 'byte': return ord(substr($buf,$offset,1)); break;
	case 'uint16': $ds=unpack('n',$buf,$offset); return $ds[1]; break;
	case 'uint32': $ds=unpack('N',$buf,$offset); return $ds[1]; break;
	case 'uint64': $ds=unpack('J',$buf,$offset); return $ds[1]; break;
	case 'float': $ds=unpack('G',$buf,$offset); return $ds[1]; break;
	case 'double': $ds=unpack('E',$buf,$offset); return $ds[1]; break;
	case 'halffloat':
		$half=cbor_getval($buf,$offset,'uint16');
		$exp=($half>>10)&0x1f;
		$mant=$half&0x3ff;
		
		if ($exp==0){
			$val=$mant*(2**-24);
		} else {
			if ($exp!=31) $val=($mant+1024)*(2**($exp-25));
			else $val=($mant===0)?INF:NAN;
		}
		return ($half&0x8000)?-1*$val:$val;
	break;
	}//switch
}

function cbor_der_length($len) {
	if ($len < 128) return chr($len);

	$bytes = '';
	while ($len > 0) {
		$bytes = chr($len % 256).$bytes;
		$len = intdiv($len, 256);
	}
	return chr(0x80|strlen($bytes)).$bytes;
}

function cbor_der_sequence($data){
	return "\x30".cbor_der_length(strlen($data)).$data;
}

function cbor_der_bitstring($bytes){
	return "\x03" . cbor_der_length(strlen($bytes) + 1)."\x00".$bytes;	
}

function cbor_der_oid($encoded){
	return "\x06".cbor_der_length(strlen($encoded)).$encoded;
}

function cbor_der_uint($bytes) {
	$len=strlen($bytes);
	
	for ($i=0;$i<($len-1);$i++) {
		if (ord($bytes[$i])!==0) break;
	}
	if ($i !== 0) $bytes=substr($bytes, $i);
	
	if ((ord($bytes[0])&0x80)!==0) $bytes="\x00".$bytes;
	
	return "\x02".cbor_der_length(strlen($bytes)).$bytes;
}
    
function cbor_validate($kty,$alg,$crv,$x,$y,$n,$e,$clientdata,$clientauth,$signature,$skipverify=0,$lastsigncount=0,&$newsigncount=0,&$err=''){

	
	$authobj=cbor_parse_authdata(base64_decode($clientauth));
		
	switch ($kty){
	case 2: //ec2
		$cert=cbor_der_sequence(
		    cbor_der_sequence(
		        cbor_der_oid("\x2A\x86\x48\xCE\x3D\x02\x01"). // OID 1.2.840.10045.2.1 ecPublicKey
		        cbor_der_oid("\x2A\x86\x48\xCE\x3D\x03\x01\x07")  // 1.2.840.10045.3.1.7 prime256v1
		    ).
		    cbor_der_bitstring(
		    	"\x04".base64_decode($x).base64_decode($y) //U2F
		    )
		);	
	break;
	case 3: //rsa
		$cert=cbor_der_sequence(
			cbor_der_sequence(
				cbor_der_oid("\x2A\x86\x48\x86\xF7\x0D\x01\x01\x01") . // OID 1.2.840.113549.1.1.1 rsaEncryption
				"\x05\x00" //null
			).
			cbor_der_bitstring(
				cbor_der_sequence(
					cbor_der_uint(base64_decode($n)).
					cbor_der_uint(base64_decode($e))
				)
			)
		);
	
	break;	
	}
	$pem="-----BEGIN PUBLIC KEY-----\n";
	$pem.=chunk_split(base64_encode($cert),64,"\n");
	$pem.="-----END PUBLIC KEY-----\n";
	
	//echo '<pre>'; print_r($pem); echo '</pre>';
	
	$pubkey=openssl_pkey_get_public($pem);

	$clienthash=hash('sha256',$clientdata,true);

	$res=openssl_verify(base64_decode($clientauth).$clienthash,base64_decode($signature),$pubkey,OPENSSL_ALGO_SHA256);
	if ($res){//check more
		if (!$authobj['flags']['userpresent']) {$res=0;$err='user not present';}
		if (!$skipverify&&!$authobj['flags']['userverified']) {$res=0;$err='user not verified';}
		if (is_numeric($authobj['signcount'])){
			
			if ($authobj['signcount']<=$lastsigncount) {$res=0;$err='invalid sign count';}
			else $newsigncount=$authobj['signcount'];
		}
		
	}
	return $res;
}


function cbor_error($msg){
	throw new ErrorException($msg);	
}







