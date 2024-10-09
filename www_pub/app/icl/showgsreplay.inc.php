<?php

function showgsreplay(){
	global $db;
	global $codepage;
	$gsreplayid=GETVAL('gsreplayid');
	gsguard($gsreplayid,'gsreplays','gsreplayid');

	$query="select * from gsreplays where gsreplayid=?";
	$rs=sql_prep($query,$db,$gsreplayid);
	$replay=sql_fetch_assoc($rs);
	
	$gsreplaytitle=$replay['gsreplaytitle'];
	$gsreplaydesc=$replay['gsreplaydesc'];
	
	header('newtitle:'.tabtitle(htmlspecialchars('#'.$gsreplayid.' '.$gsreplaytitle)));
	makechangebar('gsreplayview_'.$gsreplayid,"updategsreplay('$gsreplayid','".makegskey('updategsreplay_'.$gsreplayid)."');");
	makesavebar('gsreplayview_'.$gsreplayid);
		
?>
<div class="section">
	<div class="sectiontitle">Replay #<?php echo $gsreplayid;?> &nbsp; &nbsp; <?php echo htmlspecialchars($gsreplaytitle);?></div>

	<div class="majorcol">
	<img src="imgs/t.gif" id="gsreplay_<?php echo $gsreplayid;?>" style="max-width:95%;cursor:pointer;" onclick_x="gid('replayindicator_<?php echo $gsreplayid;?>').style.visibility='hidden';if (this.frames) gsreplay_play('gsreplay_<?php echo $gsreplayid;?>',this.frames,0,0,this.ff);">

	<div style="margin-top:20px;text-align:center;">
		<a class="hovlink" href="<?php echo $codepage;?>?cmd=downloadgsreplay&gsreplayid=<?php echo $gsreplayid;?>" target=_blank>download this clip</a>
	</div>
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
	
		<div class="inputrow">
			<div class="formlabel">Title:</div>
			<input class="inpmed" id="gsreplaytitle_<?php echo $gsreplayid;?>" value="<?php echo htmlspecialchars($gsreplaytitle);?>" oninput="this.onchange();" onchange="marktabchanged('gsreplayview_<?php echo $gsreplayid;?>');">
		</div>
		<div class="inputrow">
			<div class="formlabel">Description:</div>
			<textarea class="inplong expandable" id="gsreplaydesc_<?php echo $gsreplayid;?>" oninput="this.onchange();" onchange="marktabchanged('gsreplayview_<?php echo $gsreplayid;?>');"><?php echo htmlspecialchars($gsreplaydesc);?></textarea>
		</div>
		
		<div class="inputrow buttonbelt">
			<button onclick="updategsreplay('<?php echo $gsreplayid;?>','<?php emitgskey('updategsreplay_'.$gsreplayid);?>');">Update</button>
			&nbsp; &nbsp;
			<button class="warn" onclick="delgsreplay(<?php echo $gsreplayid;?>);">Delete</button>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php		
}