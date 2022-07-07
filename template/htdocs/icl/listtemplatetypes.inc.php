<?php

function listtemplatetypes(){
	global $db; 
	$mode=SGET('mode');
	$key=SGET('key');
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	header('listviewtitle:'.tabtitle(_tr('icon_templatetypes')));
	header('listviewflag:showtemplatetype');
	header('listviewjs:templatetypes.js');	
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookuptemplatetype(gid('templatetypekey'));return false;">
	<div class="listsearch_">
		<input onfocus="document.hotspot=this;" id="templatetypekey" class="img-mg" onkeyup="_inline_lookuptemplatetype(this);" autocomplete="off">
		<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('templatetypekey');_inline_lookuptemplatetype(gid('templatetypekey'));">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</form>

	<?php
	if ($user['groups']['systemplate']){
	?>
	<div style="padding-top:10px;">
	<a class="recadder" onclick="addtab('templatetype_new','<?php tr('list_templatetype_add_tab');?>','newtemplatetype');"> <img src="imgs/t.gif" class="img-addrec"><?php tr('list_templatetype_add');?></a>
	</div>
	<?php
	}
	?>
</div>

<div id="templatetypelist">
<?php		
	}

	$params=array($gsid);
	$query="select * from ".TABLENAME_TEMPLATETYPES." where ".COLNAME_GSID."=? ";
	
	$soundex=intval(SGET('soundex'));
	$sxsearch='';
	if ($soundex&&$key!='') $sxsearch=" or concat(soundex(templatetypename),'') like concat(soundex(?),'%') ";
	
	if ($key!=''){
		$query.=" and (templatetypename like ? or templatetypekey like ? $sxsearch) ";
		array_push($params,"%$key%","%$key%");
		if ($soundex) array_push($params,$key);
	}
		
	$rs=sql_prep($query,$db,$params);
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
<?php echo $page+1;?> of <?php echo $maxpage+1;?>
&nbsp;
<a href=# onclick="ajxpgn('templatetypelist',document.appsettings.codepage+'?cmd=slv_core__templatetypes&key='+encodeHTML(gid('templatetypekey').value)+'&page=<?php echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('templatetypelist',document.appsettings.codepage+'?cmd=slv_core__templatetypes&key='+encodeHTML(gid('templatetypekey').value)+'&page=<?php echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?php		
	}
	
	$query.=" order by templatetypename limit $start,$perpage ";	
	
	$rs=sql_prep($query,$db,$params);
	
	while ($myrow=sql_fetch_array($rs)){
		$templatetypeid=$myrow['templatetypeid'];
		$templatetypename=$myrow['templatetypename'];
		
		$templatetypetitle="$templatetypename"; //change this if needed
		
		$dbtemplatetypetitle=htmlspecialchars(noapos(htmlspecialchars($templatetypetitle)));
?>
<div class="listitem"><a onclick="showtemplatetype('<?php echo $templatetypeid;?>','<?php echo $dbtemplatetypetitle;?>');"><?php echo htmlspecialchars($templatetypetitle);?></a></div>
<?php		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_templatetypes');?></a>';
ajxjs(<?php jsflag('showtemplatetype');?>,'templatetypes.js');
</script>
<?php	
	}//embed mode

}

