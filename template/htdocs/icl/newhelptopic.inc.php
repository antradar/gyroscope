<?php

function newhelptopic(){

?>
<div class="section">
	<div class="sectiontitle"><?php tr('list_helptopic_add_tab');?></div>
	
<div class="col">
	
	<div class="inputrow">
		<div class="formlabel"><?php tr('helptopic_label_helptopictitle');?>:</div>
		<input class="inp" id="helptopictitle_new">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('helptopic_label_helptopickeywords');?>:</div>
		<input class="inp" id="helptopickeywords_new">
	</div>

		

</div>
<div class="clear"></div>

		<div class="inputrow">
			<button onclick="addhelptopic('<?php emitgskey('addhelptopic');?>');">Continue &raquo;</button>
		</div>

</div>
<?php

}
