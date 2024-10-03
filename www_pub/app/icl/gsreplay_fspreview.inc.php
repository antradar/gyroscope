<?php

function gsreplay_fspreview(){
?>
<style>
	#gsreplay_cropper, #gsreplay_controls{float:left;}
	#gsreplay_cropper{width:68%;margin-right:2%;}
	#gsreplay_controls{width:30%;}
</style>

<div style="padding:20px;">
	<div id="gsreplay_cropper" style="position:relative;">
		<img id="gsreplay_preview" style="background:#000000;display:block;width:100%;">
	</div>
	<div id="gsreplay_controls">
		<div>
			<input type="checkbox" id="gsreplay_croptrigger" onclick="gsreplay_togglecrop(this,'gsreplay_cropper');"> <label for="gsreplay_croptrigger">crop content</label>
		</div>
		<div>
			<button onclick="gsreplay_submit();">Save Recording</button>
		</div>
		
	</div>
	<div class="clear"></div>
</div>
<?php
}

