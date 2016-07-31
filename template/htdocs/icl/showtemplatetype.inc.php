<?php

include 'icl/listtemplatetypetemplates.inc.php';
include 'icl/listtemplatetypetemplatevars.inc.php';

function showtemplatetype($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=GETVAL('templatetypeid');
	
	global $db;
	
	$user=userinfo();
	
	$query="select * from templatetypes left join templates on activetemplateid=templateid where templatetypes.templatetypeid=$templatetypeid";
	$rs=sql_query($query,$db);
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$templatetypename=$myrow['templatetypename'];
	$templatetypekey=$myrow['templatetypekey'];
	$activetemplateid=$myrow['activetemplateid'];
	$templatename=$myrow['templatename'];
	$plugins=$myrow['plugins'];
	$classes=$myrow['classes'];
	

	header('newtitle:'.base64_encode($templatetypename));
?>
<div class="section">
	<div class="sectiontitle"><?echo $templatetypename;?></div>

	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?tr('templatetype_label_templatetypename');?>:</div>
		<input class="inpmed" id="templatetypename_<?echo $templatetypeid;?>" value="<?echo htmlspecialchars($templatetypename);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?tr('templatetype_label_templatetypekey');?>:</div>
		<input class="inpmed" id="templatetypekey_<?echo $templatetypeid;?>" value="<?echo htmlspecialchars($templatetypekey);?>" <?if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>
	<div class="inputrow">
		<div class="formlabel">
		<a class="hovlink" onclick="ajxjs(self.showtemplate,'templates.js');
 		showrelrec('activetemplateid_<?echo $templatetypeid;?>', showtemplate,<?echo $activetemplateid+0;?>);"><?tr('templatetype_label_activetemplateid');?></a>:</div>
		<input onfocus="lookupentity(this,'template&templatetypeid=<?echo $templatetypeid;?>','Template');"
			onkeyup="_lookupentity(this,'template&templatetypeid=<?echo $templatetypeid;?>','Template');"
			 class="inpmed" id="activetemplateid_<?echo $templatetypeid;?>" value="<?echo htmlspecialchars($templatename);?>" <?if ($activetemplateid) echo 'disabled';?>>
		<span id="activetemplateid_<?echo $templatetypeid;?>_val2"><?if ($activetemplateid) cancelpickup('activetemplateid_'.$templatetypeid);?></span>
	</div>
	<div class="inputrow">
		<div class="formlabel">Plugins:</div>
		<input class="inpmed" id="templatetypeplugins_<?echo $templatetypeid;?>" value="<?echo htmlspecialchars($plugins);?>" <?if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>
	<div class="inputrow">
		<div class="formlabel">Classes: (<em>styles</em> plugin must be enabled)</div>
		<input class="inpmed" id="templatetypeclasses_<?echo $templatetypeid;?>" value="<?echo htmlspecialchars($classes);?>" <?if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>

	
	<div class="inputrow">
		<button onclick="updatetemplatetype(<?echo $templatetypeid;?>);"><?tr('button_update');?></button>
		<?if ($user['groups']['systemplate']){?>
		&nbsp; &nbsp;
		<button class="warn" onclick="deltemplatetype(<?echo $templatetypeid;?>);"><?tr('button_delete');?></button>
		<?}?>

	</div>


	</div>
	<div class="col">

		<div class="sectionheader"><?tr('icon_templates');?></div>
		<div id="templatetypetemplates_<?echo $templatetypeid;?>">
			<?listtemplatetypetemplates($templatetypeid);?>
		</div>
		
		<?if ($user['groups']['systemplate']){?>
		<div class="sectionheader">Variables / Macros</div>
		<div id="templatetypetemplatevars_<?echo $templatetypeid;?>">
			<?listtemplatetypetemplatevars($templatetypeid);?>
		</div>
		<?}?>
	

	</div>
	<div class="clear"></div>
</div>
<?
}
