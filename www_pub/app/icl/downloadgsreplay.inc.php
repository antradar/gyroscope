<?php

include_once 'libnumfile.php';
include_once 'makeslug.php';

function downloadgsreplay($ctx=null){
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$gsreplayid=GETVAL('gsreplayid',$ctx);
	$rs=gsguard($ctx,$gsreplayid,'gsreplays','gsreplayid','gsreplaytitle,gsreplaydesc,gsreplaywidth,gsreplayheight');

	$title=$rs['gsreplaytitle'];
	
	$slug=makeslug($title);
	$dfn=trim("$gsreplayid-$slug",'-');
		
	gs_header($ctx,'Content-Type', 'binary/octet-stream');
	gs_header($ctx, "Content-disposition", "attachment; filename=\"$dfn.gsreplay\"");


	$width=$rs['gsreplaywidth'];	
	$height=$rs['gsreplayheight'];
	
	$desc=$rs['gsreplaydesc'];
	
	$sptr="\r\n";
	
	echo $width.'x'.$height.$sptr;
	echo json_encode($title).$sptr;
	echo json_encode($desc).$sptr;

	$basedir='../../protected/gsreplays/';
	
	$toffsets=array();
	$itrs=array();
	$frameids=array();
		
	$query="select frameid,frametoffset,frameitr from gsreplayframes where gsreplayid=? order by frameid";
	$rs=sql_prep($query,$db,array($gsreplayid));
	while ($myrow=sql_fetch_assoc($rs)){
		array_push($toffsets,$myrow['frametoffset']);
		array_push($itrs,$myrow['frameitr']);
		array_push($frameids,$myrow['frameid']);
	}//while

	echo implode(',',$toffsets).$sptr;
	echo implode(',',$itrs).$sptr;
	
	foreach ($frameids as $frameid){
		$path=numfile_make_path($gsreplayid,$basedir);
		$fn=$path.'/'.$gsreplayid.'.'.$frameid.'.png';
		//@readfile($fn);
		echo base64_encode(file_get_contents($fn));
		echo $sptr;
	}
	
	
		
}