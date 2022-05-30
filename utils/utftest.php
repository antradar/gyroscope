<?php

include '../template/htdocs/forminput.php';

/*
$f=fopen('seed.txt','rt');
$fw=fopen('unicode_tests.txt','wt');
while ($line=fgets($f)){
	$line=trim($line);
	fwrite($fw,utf8_decode($line)."-\r\n");
	fwrite($fw,$line."\r\n");
	fwrite($fw,utf8_encode($line)." +\r\n");
	fwrite($fw,utf8_encode(utf8_encode($line))." ++\r\n");
}
fclose($fw);
fclose($f);

die();
*/

$f=fopen('unicode_tests.txt','rt');
while ($line=fgets($f)){
	$line=trim($line);
	if ($line=='') continue;

	list($newline,$itr)=_utf8_fix($line);	
	echo "$line [".mb_strlen($line)."] => $newline [".mb_strlen($newline)."] $itr\r\n";	
}

echo "\r\n";

