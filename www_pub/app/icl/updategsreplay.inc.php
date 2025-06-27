<?php

function updategsreplay($ctx=null){
	$gsreplayid=GETVAL('gsreplayid',$ctx);

	gsguard($ctx,$gsreplayid,'gsreplays','gsreplayid');

	$gsreplaytitle=SQET('gsreplaytitle',1,$ctx);
	$gsreplaydesc=SQET('gsreplaydesc',1,$ctx);


	if (isset($ctx)) $db=&$ctx->db; else global $db;
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	
	checkgskey('updategsreplay_'.$gsreplayid,$ctx);

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
				
	logaction($ctx, "updated Replay Clip #$gsreplayid $gsreplaytitle",
		$dbchanges,
		array('rectype'=>'gsreplay','recid'=>$gsreplayid));

}
