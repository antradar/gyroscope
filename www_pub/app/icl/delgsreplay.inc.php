<?php
include 'libnumfile.php';

function delgsreplay($ctx=null){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	$gsreplayid=GETVAL('gsreplayid',$ctx);
	gsguard($ctx,$gsreplayid,'gsreplays','gsreplayid');

	$basedir='../../protected/gsreplays/';
		
	$query="select frameid from gsreplayframes where gsreplayid=?";
	$rs=sql_prep($query,$db,$gsreplayid);
	while ($myrow=sql_fetch_assoc($rs)){
		$frameid=$myrow['frameid'];
	
		$path=numfile_make_path($gsreplayid,$basedir);
		$fn=$path.'/'.$gsreplayid.'.'.$frameid.'.png';
		if (file_exists($fn)) unlink($fn);		
			
	}//while

	$query="delete gsreplays.*, gsreplayframes.* from gsreplays left join gsreplayframes on gsreplays.gsreplayid=gsreplayframes.gsreplayid where gsreplays.gsreplayid=?";
	sql_prep($query,$db,array($gsreplayid));
			
}