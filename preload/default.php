<?php

/*
PHP >=7.4

# php.ini

opcache.enabled=1
opcache.preload_user=www-data
opcache.preload=[full_path]/preload_default.php

*/

$scripts=array(
	'lb.php',
	'xss.php',
	'connect.php',
	'settings.php',
	'evict.php',
	'forminput.php',
	
);

//use full path
$gspath='/mnt/c/mnstudiodvd/gyroscope/template/htdocs/';

foreach ($scripts as $script){
	$fn=$gspath.$script;
	require_once($fn);	
	echo "preloading $fn\r\n";
}




