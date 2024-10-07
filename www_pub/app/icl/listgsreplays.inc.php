<?php

function listgsreplays(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
?>
<div class="section">

<?php
	$query="select * from gsreplays where gsid=? order by gsreplayid desc";
	$rs=sql_prep($query,$db,$gsid);
	while ($myrow=sql_fetch_assoc($rs)){
		$gsreplayid=$myrow['gsreplayid'];
		$gsreplaydate=$myrow['gsreplaydate'];
		$ddate=date('Y-n-j H:i:s',$gsreplaydate);
	?>
	<div class="listitem">
	<a onclick="showgsreplay(<?php echo $gsreplayid;?>);">#<?php echo $gsreplayid;?> <?php echo $ddate;?></a>
	</div>
	<?php	
	}//myrow
?>
</div>
<script>
gid('tooltitle').innerHTML='<a>Replay Clips</a>';
</script>
<?php	
}