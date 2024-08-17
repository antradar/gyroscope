<?php

include 'icl/listmsgpipeusers.inc.php';

function dashmsgpipes(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$msgpipeadmin=isset($user['groups']['msgpipe']);
	
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) apperror('Access denied');
	
	
?>
<div class="section">
	<div class="sectiontitle">Notification Lists</div>
	
	<?php
	$query="select msgpipes.*,msgpipeuserid,msgpipeusers.userid,dispname from msgpipes
	left join msgpipeusers on msgpipes.msgpipeid=msgpipeusers.msgpipeid
	left join users on msgpipeusers.userid=users.userid
	where msgpipes.gsid=?";
	$rs=sql_prep($query,$db,$gsid);
	$msgpipes=array();
	while ($myrow=sql_fetch_assoc($rs)){
		$msgpipeid=$myrow['msgpipeid'];
		$msgpipekey=$myrow['msgpipekey'];
		$msgpipename=$myrow['msgpipename'];
		$msgpipeuserid=$myrow['msgpipeuserid'];
		
		if (!isset($msgpipes[$msgpipeid])) $msgpipes[$msgpipeid]=array('key'=>$msgpipekey,'name'=>$msgpipename,'users'=>array());
		
		$userid=$myrow['msgpipeuserid'];
		if (!isset($userid)) continue;
		$msgpipes[$msgpipeid]['users'][$userid]=array('name'=>$myrow['dispname'],'email'=>'');
	}//myrow
	
	//echo '<pre>'; print_r($msgpipes); echo '</pre>';
	
	foreach ($msgpipes as $msgpipeid=>$msgpipe){
	?>
	<div class="listitem">
		<b><?php echo htmlspecialchars($msgpipe['name']);?></b> &nbsp; <span class="labelbutton"><?php echo htmlspecialchars($msgpipe['key']);?></span>
		<div id="msgpipeusers_<?php echo $msgpipeid;?>" style="padding-top:5px;margin-left:25px;">
			<?php listmsgpipeusers($msgpipeid,$msgpipe['users']);?>
		</div>
		<?php if ($msgpipeadmin){?>
		<div class="inputrow buttonbelt" style="margin-left:25px;">
		<button class="warn" onclick="delmsgpipe(<?php echo $msgpipeid;?>,'<?php emitgskey('delmsgpipe_'.$msgpipeid);?>');">Remove List</button>
		</div>
		<?php }?>
	</div>
	<?php	
	}
	
	if ($msgpipeadmin){
	?>
	
	<div style="padding-top:20px;">
		<a class="recadder"><img src="imgs/t.gif" class="img-addrec">add a list</a>
	</div>
	<div style="padding:10px;margin-left:15px;">
		<div class="inputrow">
			<div class="formlabel">List Key:</div>
			<input class="inpshort" id="nmsgpipekey" spellcheck="false">
		</div>
		<div class="inputrow">
			<div class="formlabel">List Name:</div>
			<input class="inpmed" id="nmsgpipename">
		</div>
		<div class="inputrow">
			<button onclick="addmsgpipe('<?php emitgskey('addmsgpipe');?>');">Create List</button>
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php	
}
