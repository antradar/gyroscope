<?php

function showkeyfilepad($container,$userid){
	global $db;
	global $codepage;
	
	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$haskeyfile=0;
	if ($myrow['keyfilehash']!='') $haskeyfile=1;
	
?>
<div id="keyfilemessage_<?echo $container;?>" class="infobox" style="<?if (!$haskeyfile) echo 'display:none;';?>">
	A key has been generated for this account. You may <a class="hovlink" onclick="gid('keyfilemessage_<?echo $container;?>').style.display='none';gid('keyfilepadview_<?echo $container;?>').style.display='block';">generate a new key</a>
</div>
<div id="keyfilepadview_<?echo $container;?>" style="<?if ($haskeyfile) echo 'display:none;';?>">
	<div class="infobox">
		Click on the box to generate a unique key:
	</div>
	<div style="border:solid 1px #dedede;height:180px;background:#ffffee;overflow:hidden;position:relative;" id="keyfilepad_<?echo $container;?>" onmouseover="trackkeyfilepad(this,'<?echo $container;?>');">
		<div onmouseover="keyfileboxover(this,'<?echo $container;?>');" onclick="keyfileboxclick(this,'<?echo $container;?>');" id="keyfilebox_<?echo $container;?>" style="cursor:pointer;font-size:24px;line-height:30px;text-align:center;color:#ffffff;width:30px;height:30px;position:absolute;top:<?echo rand(10,120);?>px;left:<?echo rand(10,200);?>px;border:solid 1px #444444;background:#848cf7;transition:left 250ms,right 250ms;">
			1
		</div>
	</div>
</div>
<div id="keyfiledownloader_<?echo $container;?>" class="infobox" style="display:none;">
	<a class="hovlink" onclick="gid('keyfileform_<?echo $container;?>').submit();">Click here to download and activate</a> the key file at the same time.
	<br><br>
	Each time you click on the above link, the previously generated file is nullified.
	<br><br>
	The content of this file is transmitted to you and <em>only you</em>. It is at no time stored on the server.
</div>
<form method="POST" id="keyfileform_<?echo $container;?>" target=_blank action="<?echo $codepage;?>?cmd=downloadgskeyfile">
<textarea style="width:100%;height:100px;display:none;" name="keyfileinfo" id="keyfileinfo_<?echo $container;?>"></textarea>
<input type="hidden" name="keyfileuserid" value="<?echo $userid;?>">
</form>
<?	
		
}