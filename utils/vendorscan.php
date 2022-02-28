<?php

?>

Vendor Auth Scanner
(c) Schien Dong, Antradar Software Inc. 2019

<?php

$req=3;
$self=strtolower($argv[0]);
$exemode=1;

$fcount=0;
$gcount=0;



if (count($argv)!=$req){
?>
Syntax: <?php echo $exemode?'vendorscan':'php vendorscan.php'?> [init_directory] [vendorkey]

<?php	
	die();
}

$maindir=trim($argv[1]);

vendorscan($maindir,$argv[2]);

if ($fcount>0){
	echo "\r\nFiles: $fcount\r\n";
	echo "Patch:\r\n";
	echo "\t".'$user=userinfo();'."\r\n";
	echo "\t".'if ($user[\''.$argv[2].'\']) apperror(\'vauth: access denied\');';
	echo "\r\n";
}	


function vendorscan($dir,$vendorkey){
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
		$bext=$parts[count($parts)-2];
		
		$type=filetype($dir.'/'.$file);
		
		if ($type=='dir'){
			if ($file!='.'&&$file!='..'&&$file!='.svn'&&$file!='.git') vendorscan($dir.'/'.$file,$vendorkey);		
		} else {
			if ($ext=='php'&&$bext=='inc'){
				$c=analyse($dir.'/'.$file,$vendorkey);
				$gcount+=$c;
				if ($c) $fcount++;
			}
		}
		
	}//while
	closedir($dh);    
	
		
}//func

function analyse($fn,$vendorkey){
		
	$skips=array(
		'geocode','utils','codegen_makecode','codegen_makeform','addtemplate','addtemplatetype','addtemplatevar','adduser','deluser',
		'deltemplate','deltemplatetype','updater','updatetemplate','updatetemplatetype','delfunc','listfuncs','newfunc','showfunc','updatefunc',
		'testgapin','newuser','newtemplate','newtemplatetype','showkeyfilepad','showtemplate','showtemplatetype','maketemplate','timedropdown',
		'setaccountpass','rptactionlog','ackhelpspot','localizeimages','showhelp','showguide','showgaqr','showgyroscopeupdater',
		'showuserhelptopics','lookupstyles','showreportsetting','updatereportsetting','resetgakey','showaccount','lookup'
	);
	
	if (in_array(basename($fn,'.inc.php'),$skips)) return 0;
	
	$c=file_get_contents($fn);
	
	if (strpos($c,'$user['."'$vendorkey'".']')===false){
		echo $fn."\r\n";
		return 1;
	}
	
	return 0;
}





