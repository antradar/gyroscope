<?php

function showtemplate($templateid=null){
	if (!isset($templateid)) $templateid=GETVAL('templateid');
	
	global $db;
	
	$query="select * from templates,templatetypes where templates.templatetypeid=templatetypes.templatetypeid and templateid=$templateid";
	$rs=sql_query($query,$db);
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$templatename=$myrow['templatename'];
	$templatetext=$myrow['templatetext'];
	$templatetypename=$myrow['templatetypename'];
	$plugins=$myrow['plugins'];
	
	$templatetypeid=$myrow['templatetypeid'];
	$recordtitle="$templatetypename"; //change this
	$dbrecordtitle=htmlspecialchars(noapos($recordtitle));
	
	header('newtitle:'.tabtitle($templatename));
	header('parenttab: templatetype_'.$templatetypeid);
	
	makechangebar('template_'.$templateid,"updatetemplate($templateid);");
?>
<div class="section">
	<div class="sectiontitle"><?echo $templatename;?></div>

	<input type="hidden" id="templateplugins_<?echo $templateid;?>" value="<?echo $plugins;?>">

	<div class="inputrow">
		<div class="formlabel"><?tr('list_templatetype_stab');?>:
		
			<a onclick="ajxjs(self.showtemplatetype,'templatetypes.js');showtemplatetype(<?echo $templatetypeid?>,'<?echo $dbrecordtitle;?>');">
			<u><?echo $recordtitle;?></u>
			</a>
		</div>
	</div>
	
	<div style="padding-bottom:10px;line-height:2em;">
	Use this template's ID to test, instead of the class key '<u><?echo $recordtitle;?></u>'<br>
	<div style="border:solid 1px #efefef;padding:3px 10px;">$template = maketemplate(<?echo $templateid;?>, ...</div>
	</div>
		
	<div class="inputrow">
		<div class="formlabel"><?tr('template_label_templatename');?>:</div>
		<input class="inpmed" id="templatename_<?echo $templateid;?>" value="<?echo htmlspecialchars($templatename);?>" onchange="marktabchanged('template_<?echo $templateid;?>');">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('template_label_templatetext');?>:</div>
		<?makelookup('templatetext_'.$templateid);?>
		<textarea class="templatetexteditor_<?echo $templateid;?>" id="templatetext_<?echo $templateid;?>" style="width:100%;height:220px;"><?echo htmlspecialchars($templatetext);?></textarea>
	</div>

	
	<div class="inputrow">
		<button onclick="updatetemplate(<?echo $templateid;?>,<?echo $templatetypeid;?>);"><?tr('button_update');?></button>

		&nbsp; &nbsp;
		<button class="warn" onclick="deltemplate(<?echo $templateid;?>,<?echo $templatetypeid;?>);"><?tr('button_delete');?></button>


	</div>

</div>
<?
}
