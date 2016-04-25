<?php

function listreports(){
?>
<div class="section">
	<div class="listitem"><a onclick="reloadtab('actionlog','Activity Log','rptactionlog');addtab('actionlog','Activity Log','rptactionlog');">Activity Log</a></div>
</div>
<script>
gid('tooltitle').innerHTML='<a><?tr('icon_reports');?></a>';
</script>
<?
}
