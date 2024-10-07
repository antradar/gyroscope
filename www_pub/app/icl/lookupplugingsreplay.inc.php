<?php

function lookupplugingsreplay(){
	$key=SGET('key');
	$mode=SGET('mode');
	
	$user=userinfo();
	$gsid=$user['gsid'];

	global $db;
	
	if ($mode!='embed'){
?>
<div class="section">
	<div class="listbar">
		<form class="listsearch" onsubmit="return false;">
		<div class="listsearch_">
			<input autocomplete="off" id="plugingsreplaykey" class="img-mg" onkeyup="ajxjs(<?php jsflag('_inline_lookupplugingsreplay');?>,'gsreplays.js');_inline_lookupplugingsreplay(this);">
			<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('plugingsreplaykey');_inline_lookupplugingsreplay(gid('plugingsreplaykey'));">
		</div>
		<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
		</form>
	
	</div>	

	<div id="plugingsreplaylist">
<?php
}

	$params=array($gsid);
	$query="select * from gsreplays where gsid=? ";
	if ($key!='') {
		$query.=" and gsreplayid like ? ";
		array_push($params,"%$key%");
	}
	
	$rs=sql_prep($query,$db,$params);
	
	$c=sql_affected_rows($db,$rs);
	$perpage=10;
	
	$page=intval($_GET['page']??0);
	$maxpage=ceil($c/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	
	$start=$page*$perpage;
	
	$pager='';
	
	if ($maxpage>0){
		ob_start();
	?>
	<a class="hovlink" onclick="ajxpgn('plugingsreplaylist',document.appsettings.codepage+'?cmd=lookupplugingsreplay&mode=embed&page=<?php echo $page-1;?>&key='+encodeHTML(gid('plugingsreplaykey').value));">&laquo; Prev</a>
	&nbsp; <?php echo $page+1;?> of <?php echo $maxpage+1;?> &nbsp;
	<a class="hovlink" onclick="ajxpgn('plugingsreplaylist',document.appsettings.codepage+'?cmd=lookupplugingsreplay&mode=embed&page=<?php echo $page+1;?>&key='+encodeHTML(gid('plugingsreplaykey').value));">Next &raquo;</a>
	<?php	
		$pager=ob_get_clean();	
	}
	
	
	echo $pager;

	$query.=" order by gsreplayid desc limit $start,$perpage";
	
	$rs=sql_prep($query,$db,$params);
	while ($myrow=sql_fetch_assoc($rs)){
		$gsreplayid=$myrow['gsreplayid'];
		$dgsreplayid=noapos(htmlspecialchars(htmlspecialchars($gsreplayid)));
		$gsreplayid=$myrow['gsreplayid'];
		$gsreplaytitle='';
		$dgsreplaytitle=noapos(htmlspecialchars($gsreplaytitle));
	?>
	<div class="listitem">
		<a onclick="if (document.hotspot&&document.hotspot.onChange) document.hotspot.onChange.dispatch();document.hotspot.selection.setContent('<div class=&quot;plugincontainer plugingsreplay&quot;><p>{{gsreplay id=<?php echo $gsreplayid;?> <?php echo $dgsreplaytitle;?>}}</p></div>');"><?php echo '#'.$gsreplayid.' '.$gsreplaytitle;?></a>
	</div>
	<?php	
	}//while
	
	echo $pager;
	
	if ($mode!='embed'){
?>
	</div>
</div>
<?php		
	}//embed
}
