<?php

?>

PHP Header Space Scanner
(c) Schien Dong, Antradar Software Inc. 2025

<?php

$req=2;
$self=strtolower($argv[0]);
$exemode=1;

$fcount=0;
$gcount=0;


if (strpos($self,'.php')!==false) {$req=2;$exemode=0;}

if (count($argv)!=$req){
?>
Syntax: <?php echo $exemode?'headerscan':'php headerscan.php'?> [init_directory]

<?php	
	die();
}

$maindir=trim($argv[$req-1]);

headerscan($maindir);

if ($gcount>0){
	echo "\r\nTotal Count: $gcount\r\n";
	echo "Files: $fcount\r\n";	
}	


function headerscan($dir){
	global $gcount;
	global $fcount;
	
	if (!is_dir($dir)) {
?>
Error: <?php echo $dir;?> is not a valid directory.

<?php		
		die();
	}
		
	if (!$dh=opendir($dir)){
?>
Error: cannot open directory <?php echo $dir;?>

<?php		
	}
	
	//echo "Entering $dir\r\n";
	
	
	while (($file = readdir($dh)) !== false) {
		$parts=explode('.',$file);
		$ext=$parts[count($parts)-1];
		
		$type=filetype($dir.'/'.$file);
		
		if ($type=='dir'){
			if ($file!='.'&&$file!='..'&&$file!='.svn'&&$file!='.git') headerscan($dir.'/'.$file);		
		} else {
			if ($ext=='php'||$ext=='seed'){
				$c=analyse($dir.'/'.$file);
				$gcount+=$c;
				if ($c) $fcount++;
			}
		}
		
	}//while
	closedir($dh);    
	
		
}//func

function analyse($fn){
	
	$c=file_get_contents($fn);

	if (!preg_match_all('/header\(\s*[\'"]\S+\:\S/',$c,$matches)) return 0;	

	$count=count($matches[0]);
	
	echo "$fn    $count\r\n";	
	
	return $count;	
	
}





