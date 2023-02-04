<?php

function showgyroscopeupdater(){
	
	global $db;
	global $dict_dir;
	
?>
<div style="color:#444444;padding:10px 0;line-height:1.6em;direction:<?php echo $dict_dir;?>">
	<div style="text-align:right;">
		<span class="footerpoweredby"><?php tr('powered_by_',array('power'=>'Antradar Gyroscope '.GYROSCOPE_VERSION.' '.VENDOR_INITIAL.VENDOR_VERSION));?></span>
	</div>
	<div style="text-align:right;" id="homefooterbuttons">
		<a class="labelbutton" onclick="updategyroscope();" style="white-space:nowrap;"><?php tr('check_updates');?></a>
		<?php if ($_SERVER['REMOTE_ADDR']==='127.0.0.1'&&($_SERVER['O_IP']==='127.0.0.1'||$_SERVER['O_IP']==='::1')){
		?>
		&nbsp; <a class="labelbutton" onclick="gid('codegenlist').style.display='block';">CodeGen</a>
		<?php	
		}?>
	</div>
	<div id="gyroscope_updater" style="display:none;margin-top:10px;padding:10px;border:solid 1px #999999;color:#000000;">
	</div>
	
</div>
<?php		
}