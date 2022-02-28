<?php

function edithelptopic($helptopicid=null){
	if (!isset($helptopicid)) $helptopicid=SGET('helptopicid');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	if (!$user['groups']['helpedit']) die('access denied');	
	
	if (!$user['groups']['helpedit']) die('access denied');
	
	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?";
	$rs=sql_prep($query,$db,array($helptopicid));
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$helptopictitle=$myrow['helptopictitle'];
	$helptopickeywords=$myrow['helptopickeywords'];
	$helptopictext=$myrow['helptopictext'];
	

	header('newtitle:'.tabtitle('<img src="imgs/t.gif" class="ico-setting">'.htmlspecialchars($helptopictitle)));
	makechangebar('helptopic_'.$helptopicid,"updatehelptopic('$helptopicid','".makegskey('updatehelptopic_'.$helptopicid)."');");
	makesavebar('helptopic_'.$helptopicid);
?>
<div class="section">
	<div class="sectiontitle"><?php echo htmlspecialchars($helptopictitle);?></div>


	<div class="inputrow">
		<div class="formlabel"><?php tr('helptopic_label_helptopictitle');?>:</div>
		<input class="inpmed" id="helptopictitle_<?php echo $helptopicid;?>" value="<?php echo htmlspecialchars($helptopictitle);?>" oninput="this.onchange();" onchange="marktabchanged('helptopic_<?php echo $helptopicid;?>');">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('helptopic_label_helptopickeywords');?>:</div>
		<input class="inplong" id="helptopickeywords_<?php echo $helptopicid;?>" value="<?php echo htmlspecialchars($helptopickeywords);?>" oninput="this.onchange();" onchange="marktabchanged('helptopic_<?php echo $helptopicid;?>');">
	</div>

	<div class="inputrow">
	<div class="formlabel"><a class="hovlink" onclick="pullupeditor(this);">Help Text:</a>
	<?php makelookup('helptopictext_'.$helptopicid);?>
	<textarea style="width:100%;height:200px;" 
		class="helptopictexteditor_<?php echo $helptopicid;?>" 
		id="helptopictext_<?php echo $helptopicid;?>"><?php echo htmlspecialchars($helptopictext);?></textarea>
	</div>
	
	<div class="inputrow">
		<button onclick="updatehelptopic('<?php echo $helptopicid;?>','<?php emitgskey('updatehelptopic_'.$helptopicid);?>');"><?php tr('button_update');?></button>

		&nbsp; &nbsp;
		<button class="warn" onclick="delhelptopic('<?php echo $helptopicid;?>','<?php emitgskey('delhelptopic_'.$helptopicid);?>');"><?php tr('button_delete');?></button>


	</div>	
	
</div>
<?php
}
