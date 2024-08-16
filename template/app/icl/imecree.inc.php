<?php

function imecree(){
?>
<div style="text-align:center;height:100%;width:100%;">
	<div style="height:2%;font-size:1px;">&nbsp;</div>
	<textarea id="imecreekeyboard" class="inplong" onfocus="document.hotspot=this;" style="height:80%;width:98%;"></textarea>
	<div style="height:10%;margin-left:1%;">
		<button style="max-height:100%;" onclick="updateimecree();">Update</button>
		&nbsp; &nbsp; &nbsp; &nbsp;
		<button style="max-height:100%;" class="warn" onclick="closefs();">Cancel</button>
	</div>
</div>
<?php
}