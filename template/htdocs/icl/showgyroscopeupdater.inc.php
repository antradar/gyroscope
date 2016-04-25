<?php

function showgyroscopeupdater(){
	
	global $db;
	global $dict_dir;
	
?>
<div style="color:#444444;padding:10px 0;line-height:1.6em;">
	<div style="text-align:right;direction:<?echo $dict_dir;?>;">
		<span style="font-size:12px;"><?tr('powered_by_',array('power'=>'Antradar Gyroscope '.GYROSCOPE_VERSION.' '.VENDOR_INITIAL.VENDOR_VERSION));?> &nbsp; &nbsp;</span>
		<a class="labelbutton" onclick="updategyroscope();" style="white-space:nowrap;"><?tr('check_updates');?></a>
		<?if ($_SERVER['REMOTE_ADDR']=='127.0.0.1'){
		?>
		&nbsp; <a class="labelbutton" onclick="gid('codegenlist').style.display='block';">CodeGen</a>
		<?	
		}?>
	</div>
	
	<div id="gyroscope_updater" style="display:none;margin-top:10px;padding:10px;border:solid 1px #999999;font-size:13px;color:#000000;">
	</div>
	
</div>
<?		
}