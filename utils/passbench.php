<?php

/*
Utility for determining the most suitable password hashing cost
*/

include 'bcrypt.php';

if (is_callable('password_hash_fallbackmode')){
	echo "Warning: running in legacy mode\r\n";	
}

$bigpass='';
for ($i=0;$i<5000;$i++) $bigpass.=time();

$cutoff=3; //3 seconds max
$itr=5; //number of test iterations

for ($cost=8;$cost<30;$cost++){
	$avg=0;
	for ($i=0;$i<$itr;$i++){
		$a=microtime(1);
		password_hash(rand(100000,999999).time().$bigpass,PASSWORD_DEFAULT,array('cost'=>$cost));
		$b=microtime(1);
		$diff=$b-$a;
		$avg+=$diff;
		if ($diff>$cutoff) {
			echo "\r\ntoo costly. terminating\r\n";
			die();	
		}
	}
	$avg=$avg/$itr;
	
	echo "$cost\t$avg\r\n";
}
