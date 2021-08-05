<?php

function lookupuser(){
	$key=SGET('key');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$params=array($gsid);
	
	$query="select * from users where gsid=? ";
	
	if ($key!=''){
		$query.=" and (dispname like ? or login like ?)";
		array_push($params,"%$key%","%$key%");
	}
	
	$query.=" order by dispname";
	
	$rs=sql_prep($query,$db,$params);

?>
<div class="section">
<?php		
	while ($myrow=sql_fetch_assoc($rs)){
		$userid=$myrow['userid'];
		$dispname=$myrow['dispname'];
		$dname=noapos(htmlspecialchars(htmlspecialchars($dispname)));
	?>
	<div class="listitem">
		<a onclick="picklookup('<?php echo $dname;?>',<?php echo $userid;?>);"><?php echo htmlspecialchars($dispname);?></a>
	</div>
	<?php	
	}//while
?>
</div>
<?php		
}