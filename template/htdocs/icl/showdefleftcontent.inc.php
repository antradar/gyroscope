<?php

function showdefleftcontent($quicklist=null){
	if (!isset($quicklist)) $quicklist=GETVAL('quicklist');
	
?>
<div class="section">

		<div class="infobox">
			The QuickList feature is enabled - records are displayed here first without interrupting the main view.
			
		</div>

</div>
<?php	
}