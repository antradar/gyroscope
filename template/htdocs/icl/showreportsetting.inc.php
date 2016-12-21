<?php

function showreportsetting($reportid=null){
	if (!isset($reportid)) $reportid=GETVAL('reportid');
	
	global $db;
	global $userroles;
	
	$user=userinfo();
	if (!$user['groups']['reportsettings']) apperror('access denied');
	
	$query="select * from ".TABLENAME_REPORTS." where reportid=$reportid";
	$rs=sql_query($query,$db);
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$reportname=$myrow['reportname'];
	$reportgroup=$myrow['reportgroup'];
	$reportkey=$myrow['reportkey'];
	$reportfunc=$myrow['reportfunc'];
	$reportdesc=$myrow['reportdesc'];
	$reportgroups=explode('|',$myrow['reportgroupnames']);
	$gyrosys=$myrow['gyrosys'];

	$reportkey=trim(str_replace('/','',$reportkey));
	$pfn="icl/rpt${reportkey}.inc.php";
	

	header('newtitle:'.base64_encode($reportname));
?>
<div class="section">
	<div class="sectiontitle"><?echo $reportname;?></div>

	<?if (!file_exists($pfn)&&$reportfunc==''){?>
<div class="warnbox">
	This report has not been implemented with a default handler. Make sure "rpt<?echo $reportkey;?>" is handled.
</div>	
	<?}?>
	
	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportname');?>:</div>
		<input class="inpmed" id="reportname_<?echo $reportid;?>" value="<?echo htmlspecialchars($reportname);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportgroup');?>:</div>
		<input class="inpmed" id="reportgroup_<?echo $reportid;?>" value="<?echo htmlspecialchars($reportgroup);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportkey');?>:</div>
		<input class="inpmed" id="reportkey_<?echo $reportid;?>" value="<?echo htmlspecialchars($reportkey);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportfunc');?>:</div>
		<input class="inpmed" id="reportfunc_<?echo $reportid;?>" value="<?echo htmlspecialchars($reportfunc);?>">
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('reportsetting_label_reportdesc');?>:</div>
		<textarea class="inplong" style="height:60px;" id="reportdesc_<?echo $reportid;?>"><?echo htmlspecialchars($reportdesc);?></textarea>
	</div>

	



	</div>
	<div class="col">
		<div class="sectionheader">Access Control</div>
		<div class="infobox">
			Users with Any of the following rights can see this report:
		</div>
		
		<?
		$jsroles='';
		
		foreach ($userroles as $role=>$label){
			$jsroles.=",'$role' ";
		?>
		<div style="padding-left:10px;margin-bottom:3px;">
			<input type="checkbox" id="reportrole_<?echo $role;?>_<?echo $reportid;?>" <?if (in_array($role,$reportgroups)) echo 'checked';?>> 
			<label for="reportrole_<?echo $role;?>_<?echo $reportid;?>"><?echo $label;?></label>
		</div>
		<?
		}
		
		$jsroles='['.trim($jsroles,',').']';
		?>

	</div>
	<div class="clear"></div>
	

	<div class="inputrow">
		<button onclick="updatereportsetting(<?echo $reportid;?>,<?echo $jsroles;?>);"><?tr('button_update');?></button>
	<?if (!$gyrosys){?>
		&nbsp; &nbsp;
		<button class="warn" onclick="delreportsetting(<?echo $reportid;?>);"><?tr('button_delete');?></button>
	<?}?>
	</div>	
	
</div>
<?
}
