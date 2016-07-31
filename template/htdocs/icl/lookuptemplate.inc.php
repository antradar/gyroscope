<?php

function lookuptemplate(){
	$templatetypeid=$_GET['templatetypeid']+0;
	$key=GETSTR('key');
	global $db;
	
	$query="select * from templates where templateid!=0 ";
	if ($templatetypeid) $query.=" and templatetypeid=$templatetypeid ";
	if ($key!='') $query.=" and templatename like '%$key%' ";
	$query.=" order by templatename ";
	$rs=sql_query($query,$db);
		
?>
<div class="section">
<?	
	while ($myrow=sql_fetch_assoc($rs)){
		$templateid=$myrow['templateid'];
		$templatename=$myrow['templatename'];
		$dbname=noapos(htmlspecialchars($templatename));
		
?>
<div class="listitem"><a onclick="picklookup('<?echo $dbname;?>',<?echo $templateid;?>);"><?echo $templatename;?></a></div>
<?		
	}//while
?>
</div>
<?		
}