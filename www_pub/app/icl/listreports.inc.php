<?php
include 'models/reports.list.php';

function listreports($ctx=null){
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
	$groupnames=$user['groups'];
	$key=SGET('key',0,$ctx);
	$mode=SGET('mode',1,$ctx);
	
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	global $lang;
	global $deflang;

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	$soundex=intval(SGET('soundex'));
	
	$res=reports_list($ctx,$gsid,$key,$lang,$page,$syslevel,$soundex);
	$maxpage=$res['maxpage'];
	$page=$res['page'];
	$recs=$res['recs'];
	
	$found=0;
	
	gs_header($ctx, 'listviewtitle',tabtitle(_tr('icon_reports')));
	gs_header($ctx, 'listviewflag', _jsflag('showreport'));
	gs_header($ctx, 'listviewjs', 'reports.js');
		
	if ($mode!='embed'){
?>
<div class="section">

<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookupreport(gid('reportkey'));return false;" style="position:relative;">
	<div class="listsearch_">
		<input onfocus="document.hotspot=this;" id="reportkey" class="img-mg" onkeyup="_inline_lookupreport(this);" autocomplete="off">
		<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('reportkey');_inline_lookupreport(gid('reportkey'));">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	<?php makehelp($ctx,'reportlistlookup','listviewlookup',1);?>
	</form>
</div>
<?php

?>
<div id="reportlist">
<?php
}

	if ($maxpage>0){
?>
<div class="listpager">
<?php echo $page+1;?> of <?php echo $maxpage+1;?>
&nbsp;
<a href=# onclick="ajxpgn('reportlist',document.appsettings.codepage+'?cmd=slv_core__reports&key='+encodeHTML(gid('reportkey').value)+'&page=<?php echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('reportlist',document.appsettings.codepage+'?cmd=slv_core__reports&key='+encodeHTML(gid('reportkey').value)+'&page=<?php echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?php		
	}
	
	$lastgroup='';
	
	foreach ($recs as $myrow){
		$reportkey=$myrow['reportkey'];
		$reportname=$myrow['reportname_'.$lang];
		if ($reportname=='') $reportname=$myrow['reportname_'.$deflang];
		$reportgroup=$myrow['reportgroup_'.$lang];
		$reportfunc=$myrow['reportfunc'];
		$dbreportname=noapos(htmlspecialchars(htmlspecialchars($reportname)));
		$reportgroupnames=explode('|',$myrow['reportgroupnames']);
		$test=array_intersect($reportgroupnames,array_keys($groupnames));
		if (count($test)<1) continue; //&&count($reportgroupnames)==0
		$found=1;
		
		$bingo=intval($myrow['bingo']);
		$params=$myrow['reportparams'];
		if ($params!='') $params='&'.trim(trim($params),'&');
				
		if ($lastgroup!=$reportgroup){
?>
<div class="clear"></div>
<div class="sectionheader"><?php echo htmlspecialchars($reportgroup);?></div>
<?php			
			$lastgroup=$reportgroup;
		}
?>
	<div class="listitem">
		<a onclick="<?php echo $reportfunc;?>reloadtab('rpt<?php echo $reportkey;?>','<img src=&quot;imgs/t.gif&quot; class=&quot;ico-report&quot;><?php echo $dbreportname;?>','rpt<?php echo $reportkey.$params;?>',(self.rptreload_<?php echo $reportkey;?>?rptreload_<?php echo $reportkey;?>:null),null,{bingo:<?php echo $bingo;?>});addtab('rpt<?php echo $reportkey;?>','<img src=&quot;imgs/t.gif&quot; class=&quot;ico-report&quot;><?php echo $dbreportname;?>','rpt<?php echo $reportkey.$params;?>',(self.rptinit_<?php echo $reportkey;?>?rptinit_<?php echo $reportkey;?>:null),null,{bingo:<?php echo $bingo;?>});"><?php echo htmlspecialchars($reportname);?></a>
	</div>
<?php		
	}//while
	
	if (!$found&&$key==''){
?>
	<em style="color:#666666;">You cannot see any reports.</em>
<?php		
	}
	
	showobjcacheinfo($res);
	
	if ($mode!='embed'){
?>
</div>
</div>
<?php
/*
<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_reports');?></a>';
ajxjs(self.showreport,'reports.js');
</script>
<?php
*/
	}

}
