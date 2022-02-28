<?php
include 'libcbor.php';

function testyubikey(){
	
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];

	$attid=SQET('id');
	$clientdata=SQET('clientdata');
	$signature=strtr(SQET('signature'),' ','+');
	$clientauth=strtr(SQET('auth'),' ','+');
	
	//$clientobj=json_decode($clientdata,1);
	//echo '<pre>'; print_r($clientobj); echo '</pre>';
	
	$query="select * from ".TABLENAME_YUBIKEYS." where userid=? and attid=?";
	$rs=sql_prep($query,$db,array($userid,$attid));
	if (!$myrow=sql_fetch_assoc($rs)){
		echo "Cannot find a key in the registry.";
		return;	
	}

	$keyid=$myrow['keyid'];
	$keyname=$myrow['keyname'];
	$kty=$myrow['kty'];
	$alg=$myrow['alg'];
	$crv=$myrow['crv']; $x=$myrow['x']; $y=$myrow['y'];
	$n=$myrow['n']; $e=$myrow['e'];
	
	$lastsigncount=$myrow['lastsigncount'];
	
	
	echo "Found matching device ".htmlspecialchars($keyname)."<br><br>";

	$newsigncount=0;
	$err='';
	$res=cbor_validate($kty,$alg,$crv,$x,$y,$n,$e,$clientdata,$clientauth,$signature,1,$lastsigncount,$newsigncount,$err);
	
	if ($res==1) {
		echo "Validated! =)";
		$query="update ".TABLENAME_YUBIKEYS." set lastsigncount=? where keyid=?";
		sql_prep($query,$db,array($newsigncount,$keyid));		
	} else echo "Authentication failed. =( ".$err;	
	
	
}
