<?php

function showgyroscopeupdater(){
	
	global $db;
	
?>
<div style="color:#444444;padding:10px 0;">
	<div style="text-align:right;">
		powered by Gyroscope <?echo GYROSCOPE_VERSION.' '.VENDOR_INITIAL.VENDOR_VERSION;?> &nbsp; &nbsp;
		<a class="labelbutton" onclick="updategyroscope();">Check updates</a>
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