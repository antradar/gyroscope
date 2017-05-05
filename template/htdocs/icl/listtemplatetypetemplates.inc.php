<?php

function listtemplatetypetemplates($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=GETVAL('templatetypeid');
	
	global $db;
	
	$query="select * from templates,templatetypes where templates.templatetypeid=$templatetypeid and templates.templatetypeid=templatetypes.templatetypeid order by templatename";
	
	$rs=sql_query($query,$db);
	
	?>
	<table>
	<?
	
	while ($myrow=sql_fetch_array($rs)){
		$templateid=$myrow['templateid'];
		$templatename=$myrow['templatename'];
		$recordtitle="$templatename"; //change this
		$dbrecordtitle=htmlspecialchars(noapos($recordtitle));
		
	?>
	<tr>
		<td>#<?echo $templateid;?></td>
		<td><a class="hovlink" onclick="ajxjs(self.showtemplate,'templates.js');showtemplate(<?echo $templateid;?>,'<?echo $dbrecordtitle;?>','<?echo $templatetypeid;?>');"><?echo $recordtitle?></a></td>
	</tr>
	<?
	}//while
	?>
	</table>
	<div class="listbar">
		<a class="recadder" onclick="ajxjs(self.showtemplate,'templates.js');addtab('template_new','<?tr('list_template_add_tab');?>','newtemplate&templatetypeid=<?echo $templatetypeid;?>');">
			<img src="imgs/t.gif" class="img-addrec" width="18" height="18"> <?tr('list_template_add');?>
		</a>	
	</div>
	<?

}
