<?php

function listtemplatetypetemplatevars($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=SGET('templatetypeid');
	global $db;

	gsguard($templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
			
	$query="select * from ".TABLENAME_TEMPLATETYPES." where templatetypeid=?";
	$rs=sql_prep($query,$db,$templatetypeid);
	$myrow=sql_fetch_assoc($rs);
	$templatetypekey=$myrow['templatetypekey'];
	
	$query="select * from ".TABLENAME_TEMPLATEVARS." where templatetypeid=? order by templatevarid";
	$rs=sql_prep($query,$db,$templatetypeid);
	$vars=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		$templatevarid=$myrow['templatevarid'];
		$varname=$myrow['templatevarname'];
		$vardesc=$myrow['templatevardesc'];
		array_push($vars,$myrow);
	?>
	<div style="margin-bottom:10px;">
		<span class="labelbutton"><?php echo $varname;?></span>
		&nbsp; &nbsp; <a onclick="deltemplatevar('<?php echo $templatevarid;?>','<?php echo $templatetypeid;?>','<?php emitgskey('deltemplatevar-'.$templatevarid);?>');"><img src="imgs/t.gif" class="img-del"></a> &nbsp; 
		<span><?php echo $vardesc;?></span>
		
	</div>
	<?php	
	}//while
	
?>
	<div style="padding-top:10px;margin-bottom:10px;"><b>Add a new variable:</b> &nbsp; <a class="labelbutton" onclick="showhide('quickvars_<?php echo $templatetypeid;?>_');">quick edit</a></div>
	<div id="quickvars_<?php echo $templatetypeid;?>_" style="display:none;margin-bottom:20px;">
		<textarea class="inplong" id="quickvars_<?php echo $templatetypeid;?>"><?php foreach ($vars as $var){echo $var['templatevarname'].'|'.$var['templatevardesc']."\r\n";}?></textarea>
		<div class="buttonbelt">
			<button onclick="batchsavetemplatevars('<?php echo $templatetypeid;?>','<?php emitgskey('batchsavetemplatevars_'.$templatetypeid);?>');">Update</button>
		</div>
	</div>
	<div class="inputrow">
		<div class="formlabel">Variable:</div>
		<input class="inpshort" id="templatevarname_<?php echo $templatetypeid;?>">
	</div>
	<div class="inputrow">
		<div class="formlabel">Description:</div>
		<input class="inplong" id="templatevardesc_<?php echo $templatetypeid;?>">
	</div>
	<div class="inputrow buttonbelt">
		<button onclick="addtemplatevar('<?php echo $templatetypeid;?>','<?php emitgskey('addtemplatevar_'.$templatetypeid);?>');">Add</button>
	</div>
	
	<div style="padding:10px 0;">
		<a onclick="showhide('templatevarscode_<?php echo $templatetypeid;?>');" class="hovlink">show binding instructions</a>
	</div>
	
	<div id="templatevarscode_<?php echo $templatetypeid;?>" style="display:none;">
		<textarea class="inplong">
	//include 'maketemplate.inc.php';
	$template=maketemplate('<?php echo $templatetypekey;?>',array(

<?php
	foreach ($vars as $var){
	?>
	'<?php echo $var['templatevarname'];?>'=>$<?php echo $var['templatevarname'];?>, //<?php echo $var['templatevardesc'];?> 
<?php	
	}
?>	
	));
	echo $template['content']; //returned object contains both template name and content
			
</textarea>
	</div>
<?php
		
}