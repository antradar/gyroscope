<?php

function mceeditsource(){

?>
<div style="text-align:center;height:100%;width:100%;">
	<div style="height:2%;font-size:1px;">&nbsp;</div>
	<textarea autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" onchange="this.changed=true;" onfocus="filterkeys(this);document.hotspot=this;" style="font-family:Courier,monospace;font-size:15px;text-align:left;margin-top:5%;height:85%;width:98%;border:none;display:block;margin:0 auto;border:solid 1px #dedede;" id="mcesourceeditor"></textarea>
	<div style="height:10%;margin-left:1%;">
		<button id="mceeditor_save" style="max-height:100%;" onclick="updatesourceeditor();">Update</button>
		&nbsp; &nbsp; &nbsp; &nbsp;
		<button style="max-height:100%;" class="warn" onclick="closefs();">Cancel</button>
	</div>
</div>
<?php		
}