<?php

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

	header('gsreplayid: '.$gsreplayid);		
?>
<div class="infobox">
	Clip #<?php echo $gsreplayid;?> has been created.
	<br><br>
	<div id="gsreplay_upload_vprogress_<?php echo $gsreplayid;?>" style="max-width:200px;border:solid 1px #dedede;">
		<div id="gsreplay_upload_progress_<?php echo $gsreplayid;?>" style="height:12px;font-size:8px;width:0%;background:#ffff00;"></div>
	</div>
</div>
<?php
		
}