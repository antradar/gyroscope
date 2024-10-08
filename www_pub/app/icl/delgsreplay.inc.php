<?php
include 'libnumfile.php';

function delgsreplay(){
	global $db;
	$gsreplayid=GETVAL('gsreplayid');
	gsguard($gsreplayid,'gsreplays','gsreplayid');

	$basedir='../../protected/gsreplays/';
	$ext='.gsreplay_'.$gsreplayid.'.png';
		
	$query="select frameid from gsreplayframes where gsreplayid=?";
	$rs=sql_prep($query,$db,$gsreplayid);
	while ($myrow=sql_fetch_assoc($rs)){
		$frameid=$myrow['frameid'];
	
		$path=numfile_make_path($frameid,$basedir);
		$fn=$path.'/'.$frameid.$ext;
		if (file_exists($fn)) unlink($fn);		
			
	}//while

	$query="delete gsreplays.*, gsreplayframes.* from gsreplays left join gsreplayframes on gsreplays.gsreplayid=gsreplayframes.gsreplayid where gsreplays.gsreplayid=?";
	sql_prep($query,$db,array($gsreplayid));
	
	
			
}