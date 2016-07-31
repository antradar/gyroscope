<?php

function lookuptemplatevar(){
	$templateid=GETVAL('templateid');
	global $db;

?>
<div class="section">
<?		
	$query="select templatetypeid from templates where templateid=$templateid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$templatetypeid=$myrow['templatetypeid']+0;
	
	$query="select * from templatevars where templatetypeid=$templatetypeid order by templatevarid";
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_assoc($rs)){
		$varname=$myrow['templatevarname'];
		$vardesc=$myrow['templatevardesc'];	
		$dbvar=noapos(htmlentities($varname));
?>
<div class="listitem"><a onclick="if (document.hotspot&&document.hotspot.selection) {document.hotspot.selection.setContent('%%<?echo $dbvar;?>%%');document.hotspot.focus();}"><?echo $varname;?><br><em style="color:#666666;"><?echo $vardesc;?></em></a></div>
<?		
	}//while	
?>
</div>
<?
}