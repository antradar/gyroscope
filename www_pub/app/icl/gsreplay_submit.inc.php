<?php

include 'libnumfile.php';

function gsreplay_submit(){
	//print_r($_FILES);
	
	global $db;
	
	$user=userinfo();
	$userid=$user['userid'];
	$gsid=$user['gsid'];
	
	$now=time();
	$sharestatus=0; //for now
	
	$width=QETVAL('width');
	$height=QETVAL('height');
	
	$toffsets=explode(',',SQET('toffsets'));
	$itrs=explode(',',SQET('itrs'));
	
	$query="insert into gsreplays (
		gsreplaydate,gsreplayuserid,gsid,gsreplaysharestatus,
		gsreplaywidth,gsreplayheight
	) values (
		?,?,?,?,
		?,?
	)";
	
	$rs=sql_prep($query,$db,array(
		$now,$userid,$gsid,$sharestatus,
		$width,$height)
	);
	
	$gsreplayid=sql_insert_id($db,$rs);
	
	$ffns=$_FILES['frames']['tmp_name'];
	
	$params=array();
	$qs=array();
	
	foreach ($toffsets as $idx=>$toffset){
		$itr=$itrs[$idx];
		array_push($qs,'(?,?,?)');
		array_push($params,$gsreplayid,$toffset,$itr);	
	}
	
	$query="insert into gsreplayframes (gsreplayid,frametoffset,frameitr) values ".implode(',',$qs);
	$rs=sql_prep($query,$db,$params);
	
	$query="select frameid from gsreplayframes where gsreplayid=?";
	$rs=sql_prep($query,$db,$gsreplayid);
	$frameids=array();
	while ($myrow=sql_fetch_assoc($rs)) array_push($frameids,$myrow['frameid']);
	
	//$path='../../protected/gsreplays/';
	$basedir='../../protected/gsreplays/';
	$ext='.gsreplay_'.$gsreplayid.'.png';
	
	foreach ($ffns as $ffn){
		$frameid=array_shift($frameids);
		$c=file_get_contents($ffn);
		echo "$frameid $ffn<br>\r\n";
		
		//$fn=$path.$gsreplayid.'_'.$frameid.'.png';
		//file_put_contents($fn,$c);
		$stem=$frameid;
		numfile_put_contents($stem,$ext,$basedir,$c);		
	}
		
		
}