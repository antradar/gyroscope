<?php

function showdefleftcontent($quicklist=null){
	if (!isset($quicklist)) $quicklist=GETVAL('quicklist');
	
?>
<div class="section">
<?php
	if (!$quicklist){
		?>
		<div class="infobox">
			You can display records on the left, in the QuickList view.
			Links in the QuickList view launch into full views, but the left panel
			is independent from the right. It works like a second monitor.
		</div>
		<div style="text-align:center;">
			<button onclick="ajxpgn('defleftview',document.appsettings.codepage+'?cmd=setmyquicklist&quicklist=1');document.appsettings.quicklist=true;lkv_remount();">Enable QuickList</button>
		</div>
		<?php
		
	} else {
		?>
		<div class="infobox">
			The QuickList feature is enabled - records are displayed here first without interrupting the main view.
			
		</div>
		<div style="text-align:center;">
			<button onclick="ajxpgn('defleftview',document.appsettings.codepage+'?cmd=setmyquicklist&quicklist=0');document.appsettings.quicklist=false;lkv_dismount();">Disable QuickList</button>
		</div>
		<?php		
	}
?>

</div>
<?php	
}