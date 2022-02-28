<?php

function showuserhelptopics(){
	
	$user=userinfo();
	$userid=$user['userid'];

	global $db;
	
	$query="select * from ".TABLENAME_USERHELPSPOTS." where userid=? limit 1";
	$rs=sql_prep($query,$db,$userid);
	if ($myrow=sql_fetch_assoc($rs)){
?>
<div class="inputrow" style="padding-top:20px;padding-bottom:80px;">
	<button class="warn" onclick="resethelpspots('<?php echo $userid;?>','<?php emitgskey('resethelpspots_'.$userid);?>');">Reset Help Tips</button>
	&nbsp;
	<?php makehelp('account_resethelp','This resets the help dots that you have dismissed. You may need a full page reload to pick up all the tips.');?>
	
</div>
<?php		
	}
		
}