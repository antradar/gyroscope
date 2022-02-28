<?php

function lookuptemplate(){
	$templatetypeid=$_GET['templatetypeid'];
	$key=SGET('key');
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
			
	$params=array($gsid);
	$query="select templates.* from templates,templatetypes where templates.templatetypeid=templatetypes.templatetypeid and gsid=? ";
	
	if ($templatetypeid) {$query.=" and templates.templatetypeid=? ";array_push($params,$templatetypeid);}
	if ($key!='') {$query.=" and templatename like ? ";array_push($params,"%$key%");}
	$query.=" order by templatename ";
	$rs=sql_prep($query,$db,$params);
			
?>
<div class="section">
<?php	
	while ($myrow=sql_fetch_assoc($rs)){
		$templateid=$myrow['templateid'];
		$templatename=$myrow['templatename'];
		$dbname=noapos(htmlspecialchars($templatename));
		
?>
<div class="listitem"><a onclick="picklookup('<?php echo $dbname;?>','<?php echo $templateid;?>');"><?php echo htmlspecialchars($templatename);?></a></div>
<?php		
	}//while
?>
</div>
<?php		
}