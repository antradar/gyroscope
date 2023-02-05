<?php

function showreportsetting($reportid=null){
	if (!isset($reportid)) $reportid=SGET('reportid');
	
	global $db;
	global $userroles;
	global $lang;
	global $deflang;
	global $userrolelocks;
	
	//vendor auth 1
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	if (!$user['groups']['reportsettings']) apperror('access denied');
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) and reportid=?";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=?) and reportid=?";
	
	$rs=sql_prep($query,$db,array($gsid,$syslevel,$reportid));
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$reportname=$myrow['reportname_'.$lang];
	$reportgroup=$myrow['reportgroup_'.$lang];
	$reportkey=$myrow['reportkey'];
	$reportfunc=$myrow['reportfunc'];
	$reportdesc=$myrow['reportdesc_'.$lang];
	$reportgroups=explode('|',$myrow['reportgroupnames']);
	$gyrosys=$myrow['gyrosys'];
	$rptgsid=$myrow[COLNAME_GSID];

	$reportkey=trim(str_replace('/','',$reportkey));
	$pfn="icl/rpt${reportkey}.inc.php";
	
	$bingo=$myrow['bingo'];
	
	header('newtitle:'.tabtitle('<img src="imgs/t.gif" class="ico-setting">'.htmlspecialchars($reportname)));
?>
<div class="section">
	<div class="sectiontitle"><?php echo htmlspecialchars($reportname);?></div>
	

	<?php if ($bingo){
	?>
	<div class="warnbox">
		This report is run via Bingo Bridge
	</div>
	<?php		
	}?>	

	<?php if (!$bingo&&!file_exists($pfn)&&$reportfunc==''){?>
<div class="warnbox">
	This report has not been implemented with a default handler. Make sure "rpt<?php echo $reportkey;?>" is handled.
</div>	
	<?php }?>
	
	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportname');?>:</div>
		<input class="inpmed" onfocus="document.hotspot=this;" id="reportname_<?php echo $reportid;?>" value="<?php echo htmlspecialchars($reportname);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportgroup');?>:</div>
		<input class="inpmed" onfocus="document.hotspot=this;" id="reportgroup_<?php echo $reportid;?>" value="<?php echo htmlspecialchars($reportgroup);?>">
	</div>
	<div <?php if (!$user['groups']['devreports']) echo 'style="display:none;"';?>>
		<div class="inputrow">
			<div class="formlabel"><?php tr('reportsetting_label_reportkey');?>:</div>
			<input class="inpmed" id="reportkey_<?php echo $reportid;?>" value="<?php echo htmlspecialchars($reportkey);?>">
		</div>
		<div class="inputrow">
			<div class="formlabel"><?php tr('reportsetting_label_reportfunc');?>:</div>
			<input class="inpmed" id="reportfunc_<?php echo $reportid;?>" value="<?php echo htmlspecialchars($reportfunc);?>">
		</div>
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('reportsetting_label_reportdesc');?>:</div>
		<textarea onfocus="document.hotspot=this;" class="inplong" style="height:60px;" id="reportdesc_<?php echo $reportid;?>"><?php echo htmlspecialchars($reportdesc);?></textarea>
	</div>


	</div>
	<div class="col">
		<div class="sectionheader">Access Control</div>
		<div class="infobox">
			Users with Any of the following rights can see this report:
		</div>
		
		<?php
		$allroles=$userroles;

		//vendor auth 2
		
		$jsroles='';
		
		foreach ($allroles as $role=>$label){
			//if (in_array($role,$userrolelocks)&&!$user['groups'][$role]) continue; //comment out to show, but grey out flags
			if (!in_array($role,$userrolelocks)||isset($user['groups'][$role])) $jsroles.=",'$role' ";
		?>
		<div style="padding-left:10px;margin-bottom:3px;">
			<input <?php if (in_array($role,$userrolelocks)&&!isset($user['groups'][$role])) echo 'disabled';?> <?php if ($rptgsid==0) echo 'disabled';?> type="checkbox" id="reportrole_<?php echo $role;?>_<?php echo $reportid;?>" <?php if (in_array($role,$reportgroups)) echo 'checked';?>> 
			<label for="reportrole_<?php echo $role;?>_<?php echo $reportid;?>"><?php echo $label;?></label>
		</div>
		<?php
		}
		
		$jsroles='['.trim($jsroles,',').']';
		?>

	</div>
	<div class="clear"></div>
	

	<div class="inputrow buttonbelt">
		<button <?php if ($rptgsid==0) echo 'class="disabled"';?>onclick<?php if ($rptgsid==0) echo 'a';?>="updatereportsetting('<?php echo $reportid;?>',<?php echo $jsroles;?>,'<?php emitgskey('updatereportsetting_'.$reportid);?>');"><?php tr('button_update');?></button>
	<?php if (!$gyrosys&&$user['groups']['devreports']){?>
		&nbsp; &nbsp;
		<button class="warn" onclick="delreportsetting('<?php echo $reportid;?>','<?php emitgskey('delreportsetting_'.$reportid);?>');"><?php tr('button_delete');?></button>
	<?php }?>
	</div>	
	
</div>
<?php
}
