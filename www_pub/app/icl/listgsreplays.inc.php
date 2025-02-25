<?php

function listgsreplays(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$mode=SGET('mode');
	$key=SGET('key',0); //do not trim
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	header('listviewtitle: Replay Clips');
	header('listviewflag: '._jsflag('showgsreplay'));
	header('listviewjs: gsreplay.js');
		
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookupgsreplay(gid('gsreplaykey'));return false;" style="position:relative;">
		<div class="listsearch_">
			<input id="gsreplaykey" class="img-mg" onfocus="document.hotspot=this;" onkeyup="_inline_lookupgsreplay(this);" autocomplete="off">
			<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('gsreplaykey');_inline_lookupgsreplay(gid('gsreplaykey'));">
		</div>
		<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
		<?php makehelp('gsreplaylistlookup','listviewlookup',1);?>
	</form>

</div>
<div style="margin-bottom:10px;text-align:right;">
	<input type="file" id="gsreplay_importer" accept=".gsreplay" style="display:none;" onchange="gsreplay_import('gsreplay_importer');">
	<label for="gsreplay_importer"><a class="hovlink">import a clip</a></label>
	
	<div style="margin-top:10px;border:solid 1px #dedede;display:none;">
		<div id="gsreplay_importer_pct" style="height:12px;font-size:8px;width:0%;background:#ffff00;"></div>
	</div>
	
</div>
<div id="gsreplaylist">
<?php		
	}

	$params=array($gsid);
		
	$basequery=" from gsreplays where ".COLNAME_GSID."=? ";	

	
	$soundex=is_numeric(SGET('soundex'))?SGET('soundex'):0;
	$sxsearch='';
	if ($soundex&&$key!='') {
		$sxsearch=" or concat(soundex(gsreplaytitle),'') like concat(soundex(?),'%') ";
		
	}
	
	if ($key!='') {
		$basequery.=" and (gsreplayid=? or gsreplaytitle like ? $sxsearch) ";
		array_push($params,$key,"%$key%");
		
		if ($soundex) array_push($params,$key);
	}
	
	$sel='*'; //be specific with the select
	$cquery="select count(*) as c $basequery ";
	$query="select $sel $basequery ";		
	$rs=sql_prep($cquery,$db,$params);
	
	$myrow=sql_fetch_assoc($rs);
	$count=$myrow['c']; //sql_affected_rows($db,$rs);
	
	$perpage=20;
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$perpage*$page;

	$pager='';
	
	if ($maxpage>0){
	ob_start();
?>
<div class="listpager">
<a href=# class="hovlink" onclick="ajxpgn('gsreplaylist',document.appsettings.codepage+'?cmd=slv_core__gsreplays&key='+encodeHTML(gid('gsreplaykey').value)+'&page=<?php echo $page-1;?>&mode=embed');return false;"><img src="imgs/t.gif" class="img-pageleft">Prev</a>
&nbsp;
<a class="pageskipper" onclick="var pagenum=sprompt('Go to page:',<?php echo $page+1;?>);if (pagenum==null||parseInt(pagenum,0)!=pagenum) return false;ajxpgn('gsreplaylist',document.appsettings.codepage+'?cmd=slv_core__gsreplays&key='+encodeHTML(gid('gsreplaykey').value)+'&page='+(pagenum-1)+'&mode=embed');return false;"><?php echo $page+1;?></a>
 of <?php echo $maxpage+1;?>
&nbsp;
<a href=# class="hovlink" onclick="ajxpgn('gsreplaylist',document.appsettings.codepage+'?cmd=slv_core__gsreplays&key='+encodeHTML(gid('gsreplaykey').value)+'&page=<?php echo $page+1;?>&mode=embed');return false;">Next<img src="imgs/t.gif" class="img-pageright"></a>
</div>
<?php		
	$pager=ob_get_clean();
	}
	
	echo $pager;
	
	$query.=" order by gsreplaydate desc, gsreplayid desc limit $start,$perpage";	
	
	$rs=sql_prep($query,$db,$params);
	
	while ($myrow=sql_fetch_assoc($rs)){
		$gsreplayid=$myrow['gsreplayid'];
		$gsreplaytitle=$myrow['gsreplaytitle'];
		$gsreplaydate=$myrow['gsreplaydate'];
		$ddate=date('Y-n-j H:i:s',$gsreplaydate);
		$gsreplaytitle="$gsreplaytitle"; //change this if needed
		
		$dbgsreplaytitle=noapos(htmlspecialchars(htmlspecialchars($gsreplaytitle)));
?>
<div class="listitem"><a onclick="showgsreplay(<?php echo $gsreplayid;?>,'<?php echo $dbgsreplaytitle;?>');">
	<b>#<?php echo $gsreplayid;?></b> <?php echo $ddate;?><br>
	<?php echo htmlspecialchars($gsreplaytitle);?>
	</a>
</div>
<?php		
	}//while
	
	echo $pager;
	
	if ($mode!='embed'){
?>
</div>
</div>
<?php
/*
<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_gsreplays');?></a>';
ajxjs(<?php jsflag('showgsreplay');?>,'gsreplay.js');
</script>
<?php	
*/

	}//embed mode

}

