<?php

function listreportsettings(){
	global $db; 
	global $lang;
	global $deflang;
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;		

	$mode=SGET('mode');
	$key=SGET('key',0);
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookupreportsetting(gid('reportsettingkey'));return false;">
	<div class="listsearch_">
		<input onfocus="document.hotspot=this;" id="reportsettingkey" class="img-mg" onkeyup="_inline_lookupreportsetting(this);">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</form>
	<?php if ($user['groups']['devreports']){?>
	<div style="padding-top:10px;">
	<a class="recadder" onclick="addtab('reportsetting_new','<img src=&quot;imgs/t.gif&quot; class=&quot;ico-setting&quot;><?php tr('list_reportsetting_add_tab');?>','newreportsetting');"> <img src="imgs/t.gif" class="img-addrec"><?php tr('list_reportsetting_add');?></a>
	</div>
	<?php }?>
</div>

<div id="reportsettinglist">
<?php		
	}

	$params=array($gsid,$syslevel);
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) ";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=?)";
	
	$soundex=intval(SGET('soundex'));
	$sxsearch='';
	if ($soundex&&$key!='') $sxsearch=" or concat(soundex(reportname_$lang),'') like concat(soundex(?),'%') ";
	
	if ($key!='') {
		$query.=" and (lower(reportname_$lang) like lower(?) or lower(reportgroup_$lang) like lower(?) $sxsearch) ";
		array_push($params,"%$key%","%$key%");
		if ($sxsearch){
			array_push($params,$key);	
		}
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
<a href=# onclick="ajxpgn('reportsettinglist',document.appsettings.codepage+'?cmd=slv_core__reportsettings&key='+encodeHTML(gid('reportsettingkey').value)+'&page=<?php echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('reportsettinglist',document.appsettings.codepage+'?cmd=slv_core__reportsettings&key='+encodeHTML(gid('reportsettingkey').value)+'&page=<?php echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?php		
	}
	
	$query.=" order by reportgroup_$lang, reportname_$lang limit $start,$perpage";	
	$rs=sql_prep($query,$db,$params);

	$lastgroup='';
			
	while ($myrow=sql_fetch_array($rs)){
		$reportid=$myrow['reportid'];
		$reportname=$myrow['reportname_'.$lang];
		if ($reportname=='') $reportname=$myrow['reportname_'.$deflang];
		$reportgroup=$myrow['reportgroup_'.$lang];
		
		$reportsettingtitle="$reportname"; //change this if needed
		
		$dbreportsettingtitle=noapos(htmlspecialchars(htmlspecialchars($reportsettingtitle)));
		
		if ($lastgroup!=$reportgroup){
?>
<div class="sectionheader"><?php echo $reportgroup;?></div>
<?php			
			$lastgroup=$reportgroup;
		}
		
?>
<div class="listitem"><a onclick="showreportsetting('<?php echo $reportid;?>','<?php echo $dbreportsettingtitle;?>');"><?php echo htmlspecialchars($reportsettingtitle);?></a></div>
<?php		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_reportsettings');?></a>';
ajxjs(<?php jsflag('showreportsetting');?>,'reportsettings.js');
</script>
<?php	
	}//embed mode

}

