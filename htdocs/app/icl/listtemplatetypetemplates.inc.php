<?php

function listtemplatetypetemplates($templatetypeid=null){
	if (!isset($templatetypeid)) $templatetypeid=SGET('templatetypeid');
	
	global $db;
	
	gsguard($templatetypeid,TABLENAME_TEMPLATETYPES,'templatetypeid');
		
	$query="select * from ".TABLENAME_TEMPLATES.",".TABLENAME_TEMPLATETYPES." where ".TABLENAME_TEMPLATES.".templatetypeid=? and ".TABLENAME_TEMPLATES.".templatetypeid=".TABLENAME_TEMPLATETYPES.".templatetypeid order by templatename";
	
	$rs=sql_prep($query,$db,array($templatetypeid));
	
	?>
	<table>
	<?php
	
	while ($myrow=sql_fetch_array($rs)){
		$templateid=$myrow['templateid'];
		$templatename=$myrow['templatename'];
		$recordtitle="$templatename"; //change this
		$dbrecordtitle=htmlspecialchars(htmlspecialchars(noapos($recordtitle)));
		
	?>
	<tr>
		<td>#<?php echo $templateid;?></td>
		<td><a class="hovlink" onclick="ajxjs(self.showtemplate,'templates.js');showtemplate('<?php echo $templateid;?>','<?php echo $dbrecordtitle;?>');"><?php echo htmlspecialchars($recordtitle);?></a></td>
	</tr>
	<?php
	}//while
	?>
	</table>
	<div class="listbar">
		<a class="recadder" onclick="ajxjs(self.showtemplate,'templates.js');addtab('template_new','<?php tr('list_template_add_tab');?>','newtemplate&templatetypeid=<?php echo $templatetypeid;?>');">
			<img src="imgs/t.gif" class="img-addrec" width="18" height="18"> <?php tr('list_template_add');?>
		</a>	
	</div>
	<?php

}
