<?php

function listtemplatetypetemplatevars($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=GETVAL('templatetypeid');
	global $db;
	
	$query="select * from templatetypes where templatetypeid=$templatetypeid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$templatetypekey=$myrow['templatetypekey'];
	
	$query="select * from templatevars where templatetypeid=$templatetypeid order by templatevarid";
	$rs=sql_query($query,$db);
	$vars=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		$templatevarid=$myrow['templatevarid'];
		$varname=$myrow['templatevarname'];
		$vardesc=$myrow['templatevardesc'];
		array_push($vars,$myrow);
	?>
	<div style="margin-bottom:10px;">
		<span class="labelbutton"><?echo $varname;?></span>
		&nbsp; &nbsp; <a onclick="deltemplatevar(<?echo $templatevarid;?>,<?echo $templatetypeid;?>);"><img src="imgs/t.gif" class="img-del"></a> &nbsp; 
		<span><?echo $vardesc;?></span>
		
	</div>
	<?	
	}//while
	
?>
	<div style="padding-top:10px;margin-bottom:10px;"><b>Add a new variable:</b> &nbsp; <a class="labelbutton" onclick="showhide('quickvars_<?echo $templatetypeid;?>_');">quick edit</a></div>
	<div id="quickvars_<?echo $templatetypeid;?>_" style="display:none;margin-bottom:20px;">
		<textarea class="inplong" id="quickvars_<?echo $templatetypeid;?>"><?foreach ($vars as $var){echo $var['templatevarname'].'|'.$var['templatevardesc']."\r\n";}?></textarea>
		<button onclick="batchsavetemplatevars(<?echo $templatetypeid;?>);">Update</button>
	</div>
	<div class="inputrow">
		<div class="formlabel">Variable:</div>
		<input class="inpshort" id="templatevarname_<?echo $templatetypeid;?>">
	</div>
	<div class="inputrow">
		<div class="formlabel">Description:</div>
		<input class="inplong" id="templatevardesc_<?echo $templatetypeid;?>">
	</div>
	<div class="inputrow">
		<button onclick="addtemplatevar(<?echo $templatetypeid;?>);">Add</button>
	</div>
	
	<div style="padding:10px 0;">
		<a onclick="showhide('templatevarscode_<?echo $templatetypeid;?>');" class="hovlink">show binding instructions</a>
	</div>
	
	<div id="templatevarscode_<?echo $templatetypeid;?>" style="display:none;">
		<textarea class="inplong">
	//include 'maketemplate.inc.php';
	$template=maketemplate('<?echo $templatetypekey;?>',array(

<?
	foreach ($vars as $var){
	?>
	'<?echo $var['templatevarname'];?>'=>$<?echo $var['templatevarname'];?>, //<?echo $var['templatevardesc'];?> 
<?	
	}
?>	
	));
	echo $template['content']; //returned object contains both template name and content
			
</textarea>
	</div>
<?
		
}