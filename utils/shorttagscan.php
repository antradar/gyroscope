<?php

?>

PHP Short Tag Scanner
(c) Schien Dong, Antradar Software Inc. 2019

<?php

$req=2;
$self=strtolower($argv[0]);
$exemode=1;

$fcount=0;
$gcount=0;


if (strpos($self,'.php')!==false) {$req=2;$exemode=0;}

if (count($argv)!=$req){
?>
Syntax: <?php echo $exemode?'shorttagscan':'php shorttagscan.php'?> [init_directory]

<?php	
	die();
}

$maindir=trim($argv[$req-1]);

shorttagscan($maindir);

if ($gcount>0){
	echo "\r\nTotal Count: $gcount\r\n";
	echo "Files: $fcount\r\n";	
}	


function shorttagscan($dir){
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
			if ($file!='.'&&$file!='..'&&$file!='.svn'&&$file!='.git') shorttagscan($dir.'/'.$file);		
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
	
	if (!preg_match_all('/<\?/',$c,$matches,PREG_OFFSET_CAPTURE)) return;
		
	$count=0;
	
		
	foreach ($matches as $parts){
		foreach ($parts as $part){
					
			$pos=$part[1];
			
			$tag=substr($c,$pos,6);
						
			if (trim($tag)==trim('<?php ')||trim($tag)==trim('<?xml ')) continue; //double trim to avoid false positive scanning on itself
			$count++;
		}
	}
	
	
	if (!$count) return 0;
	
	echo $fn.'     '.$count.' instance'.($count==1?'':'s')."\r\n";
	
	return $count;
}





