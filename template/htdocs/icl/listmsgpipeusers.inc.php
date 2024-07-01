<?php

function listmsgpipeusers($msgpipeid=null,$users=null){
	global $db;
	$user=userinfo();
	
	if (!isset($user['groups']['msgpipe'])&&!isset($user['groups']['msgpipeuse'])) return;
	
	if (!isset($msgpipeid)) $msgpipeid=GETVAL('msgpipeid');
	if (!isset($users)){
		gsguard($msgpipeid,'msgpipes','msgpipeid');
		$query="select msgpipeusers.*,dispname from msgpipeusers,users where msgpipeusers.userid=users.userid and msgpipeid=?";
		$rs=sql_prep($query,$db,array($msgpipeid));
		$users=array();
		while ($myrow=sql_fetch_assoc($rs)){
			$users[$myrow['msgpipeuserid']]=array('name'=>$myrow['dispname'],'email'=>'');
		}//while
	}
	
	foreach ($users as $userid=>$user){
	
	?>
	<nobr><a class="hovlink"><?php echo htmlspecialchars($user['name']);?></a> <a onclick="delmsgpipeuser(<?php echo $msgpipeid;?>,<?php echo $userid;?>,'<?php emitgskey('delmsgpipeuser_'.$userid);?>');"><img src="imgs/t.gif" class="img-del"></a></nobr> &nbsp;
	<?php		
	}
	
?>
	<input onchange="addmsgpipeuser(this,<?php echo $msgpipeid;?>,'<?php emitgskey('addmsgpipeuser_'.$msgpipeid);?>');" placeholder="+ Recipient" class="inpshort" onfocus="lookupentity(this,'user&activeonly=1','Users');" onkeyup="_lookupentity(this,'user&activeonly=1','Users');">
<?php		
}
