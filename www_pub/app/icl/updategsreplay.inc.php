<?php

function updategsreplay(){
	$gsreplayid=GETVAL('gsreplayid');

	gsguard($gsreplayid,'gsreplays','gsreplayid');

	$gsreplaytitle=SQET('gsreplaytitle');
	$gsreplaydesc=SQET('gsreplaydesc');


	global $db;
	$user=userinfo();
	$gsid=$user['gsid'];
	
	checkgskey('updategsreplay_'.$gsreplayid);

	$query="select * from gsreplays where gsreplayid=?"; 
	$rs=sql_prep($query,$db,array($gsreplayid));
	$before=sql_fetch_assoc($rs);

	$query="update gsreplays set gsreplaytitle=?,gsreplaydesc=? where gsreplayid=?";
	sql_prep($query,$db,array($gsreplaytitle,$gsreplaydesc,$gsreplayid));

	$query="select * from gsreplays where gsreplayid=?"; 
	$rs=sql_prep($query,$db,array($gsreplayid));
	$after=sql_fetch_assoc($rs);
	
	$dbchanges=array('gsreplayid'=>$gsreplayid,'gsreplaytitle'=>"$gsreplaytitle");
	$diffs=diffdbchanges($before,$after,array('gsreplaydesc'));
	$dbchanges=array_merge($dbchanges,$diffs); //arg3-masks, arg4-omits
				
	logaction("updated Replay Clip #$gsreplayid $gsreplaytitle",
		$dbchanges,
		array('rectype'=>'gsreplay','recid'=>$gsreplayid));

}
