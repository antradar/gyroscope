<?php

function numfile_stream_contents($stem,$ext,$basedir,$perfolder=100){
	$path=numfile_make_path($stem,$basedir,$perfolder);
	$fn=$path.'/'.$stem.'.'.trim($ext,'.');
	if (!file_exists($fn)) return;
	readfile($fn);
}

function numfile_get_contents($stem,$ext,$basedir,$perfolder=100){
	$path=numfile_make_path($stem,$basedir,$perfolder);
	$fn=$path.'/'.$stem.'.'.trim($ext,'.');
	if (!file_exists($fn)) return null;
	return file_get_contents($fn);
}

function numfile_put_contents($stem,$ext,$basedir,$data,$perfolder=100){
	$path=numfile_make_path($stem,$basedir,$perfolder,1);
	$fn=$path.'/'.$stem.'.'.trim($ext,'.');
	
	file_put_contents($fn,$data);
}

function numfile_make_path($stem,$basedir,$perfolder=100,$createfolder=0){
	if (!is_numeric($stem)) throw new ErrorException('not a numeric stem');
		
	$dir=rtrim($basedir,'/');
	$level1=floor($stem/$perfolder/$perfolder);
	$level2=floor($stem/$perfolder);

	if ($createfolder){
		if (!is_dir($dir)) mkdir($dir);	
		if (!is_dir($dir.'/'.$level1)) mkdir($dir.'/'.$level1);
		if (!is_dir($dir.'/'.$level1.'/'.$level2)) mkdir($dir.'/'.$level1.'/'.$level2);
	}
	
	return $dir.'/'.$level1.'/'.$level2;
}
