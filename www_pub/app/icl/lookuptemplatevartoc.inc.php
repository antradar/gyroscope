<?php

function lookuptemplatevartoc(){

?>
<div class="section">
	<div class="inputrow">
		<div class="formlabel">View by:</div>
		<input type="radio" name="lookup_templatevartoc" id="lookup_templatevartoc_list" onclick="mce_findvars(null,null,'list');" checked>
		<label for="lookup_templatevartoc_list">Occurance</label>
		&nbsp;
		<input type="radio" name="lookup_templatevartoc" id="lookup_templatevartoc_group" onclick="mce_findvars(null,null,'group');">
		<label for="lookup_templatevartoc_group">Variable</label>		
		
	</div>
	<div id="lookuptemplatevar_toc"></div>
</div>
<?php		
}
