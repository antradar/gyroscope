<?php

include 'models/reports.list.php';

function listreportsettings($ctx=null){
	if (isset($ctx)) $db=$ctx->db; else global $db; 
	global $lang;
	global $deflang;
	
	$user=userinfo($ctx);
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;		

	$mode=SGET('mode',1,$ctx);
	$key=SGET('key',0,$ctx);
	
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

	$soundex=intval(SGET('soundex'));
	
	$res=reports_list($ctx,$gsid,$key,$lang,$page,$syslevel,$soundex);
	$maxpage=$res['maxpage'];
	$page=$res['page'];
	$recs=$res['recs'];

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
	

	$lastgroup='';
			
	foreach ($recs as $myrow){
		$reportid=$myrow['reportid'];
		$reportname=$myrow['reportname_'.$lang];
		if ($reportname=='') $reportname=$myrow['reportname_'.$deflang];
		$reportgroup=$myrow['reportgroup_'.$lang];
		
		$reportsettingtitle="$reportname"; //change this if needed
		
		$dbreportsettingtitle=noapos(htmlspecialchars(htmlspecialchars($reportsettingtitle)));
		
		if ($lastgroup!=$reportgroup){
?>
<div class="sectionheader"><?php echo htmlspecialchars($reportgroup);?></div>
<?php			
			$lastgroup=$reportgroup;
		}
		
?>
<div class="listitem"><a onclick="showreportsetting('<?php echo $reportid;?>','<?php echo $dbreportsettingtitle;?>');"><?php echo htmlspecialchars($reportsettingtitle);?></a></div>
<?php		
	}//while
	
	showobjcacheinfo($res);
	
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

