<?php

function showuserprofile($userid=null){
	if (!isset($userid)) $userid=GETVAL('userid');
	
	global $db;
	global $codepage;
	
	$query="select * from ".TABLENAME_USERS." where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_array($rs);
	
	$haspic=$myrow['haspic'];
	$imgv=$myrow['imgv'];
?>
<div style="position:relative;padding-left:110px;">

	<div style="position:absolute;top:0;left:0;width:90px;">
	<?php
		if ($haspic){
	?>
	
	
	<img src="<?php echo $codepage;?>?cmd=imguserprofile&v=<?php echo $imgv;?>" style="width:80px;margin-left:10px;border-radius:200px;background:#dedede;">
	<div style="padding:10px 0;text-align:center;">
		<a onclick="removeuserprofilepic(<?php echo $userid;?>,'<?php emitgskey('removeuserprofilepic_'.$userid);?>')">reset <img src="imgs/t.gif" class="img-del"></a>
	</div>
	<?php	
		} else {
		?>
		<img src="imgs/profile.png" style="width:80px;margin-left:10px;border-radius:200px;background:#dedede;">
		<?php	
			
		}
			
		?>
	</div>
	<div>
		<iframe style="width:90%;border:none;height:200px;" frameborder="no" src="<?php echo $codepage;?>?cmd=embeduserprofileuploader&userid=<?php echo $userid;?>&hb=<?php echo time();?>"></iframe>
	</div>
</div>
	<?php
	
}

