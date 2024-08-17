<?php

function showkeyfilepad($container,$userid){
	global $db;
	global $codepage;
	
	$query="select * from users where userid=?";
	$rs=sql_prep($query,$db,$userid);
	$myrow=sql_fetch_assoc($rs);
	$haskeyfile=0;
	if ($myrow['keyfilehash']!='') $haskeyfile=1;
	
?>
<div id="keyfilemessage_<?php echo $container;?>" class="infobox" style="<?php if (!$haskeyfile) echo 'display:none;';?>">
	A key has been generated for this account. You may <a class="hovlink" onclick="gid('keyfilemessage_<?php echo $container;?>').style.display='none';gid('keyfilepadview_<?php echo $container;?>').style.display='block';">generate a new key</a>
</div>
<div id="keyfilepadview_<?php echo $container;?>" style="<?php if ($haskeyfile) echo 'display:none;';?>">
	<div class="infobox">
		Click on the box to generate a unique key:
	</div>
	<div style="border:solid 1px #dedede;height:180px;background:#ffffee;overflow:hidden;position:relative;" id="keyfilepad_<?php echo $container;?>" onmouseover="trackkeyfilepad(this,'<?php echo $container;?>');">
		<div onmouseover="keyfileboxover(this,'<?php echo $container;?>');" onclick="keyfileboxclick(this,'<?php echo $container;?>');" id="keyfilebox_<?php echo $container;?>" style="cursor:pointer;font-size:24px;line-height:30px;text-align:center;color:#ffffff;width:30px;height:30px;position:absolute;top:<?php echo rand(10,120);?>px;left:<?php echo rand(10,200);?>px;border:solid 1px #444444;background:#848cf7;transition:left 250ms,right 250ms;">
			1
		</div>
	</div>
</div>
<div id="keyfiledownloader_<?php echo $container;?>" class="infobox" style="display:none;">
	<a class="hovlink" onclick="gid('keyfileform_<?php echo $container;?>').submit();">Click here to download and activate</a> the key file at the same time.
	<br><br>
	Each time you click on the above link, the previously generated file is nullified.
	<br><br>
	The content of this file is transmitted to you and <em>only you</em>. It is at no time stored on the server.
</div>
<form method="POST" id="keyfileform_<?php echo $container;?>" target=_blank action="<?php echo $codepage;?>?cmd=downloadgskeyfile">
<textarea style="width:100%;height:100px;display:none;" name="keyfileinfo" id="keyfileinfo_<?php echo $container;?>"></textarea>
<input type="hidden" name="keyfileuserid" value="<?php echo $userid;?>">
<input type="hidden" name="X-GSREQ-KEY" value="<?php emitgskey('downloadgskeyfile_'.$userid);?>">
</form>
<?php	
		
}