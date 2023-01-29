<?php

function lookuptemplatevar(){
	$templateid=SGET('templateid');
	global $db;
	$key=SGET('varkey');
	$key=str_replace('%','',$key);
	
	$mode=SGET('mode');

	if ($mode!='embed'){
?>
<div class="section" style="position:relative;">

	<div class="listsearch">
	<div class="listsearch_">
		<input id="templatetypelookupkey" value="<?php echo htmlspecialchars($key);?>" class="img-mg" onkeyup="_inline_lookuptemplatetypelookup(this,<?php echo $templateid;?>);" autocomplete="off">
		<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('templatetypelookupkey');_inline_lookuptemplatetypelookup(gid('templatetypelookupkey'),<?php echo $templateid;?>)">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</div>
	
	<div id="templatetypelookuplist">
<?php	
	}
		
	$query="select templatetypeid from templates where templateid=?";
	$rs=sql_prep($query,$db,$templateid);
	$myrow=sql_fetch_assoc($rs);
	$templatetypeid=$myrow['templatetypeid'];

	$params=array($templatetypeid);
	
	$keyorder='';
	if ($key!='') {
		$keyorder="templatevarname=? desc,";
	}
	
	$query="select * from templatevars where templatetypeid=? ";
	if ($key!=''){
		$query.=" and (templatevarname like ? or templatevardesc like ?) ";
		array_push($params,"%$key%","%$key%");	
	}
	$query.=" order by $keyorder templatevarid";
	if ($key!=''){
		array_push($params,$key);	
	}
	$rs=sql_prep($query,$db,$params);
		
	while ($myrow=sql_fetch_assoc($rs)){

		$varname=$myrow['templatevarname'];
		$vardesc=$myrow['templatevardesc'];	
		$dbvar=noapos(htmlentities($varname));
?>
<div class="listitem" style="<?php if ($varname==$key) echo 'font-weight:bold;background:#ffffcc;';?>"><a onclick="if (document.hotspot&&document.hotspot.onChange) document.hotspot.onChange.dispatch();if (document.hotspot&&document.hotspot.selection) {document.hotspot.selection.setContent('%%<?php echo $dbvar;?>%%');document.hotspot.focus();}"><?php echo $varname;?><br><em style="color:#666666;"><?php echo htmlspecialchars($vardesc);?></em></a></div>
<?php		
	}//while	
	
	if ($mode!='embed'){
?>
<?php
	
	makehelp('templatevar','templatevar',1);
?>
	</div>
</div>
<?php
	}
}