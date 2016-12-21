<?php

function listtemplates(){
	global $db; 
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=$_GET['page']+0;
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookuptemplate(gid('templatekey'));return false;">
	<div class="listsearch_">
		<input id="templatekey" class="img-mg" onkeyup="_inline_lookuptemplate(this);">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</form>
</div>

<div id="templatelist">
<?		
	}

	$soundex=GETSTR('soundex')+0;
	$sxsearch='';
	if ($soundex&&$key!='') $sxsearch=" or concat(soundex(templatename),'') like concat(soundex('$key'),'%') ";
	
	$query="select * from templates ";
	if ($key!='') $query.=" where (templatename like '%$key%' $sxsearch) ";
	$rs=sql_query($query,$db);
	$count=sql_affected_rows($db,$rs);
	
	$perpage=20;
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$perpage*$page;

	if ($maxpage>0){
?>
<div class="listpager">
<?echo $page+1;?> of <?echo $maxpage+1;?>
&nbsp;
<a href=# onclick="ajxpgn('templatelist',document.appsettings.codepage+'?cmd=slv_core__templates&key='+encodeHTML(gid('templatekey').value)+'&page=<?echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('templatelist',document.appsettings.codepage+'?cmd=slv_core__templates&key='+encodeHTML(gid('templatekey').value)+'&page=<?echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?		
	}
	
	$query.=" order by templatename limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$templateid=$myrow['templateid'];
		$templatename=$myrow['templatename'];
		$templatetypeid=$myrow['templatetypeid'];
		
		$templatetitle="$templatename"; //change this if needed
		
		$dbtemplatetitle=noapos(htmlspecialchars($templatetitle));
?>
<div class="listitem"><a onclick="showtemplate(<?echo $templateid;?>,'<?echo $dbtemplatetitle;?>',<?echo $templatetypeid;?>,function(){inittemplatetexteditor('new');});"><?echo $templatetitle;?></a></div>
<?		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a>Templates</a>';
ajxjs(self.showtemplate,'templates.js');
</script>
<?	
	}//embed mode

}

