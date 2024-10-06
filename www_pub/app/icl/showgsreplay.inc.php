<?php

function showgsreplay(){
	global $db;
	global $codepage;
	$gsreplayid=GETVAL('gsreplayid');
	gsguard($gsreplayid,'gsreplays','gsreplayid');
	
?>
<div class="section">
	<div class="sectiontitle">Replay #<?php echo $gsreplayid;?></div>

	<img src="imgs/t.gif" id="gsreplay_<?php echo $gsreplayid;?>" style="max-width:70%;cursor:pointer;" onclick="if (this.frames) gsreplay_play('gsreplay_<?php echo $gsreplayid;?>',this.frames,0,0,this.ff);">

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
<?php		
}