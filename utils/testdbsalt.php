<?php

if ($argc<2){
	echo "Usage: php testdbsalt.php [hash or auth.php]\r\n";
	die();	
}

include 'bcrypt.php';

if (is_callable('password_hash_fallbackmode')){
	echo "Warning: running in legacy mode\r\n";	
}

$dbsalt=$argv[1];

if (preg_match('/\.php$/',$dbsalt)){
	$fn=$dbsalt;
	if (!file_exists($fn)){
		die("Cannot open source file $fn\r\n");	
	}
	
	$c=file_get_contents($fn);
	if (!preg_match_all('/(\S*)\s*\$dbsalt\s*=\s*([\S\s]+?);/',$c,$matches)){
		die("The source file $fn does not contain any definitions of \$dbsalt.\r\n");	
	}
	
	foreach ($matches[2] as $idx=>$qsalt) {
		if (trim($matches[1][$idx])=='//') continue;
		$len=strlen($qsalt);
		$dbsalt=substr($qsalt,1,$len-2);
	}
			
}

$hash_xyz='$2y$12$sTs3OjcO9zZKSqRWltIhpeDtrNEoC1GJD3b70Xc.JYWm8P7GpHpjq'; //password_hash($dbsalt.'xyz',PASSWORD_DEFAULT,array('cost'=>PASSWORD_COST));

$saltok=1;
$reason='';

$badsalt=password_verify($dbsalt.'abc'.time(), $hash_xyz);


if ($badsalt){$saltok=0; $reason='This salt incorrectly accepts any password!';}
if ($saltok&&stripos($dbsalt,'gyroscope_demo')!==false) {$saltok=0; $reason='Do not use default salt.';}
if ($saltok&&strlen($dbsalt)<20) {$saltok=0; $reason='Salt length must be at least 20 chars.';}


if ($saltok) echo "\033[32m\033[1mThis salt is suitable as \$dbsalt.\033[0m\r\n";
else echo "\033[31m\033[1mBad Salt: $reason \033[0m\r\n";
