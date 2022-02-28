<?php

?>

ASCII Scanner
(c) Schien Dong, Antradar Software Inc. 2018

<?php

$req=2;
$self=strtolower($argv[0]);
$exemode=1;

if (strpos($self,'.php')!==false) {$req=2;$exemode=0;}

if (count($argv)<$req){
?>
Syntax: <?php echo $exemode?'asciiscan':'php asciiscan.php'?> [init_directory] [warn]

<?php	
	die();
}

$maindir=trim($argv[1]);

$warn=0;

if ($argv[2]=='warn') $warn=1;

asciiscan($maindir,$warn);


function asciiscan($dir,$warn){
	
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
			if ($file!='.'&&$file!='..'&&$file!='.svn'&&$file!='.git') asciiscan($dir.'/'.$file);		
		} else {
			if ($ext=='php'||$ext=='js'||$ext=='css'){
				analyse($dir.'/'.$file,$warn);
			}
		}
		
	}//while
	closedir($dh);    	
		
}//func asciiscan

function analyse($fn,$warn=0){
	
	$buf=array();
	
	$f=fopen($fn,'rb');
	$buf=fread($f,3);
	fclose($f);
	
	$bad=0;
	for ($i=0;$i<3;$i++){
		$c=$buf[$i];
		if (ord($c)>126) $bad=1;
	}

	if ($bad) {
		echo "$fn\r\n";
		return;
	}
	
	if ($warn){
		$c=file_get_contents($fn);
		$len1=strlen($c);
		$len2=mb_strlen($c,'UTF-8');		
		if (preg_match('//u',$c)&&($len1!=$len2)) echo "Warn: $fn\r\n";
	}
	
}



