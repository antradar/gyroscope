<?php

function lookupuser(){
	$key=SGET('key');
	$canchatnow=intval(SGET('canchatnow'));
	
	global $db;
	global $WSS_INTERNAL_HOST;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	$userid=$user['userid'];
	
	$params=array($gsid);
	
	$query="select * from ".TABLENAME_USERS." where ".COLNAME_GSID."=? ";
	
	$activeagents=array();
    $agentfilters='';
	
	if ($canchatnow&&class_exists('Redis')){	
	
	    global $redis;
	    $valid=0;
	    if (!isset($redis)){
		    try{
	            $redis=new Redis();
	            $redis->connect($WSS_INTERNAL_HOST,REDIS_PORT);
	            $valid=1;
            } catch (Exception $e){
	         	//echo "warn: cannot connect to Redis server";
            }
	    } else $valid=1;
	    
	    if ($valid){
		    $agentmap=json_decode($redis->get(REDIS_PREFIX.'agentmap'),1);
		    $activeagents=$agentmap[$gsid];
	    }
	    
	    if (count($activeagents)>0) {
			$agentfilters=" and userid in (".implode(',',$activeagents).")";   
	    }
	    
    }	
   
	
	if ($key!=''){
		$query.=" and (dispname like ? or login like ?)";
		array_push($params,"%$key%","%$key%");
	}
	
	if ($canchatnow){
		$query.=" and canchat=1 and userid!=".$userid." ".$agentfilters;	
	}
	
	$query.=" order by dispname";
	
	$rs=sql_prep($query,$db,$params);
	$c=sql_affected_rows($db,$rs);
	
	if ($c==0&&$canchatnow){
	?>
	<div class="infobox">
		No one else is online to be added to the chat.
	</div>
	<?php	
	}

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