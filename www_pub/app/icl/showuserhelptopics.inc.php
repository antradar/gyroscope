<?php

function showuserhelptopics($ctx=null){
	
	$user=userinfo($ctx);
	$userid=$user['userid'];

	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$query="select * from ".TABLENAME_USERHELPSPOTS." where userid=? limit 1";
	$rs=sql_prep($query,$db,$userid);
	?>
<div class="inputrow buttonbelt" style="padding-top:20px;padding-bottom:80px;">
	<?php
	if ($myrow=sql_fetch_assoc($rs)){
?>
	<a class="button warn" onclick="resethelpspots('<?php echo $userid;?>','<?php emitgskey('resethelpspots_'.$userid,'',$ctx);?>');">Reset Help Tips</a>
	&nbsp;
	<?php makehelp(null,'account_resethelp','This resets the help dots that you have dismissed. You may need a full page reload to pick up all the tips.');?>
	
<?php		
	}
?>
	&nbsp; &nbsp; &nbsp;
	<a class="button watchonly" href="login.php">Sign Out</a>
</div>
<?php		
}