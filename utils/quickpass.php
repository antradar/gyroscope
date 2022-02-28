<?php

include '../template/htdocs/encdec.php';
include 'bcrypt.php';

$itr=5;

$max=10;

echo "\r\nBenchmarking password hashing for $max seconds\r\n";
echo "The high the number, the easier to crack\r\n\r\n";

$count=0;
$a=microtime(1);
do{
	$pw=rand(100,000).time();
	$enc=encstr(md5($pw),$pw);
	$b=microtime(1);
	$diff=$b-$a;
	$count++;
} while ($diff<$max);

$dcount=number_format(round($count/$max,2));
echo "AES-256-CB:\t$dcount ops/sec\r\n";

for ($cost=10;$cost<14;$cost++){
	$count=0;
	$a=microtime(1);
	do{
		$pw=rand(100,000).time();
		password_hash($pw,PASSWORD_DEFAULT,array('cost'=>$cost));
		$b=microtime(1);
		$diff=$b-$a;
		$count++;
	} while ($diff<$max);
	
	$dcount=number_format(round($count/$max,2));
	echo "BCrypt-$cost:\t$dcount ops/sec\r\n";
	
}