<?php

ini_set("auto_detect_line_endings", true);

//given a position in a string, return the line number
function countlines($c,$pos){
	$pre=substr($c,0,$pos);
	$parts=explode("\n",$pre);
	$nlines=count($parts);
	return $nlines;
}

//parse out the last parameter in the call stack

function lastcallparam($line){
	$depth=null;
	$len=strlen($line);
	$found=0;
	
	for ($i=0;$i<$len;$i++){
		$c=$line[$i];
		switch ($c){
			case '(':
				if (!isset($depth)) $depth=1; else $depth++;
			break;
			case ')':
				$depth--;
				if ($depth==0&&$found==0){
					$found=$i;
					break;	
				}
			break;
			
		}//switch
	}//for
	
	if (!$found) return array();

	$str=substr($line,0,$found+1);
	
	$parts=explode(',',$str);
	$lastparam=trim(trim($parts[count($parts)-1],')'));
		
	$param3=$parts[2]??'';
	$param2=$parts[1]??'';
	$action='';
	$params=explode('&',$param3);
	
	$action=trim(trim($params[0],"'"));
	$action=str_replace(array("'",')'),'',$action);

	if (preg_match('/cmd=/',$param2,$matches)) $action=$param2;
	if (preg_match('/cmd=(\w+)/',$param2,$matches)) $action=$matches[1];
	
	
	$skip=0;
	$allowed=array('checkpass','logpump','list','show','slv_','lookup','rpt','slv\d+','new','reauth','updategyroscope','codegen_makeform','wk','dash','ct\.','tab\.reload',"document\.appsettings\.codepage\+'\?cmd='\+",'installmods','pdf','preview','xls','download');
	foreach ($allowed as $allow){
		if (preg_match('/^'.$allow.'/',$action)) $skip=1;
	}
	
	return array(
		'lastparam'=>$lastparam,
		'action'=>$action,
		'line'=>$str,
		'skip'=>$skip
	);
}

//print_r(lastcallparam("	ajxpgn('statusc',document.appsettings.codepage+'?cmd=updaterenewalpenaltyclosedvariable&loanid='+loanid+'&value='+d.value);	"));
//die();

//parse out all the ajxpgn call positions

function locatekeycalls($c,$verb){
	if (!preg_match_all('/'.$verb.'\s*\(/',$c,$matches,PREG_OFFSET_CAPTURE)) return array();
	
	$poses=array();
	
	foreach ($matches[0] as $match){
		$pos=$match[1];
		array_push($poses,$pos);	
	}
	
	$eof=strlen($c);
	
	$parts=array();
	
	$count=0;
	
	$lines=array();
	
	for ($i=0;$i<count($poses)-1;$i++){

		$a=$poses[$i];
		$b=$poses[$i+1];
		if ($i==count($poses)-1) $b=$eof;
		$part=substr($c,$a,$b);
		$nlines=countlines($c,$a);

		//echo "$a - $b / $last\r\n";
				
		$callinfo=lastcallparam($part);
		$part=$callinfo['line']??'';
		$lastparam=trim($callinfo['lastparam']??'');
		$action=trim($callinfo['action']??'');
		if ($action==''||$lastparam=='gskey'||$callinfo['skip']) continue;
		$count++;
		//echo "Line $nlines: $action\r\n";
		
		array_push($lines,array('loc'=>$nlines,'action'=>$action));
	}
	
	//echo "\r\nFound $count vulnerable ajxpgn calls\r\n";
	
	return $lines;
	
}

//$c=file_get_contents('/svn/loanstudio/site/app/loans.js');
//print_r(locatekeycalls($c,'reloadtab'));

//echo "\r\n";

//die();

$req=2;
$self=strtolower($argv[0]);
$exemode=1;

if (strpos($self,'.php')!==false) {$req=2;$exemode=0;}

if (count($argv)!=$req){
?>
GSKey Scanner
(c) Schien Dong, Antradar Software Inc. 2018

Syntax: <?php echo $exemode?'gskeyscan':'php gskeyscan.php'?> [init_directory]

<?php	
	die();
}

global $stats;
$stats['files']=0;
$stats['reloads']=0;
$stats['ajxpgns']=0;


$maindir=trim($argv[$req-1]);

gskeyscan($maindir);


function gskeyscan($dir){
	
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
		
		if (in_array($file,array('nano.js','wss.js','viewport.js','autocomplete.js') )) continue; //skip library files
		
		$type=filetype($dir.'/'.$file);
		
		if ($type=='dir'){
			if ($file!='.'&&$file!='..'&&$file!='.svn'&&$file!='.git') gskeyscan($dir.'/'.$file);		
		} else {
			if ($ext=='js'){ //||$ext=='php'
				analyse($dir.'/'.$file);
			}
		}
		
	}//while
	closedir($dh);    	
		
}//func gskeyscan

echo "\r\n\r\nScan Summary:\r\n--------------\r\n";

echo "File scanned: ".$stats['files']."\r\n";
echo "ajxpgn calls: ".$stats['ajxpgns']."\r\n";
echo "reloadtab calls: ".$stats['reloads']."\r\n";

echo "\r\n";

function analyse($fn){
	
	global $stats;
	
	$stats['files']++;
	
	$c=file_get_contents($fn);
	
	
	$sqllines=array();
	
	$hasquery=0;
	$hasajxpgn=0;
	
		
	if (preg_match_all('/sql_query\s*\(\$([\S\s]+?)[,\s\$\)]/',$c,$matches)){
		$hasquery=1;
	}

	
	if (preg_match_all('/\-\>query\s*\(\$([\S\s]+?)[,\s\$\)]/',$c,$matches)){
		$hasquery=1;
	}
	
	$ajxpgnlines=locatekeycalls($c,'ajxpgn');	
	$reloadlines=locatekeycalls($c,'reloadtab');
	
	$stats['ajxpgns']+=count($ajxpgnlines);
	$stats['reloads']+=count($reloadlines);
	
	$lines=array_merge($ajxpgnlines,$reloadlines);
	
	$count=count($lines);		
	if ($count>0){
		?>

Found script with <?php echo $count;?> problem<?php echo $count==1?'':'s';?>: 
[<?php echo $fn;?>]

<?php
		foreach ($lines as $line){
			echo "  Line ".$line['loc'].":\t".$line['action']."\r\n";	
		}
		
	}
}



