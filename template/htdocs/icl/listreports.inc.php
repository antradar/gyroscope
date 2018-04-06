<?php

function listreports(){
	$user=userinfo();
	$gsid=$user['gsid']+0;
	$groupnames=$user['groups'];
	$key=GETSTR('key');
	$mode=GETSTR('mode');
	
	global $db;
	global $lang;
	global $deflang;
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=$gsid or gsid=0) ";
	if ($key!='') $query.=" and reportgroup_$lang like '%$key%' or reportname_$lang like '%$key%' ";
	$query.=" order by reportgroup_$lang,reportname_$lang";
	
	$rs=sql_query($query,$db);
	$found=0;
	
	if ($mode!='embed'){
?>
<div class="section">

<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookupreport(gid('reportkey'));return false;">
	<div class="listsearch_">
		<input id="reportkey" class="img-mg" onkeyup="_inline_lookupreport(this);" autocomplete="off">
		<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('reportkey');_inline_lookupreport(gid('reportkey'));">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</form>
</div>
<div id="reportlist">
<?
}
	$lastgroup='';
	
	while ($myrow=sql_fetch_assoc($rs)){
		$reportkey=$myrow['reportkey'];
		$reportname=$myrow['reportname_'.$lang];
		if ($reportname=='') $reportname=$myrow['reportname_'.$deflang];
		$reportgroup=$myrow['reportgroup_'.$lang];
		$reportfunc=$myrow['reportfunc'];
		$dbreportname=noapos(htmlspecialchars($reportname));
		$reportgroupnames=explode('|',$myrow['reportgroupnames']);
		$test=array_intersect($reportgroupnames,array_keys($groupnames));
		if (count($test)<1) continue; //&&count($reportgroupnames)==0
		$found=1;
		
		if ($lastgroup!=$reportgroup){
?>
<div class="sectionheader"><?echo $reportgroup;?></div>
<?			
			$lastgroup=$reportgroup;
		}
?>
	<div class="listitem">
		<a onclick="<?echo $reportfunc;?>reloadtab('rpt<?echo $reportkey;?>','<?echo $dbreportname;?>','rpt<?echo $reportkey;?>',(self.rptreload_<?echo $reportkey;?>?rptreload_<?echo $reportkey;?>:null));addtab('rpt<?echo $reportkey;?>','<?echo $dbreportname;?>','rpt<?echo $reportkey;?>',(self.rptinit_<?echo $reportkey;?>?rptinit_<?echo $reportkey;?>:null));"><?echo $reportname;?></a>
	</div>
<?		
	}//while
	
	if (!$found&&$key==''){
?>
	<em style="color:#666666;">You cannot see any reports.</em>
<?		
	}
	
	if ($mode!='embed'){
?>
</div>
</div>
<script>
gid('tooltitle').innerHTML='<a><?tr('icon_reports');?></a>';
ajxjs(self.showreport,'reports.js');
</script>
<?
	}

}
