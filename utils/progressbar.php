<?php
function progressbar($idx,$max,$w=50){
	
	$base='['.str_pad('-',$w,'-').']';

	$progress=$idx/$max;
	$wstart=ceil($w*$progress);
	for ($i=0;$i<$wstart;$i++){
		$base[$i+1]='=';
	}	

	$base[$wstart+1]='>';

	$strprogress=' '.round(($progress)*100).'% ';


	$len=strlen($strprogress);
	$start=floor(($w-$len)/2);
	for ($i=0;$i<$len;$i++){
		$base[$start+$i+1]=$strprogress[$i];
	}

	$base[$w+1]=']';

	return "\r$base";
	
}
