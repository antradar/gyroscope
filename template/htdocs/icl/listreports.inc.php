<?php

function listreports(){
	$user=userinfo();
	$gsid=$user['gsid'];
	$groupnames=$user['groups'];
	$key=SGET('key',0);
	$mode=SGET('mode');
	
	global $db;
	global $lang;
	global $deflang;

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;
	
	$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) ";
	
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=?)";
	
	$params=array($gsid,$syslevel);
	
	if ($key!='') {
		$query.=" and (lower(reportgroup_$lang) like lower(?) or lower(reportname_$lang) like lower(?)) ";
		array_push($params,'%'.$key.'%','%'.$key.'%');
	}
	
	$query.=" order by reportgroup_$lang,reportname_$lang";
	
	$rs=sql_prep($query,$db,$params);
	$found=0;
	
	header('listviewtitle:'.tabtitle(_tr('icon_reports')));
	header('listviewflag:'._jsflag('showreport'));
	header('listviewjs:reports.js');
		
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
	<?php makehelp('reportlistlookup','listviewlookup',1);?>
	</form>
</div>
<div id="reportlist">
<?php
}
	$lastgroup='';
	
	while ($myrow=sql_fetch_assoc($rs)){
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
<div class="sectionheader"><?php echo $reportgroup;?></div>
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
