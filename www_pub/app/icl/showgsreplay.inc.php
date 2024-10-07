<?php

function showgsreplay(){
	global $db;
	global $codepage;
	$gsreplayid=GETVAL('gsreplayid');
	gsguard($gsreplayid,'gsreplays','gsreplayid');
	
?>
<div class="section">
	<div class="sectiontitle">Replay #<?php echo $gsreplayid;?></div>

	<div class="majorcol">
	<img src="imgs/t.gif" id="gsreplay_<?php echo $gsreplayid;?>" style="max-width:95%;cursor:pointer;" onclick="gid('replayindicator_<?php echo $gsreplayid;?>').style.visibility='hidden';if (this.frames) gsreplay_play('gsreplay_<?php echo $gsreplayid;?>',this.frames,0,0,this.ff);">

	<?php 
	$query="select * from gsreplayframes where gsreplayid=? order by frameid";
	$rs=sql_prep($query,$db,array($gsreplayid));
	$frames=array();
	while ($myrow=sql_fetch_assoc($rs)){
		$frameid=$myrow['frameid'];
		array_push($frames,array(
			'frame'=>$codepage.'?cmd=img_gsreplayframe&frameid='.$frameid,
			'toffset'=>$myrow['frametoffset'],
			'itr'=>$myrow['frameitr']
		));	
	}//while
	?>
	<textarea class="inplong" id="gsreplayinfo_<?php echo $gsreplayid;?>" style="height:400px;display:none;"><?php echo json_encode($frames);?></textarea>	
	
	</div>
	<div class="minorcol">
	
		<div id="replayindicator_<?php echo $gsreplayid;?>" style="visibility:hidden;margin-bottom:10px;">
			Replay finished. Click on the clip to play again.
		</div>
	
		<div class="inputrow buttonbelt">
		<button class="warn" onclick="delgsreplay(<?php echo $gsreplayid;?>);">Delete</button>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php		
}