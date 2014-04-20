<?php

function listreports(){
?>
<div class="section">
	<div class="listitem"><a onclick="reloadtab('actionlog','Activity Log','rptactionlog');addtab('actionlog','Activity Log','rptactionlog');">Activity Log</a></div>

	<div class="listitem">
		<a onclick="reloadtab('report_purchase',null,'rptpurchase');addtab('report_purchase','Purchase Report','rptpurchase');">Purchase Report</a>
	</div>

	
</div>
<script>
gid('tooltitle').innerHTML='<a>Reports</a>';
</script>
<?
}
