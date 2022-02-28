<?php

include 'icl/listtemplatetypetemplates.inc.php';
include 'icl/listtemplatetypetemplatevars.inc.php';

function showtemplatetype($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=SGET('templatetypeid');
	
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$query="select * from ".TABLENAME_TEMPLATETYPES." left join templates on activetemplateid=templateid where ".TABLENAME_TEMPLATETYPES.".templatetypeid=? and ".COLNAME_GSID."=? ";
	$rs=sql_prep($query,$db,array($templatetypeid,$gsid));
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$templatetypename=$myrow['templatetypename'];
	$templatetypekey=$myrow['templatetypekey'];

	$activetemplateid=$myrow['activetemplateid'];
	if ($activetemplateid=='00000000-0000-0000-0000-000000000000') $activetemplateid=null;

	$templatename=$myrow['templatename'];
	$plugins=$myrow['plugins'];
	$classes=$myrow['classes'];
	

	header('newtitle:'.tabtitle(htmlspecialchars($templatetypename)));
	
	makechangebar('templatetype_'.$templatetypeid,"updatetemplatetype('$templatetypeid','".makegskey('updatetemplatetype_'.$templatetypeid)."');");
	makesavebar('templatetype_'.$templatetypeid);
?>
<div class="section">
	<div class="sectiontitle"><a ondblclick="toggletabdock();"><?php echo htmlspecialchars($templatetypename);?></a></div>

	<div class="col">


	<div class="inputrow">
		<div class="formlabel"><?php tr('templatetype_label_templatetypename');?>:</div>
		<input class="inpmed" onfocus="document.hotspot=this;" oninput="this.onchange();" onchange="marktabchanged('templatetype_<?php echo $templatetypeid;?>');" id="templatetypename_<?php echo $templatetypeid;?>" value="<?php echo htmlspecialchars($templatetypename);?>">
	</div>
	<div class="inputrow">
		<div class="formlabel"><?php tr('templatetype_label_templatetypekey');?>:</div>
		<input class="inpmed" id="templatetypekey_<?php echo $templatetypeid;?>" oninput="this.onchange();" onchange="marktabchanged('templatetype_<?php echo $templatetypeid;?>');" value="<?php echo htmlspecialchars($templatetypekey);?>" <?php if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>
	<div class="inputrow">
		<div class="formlabel">
		<a class="hovlink" onclick="ajxjs(<?php jsflag('showtemplate');?>,'templates.js');
 		showrelrec('activetemplateid_<?php echo $templatetypeid;?>', showtemplate,'<?php echo $activetemplateid;?>');"><?php tr('templatetype_label_activetemplateid');?></a>:</div>
		<input onfocus="lookupentity(this,'template&templatetypeid=<?php echo $templatetypeid;?>','Template');"
			onkeyup="_lookupentity(this,'template&templatetypeid=<?php echo $templatetypeid;?>','Template');"
			 oninput="this.onchange();" onchange="marktabchanged('templatetype_<?php echo $templatetypeid;?>');"
			 class="inpmed" id="activetemplateid_<?php echo $templatetypeid;?>" value="<?php echo htmlspecialchars($templatename);?>" <?php if ($activetemplateid) echo 'disabled';?>>
		<span id="activetemplateid_<?php echo $templatetypeid;?>_val2"><?php if ($activetemplateid) cancelpickup('activetemplateid_'.$templatetypeid);?></span>
		<?php makelookup('activetemplateid_'.$templatetypeid);?>
	</div>
	<div class="inputrow">
		<div class="formlabel">Plugins:</div>
		<input class="inpmed" id="templatetypeplugins_<?php echo $templatetypeid;?>" oninput="this.onchange();" onchange="marktabchanged('templatetype_<?php echo $templatetypeid;?>');" value="<?php echo htmlspecialchars($plugins);?>" <?php if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>
	<div class="inputrow">
		<div class="formlabel">Classes: (<em>styles</em> plugin must be enabled)</div>
		<input class="inpmed" id="templatetypeclasses_<?php echo $templatetypeid;?>" value="<?php echo htmlspecialchars($classes);?>" oninput="this.onchange();" onchange="marktabchanged('templatetype_<?php echo $templatetypeid;?>');" <?php if (!$user['groups']['systemplate']) echo 'disabled';?>>
	</div>

	
	<div class="inputrow">
		<button onclick="updatetemplatetype('<?php echo $templatetypeid;?>','<?php emitgskey('updatetemplatetype_'.$templatetypeid);?>');"><?php tr('button_update');?></button>
		<?php if ($user['groups']['systemplate']){?>
		&nbsp; &nbsp;
		<button class="warn" onclick="deltemplatetype('<?php echo $templatetypeid;?>','<?php emitgskey('deltemplatetype_'.$templatetypeid);?>');"><?php tr('button_delete');?></button>
		<?php }?>

	</div>


	</div>
	<div class="col">

		<div class="sectionheader"><?php tr('icon_templates');?></div>
		<div id="templatetypetemplates_<?php echo $templatetypeid;?>">
			<?php listtemplatetypetemplates($templatetypeid);?>
		</div>
		
		<?php if ($user['groups']['systemplate']){?>
		<div class="sectionheader">Variables / Macros</div>
		<div id="templatetypetemplatevars_<?php echo $templatetypeid;?>">
			<?php listtemplatetypetemplatevars($templatetypeid);?>
		</div>
		<?php }?>
	

	</div>
	<div class="clear"></div>
</div>
<?php
}
