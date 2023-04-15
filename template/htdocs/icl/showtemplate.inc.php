<?php

function showtemplate($templateid=null){
	if (!isset($templateid)) $templateid=SGET('templateid');
	
	global $db;

	gsguard($templateid,array(TABLENAME_TEMPLATETYPES,TABLENAME_TEMPLATES),array('templatetypeid-templatetypeid','templateid'));
	
	$user=userinfo();
	$gsid=$user['gsid'];
		
	$query="select * from ".TABLENAME_TEMPLATES.",".TABLENAME_TEMPLATETYPES." where ".TABLENAME_TEMPLATES.".templatetypeid=".TABLENAME_TEMPLATETYPES.".templatetypeid and templateid=? and ".COLNAME_GSID."=? ";
	$rs=sql_prep($query,$db,array($templateid,$gsid));
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$templatename=$myrow['templatename'];
	$templatetext=$myrow['templatetext'];
	$templatetypename=$myrow['templatetypename'];
	$plugins=$myrow['plugins'];
	
	$templatetypeid=$myrow['templatetypeid'];
	$recordtitle="$templatetypename"; //change this
	$dbrecordtitle=htmlspecialchars(noapos(htmlspecialchars($recordtitle)));
	
	header('newtitle:'.tabtitle(htmlspecialchars($templatename)));
	header('parenttab: templatetype_'.$templatetypeid);
	
	makechangebar('template_'.$templateid,"updatetemplate('$templateid','$templatetypeid','".makegskey('updatetemplate_'.$templateid)."');");
	makesavebar('template_'.$templateid);
?>
<div class="section">
	<div class="sectiontitle">
		<a id="vrectitle_templatename_<?php echo $templateid;?>" onclick="gid('vrectitle_templatename_<?php echo $templateid;?>').style.display='none';gid('mrectitle_templatename_<?php echo $templateid;?>').style.display='inline';">
			<?php echo htmlspecialchars($templatename);?> <span class="edithover"></span>
		</a>
		<span id="mrectitle_templatename_<?php echo $templateid;?>" style="display:none;">
			<input class="inpmed" id="dir_templatename_<?php echo $templateid;?>" 
				value="<?php echo htmlspecialchars($templatename);?>" 
			>
			<button onclick="updatetemplate_rectitle(<?php echo $templateid;?>);">Update</button>
			&nbsp;
			<button class="trivial" onclick="gid('vrectitle_templatename_<?php echo $templateid;?>').style.display='inline';gid('mrectitle_templatename_<?php echo $templateid;?>').style.display='none';">Cancel</button>
		</span>
	</div><!-- sectiontitle -->
		
	<input type="hidden" id="templateplugins_<?php echo $templateid;?>" value="<?php echo $plugins;?>">

	<div class="inputrow">
		<div class="formlabel"><?php tr('list_templatetype_stab');?>:
		
			<a onclick="ajxjs(<?php jsflag('showtemplatetype');?>,'templatetypes.js');showtemplatetype('<?php echo $templatetypeid?>','<?php echo $dbrecordtitle;?>');">
			<u><?php echo htmlspecialchars($recordtitle);?></u>
			</a>
		</div>
	</div>
	
	<div style="padding-bottom:10px;line-height:2em;">
	Use this template's ID to test, instead of the class key '<u><?php echo htmlspecialchars($recordtitle);?></u>'<br>
	<div style="border:solid 1px #efefef;padding:3px 10px;">$template = maketemplate('<?php echo $templateid;?>', ...</div>
	</div>
		
	<div class="inputrow" style="display:none;">
		<div class="formlabel"><?php tr('template_label_templatename');?>:</div>
		<input onfocus="document.hotspot=this;" class="inpmed" id="templatename_<?php echo $templateid;?>" value="<?php echo htmlspecialchars($templatename);?>" oninput="this.onchange();" onchange="marktabchanged('template_<?php echo $templateid;?>');">
	</div>
	<div class="inputrow" style="position:relative;">
		<div class="formlabel"><a class="hovlink" onclick="pullupeditor(this);"><?php tr('template_label_templatetext');?></a>:</div>
		<?php 
		//makelookup('templatetext_'.$templateid);
		?>
		<textarea class="templatetexteditor_<?php echo $templateid;?>" id="templatetext_<?php echo $templateid;?>" style="width:100%;height:500px;"><?php echo htmlspecialchars($templatetext);?></textarea>
		<?php makehelp('editortip'.$templateid,'richtexteditor',1);?>
	</div>

	
	<div class="inputrow buttonbelt">
		<button onclick="updatetemplate('<?php echo $templateid;?>','<?php echo $templatetypeid;?>','<?php emitgskey('updatetemplate_'.$templateid);?>');"><?php tr('button_update');?></button>

		&nbsp; &nbsp;
		<button class="warn" onclick="deltemplate('<?php echo $templateid;?>','<?php echo $templatetypeid;?>','<?php emitgskey('deltemplate_'.$templateid);?>');"><?php tr('button_delete');?></button>


	</div>

</div>
<?php
}
