<?php

function listhelptopics(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	
	//unset($user['groups']['helpedit']); //debug
	
	$mode=SGET('mode');
	$key=SGET('key',0);
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	header('listviewtitle:'.tabtitle(_tr('icon_helptopics')));
	header('listviewflag:'._jsflag('showhelptopic'));
	header('listviewjs:helptopics.js');
		
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
	<form class="listsearch" onsubmit="_inline_lookuphelptopic(gid('helptopickey'));return false;" style="position:relative;">
		<div class="listsearch_">
			<input id="helptopickey" class="img-mg" onfocus="document.hotspot=this;" onkeyup="_inline_lookuphelptopic(this);" autocomplete="off">
			<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('helptopickey');_inline_lookuphelptopic(gid('helptopickey'));">
		</div>
		<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
		<?php makehelp('helptopiclistlookup','listviewlookup',1);?>
	</form>
	
	<?php if ($user['groups']['helpedit']){?>

	<div style="padding-top:10px;">
	<a class="recadder" onclick="addtab('helptopic_new','<img src=&quot;imgs/t.gif&quot; class=&quot;ico-setting&quot;><?php tr('list_helptopic_add_tab');?>','newhelptopic');"> <img src="imgs/t.gif" class="img-addrec"><?php tr('list_helptopic_add');?></a>
	</div>
	
	<?php }?>
</div>

<div id="helptopiclist" style="position:relative;">
<?php		
	}

	$params=array();
	$query="select * from ".TABLENAME_HELPTOPICS." where 1 ";
	
	$soundex=intval(SGET('soundex'));
	$sxsearch='';
	if ($soundex&&$key!='') {
		$sxsearch=" or concat(soundex(helptopictitle),'') like concat(soundex(?),'%') ";
		
	}
	
	if ($key!='') {
		$query.=" and (lower(helptopictitle) like lower(?) or helptopictext like ? or helptopickeywords like ?  $sxsearch) ";
		array_push($params,"%$key%","%$key%","%$key%");
		
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

	$pager='';
	
	if ($maxpage>0){
	ob_start();
?>
<div class="listpager">
<a href=# class="hovlink" onclick="ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=slv_core__helptopics&key='+encodeHTML(gid('helptopickey').value)+'&page=<?php echo $page-1;?>&mode=embed');return false;"><img src="imgs/t.gif" class="img-pageleft">Prev</a>
&nbsp;
<a class="pageskipper" onclick="var pagenum=sprompt('Go to page:',<?php echo $page+1;?>);if (pagenum==null||parseInt(pagenum,0)!=pagenum) return false;ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=slv_core__helptopics&key='+encodeHTML(gid('helptopickey').value)+'&page='+(pagenum-1)+'&mode=embed');return false;"><?php echo $page+1;?></a>
 of <?php echo $maxpage+1;?>
&nbsp;
<a href=# class="hovlink" onclick="ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=slv_core__helptopics&key='+encodeHTML(gid('helptopickey').value)+'&page=<?php echo $page+1;?>&mode=embed');return false;">Next<img src="imgs/t.gif" class="img-pageright"></a>
</div>
<?php		
	$pager=ob_get_clean();
	}
	
	echo $pager;
	
	$query.=" order by helptopicsort limit $start,$perpage";	
	
	$rs=sql_prep($query,$db,$params);
	
	$pagelead=0;
	$pageleadidx=0;
	
	while ($myrow=sql_fetch_array($rs)){
		$helptopicid=$myrow['helptopicid'];
		$helptopictitle=$myrow['helptopictitle'];
		
		$helptopiclevel=$myrow['helptopiclevel'];
		$ind=$helptopiclevel*25;
		
		$helptopictitle="$helptopictitle"; //change this if needed
		
		$dbhelptopictitle=noapos(htmlspecialchars(htmlspecialchars($helptopictitle)));
		
		if ($user['groups']['helpedit']){?>
<div class="sortlistitem helptopicitem" onselectstart="return false;" 
onmousedown="helptopic_mousedown(this,'<?php echo $helptopicid;?>',<?php echo $page;?>,'<?php emitgskey('swaphelptopicpos');?>');" 
ontouchstart="helptopic_touchstart(this,'<?php echo $helptopicid;?>',<?php echo $page;?>,'<?php emitgskey('swaphelptopicpos');?>');" 
onmouseover="helptopic_mouseover(this,'<?php echo $helptopicid;?>',1);" style="border-bottom:solid 1px #D4EDC9;<?php if ($pageleadidx<$pagelead&&$page>0) echo 'opacity:0.6;';?>position:relative;cursor:move;user-select:none;-webkit-user-select:none;-moz-user-select:none;padding-left:5px;">
	<a onselectstart="return false;" onmouseover="helptopic_mouseover(this,'<?php echo $helptopicid;?>');" style="margin-left:<?php echo $ind;?>px;<?php if ($helptopiclevel==0) echo 'font-weight:bold;'; else echo 'font-style:italic;';?>" onclick="showhelptopic('<?php echo $helptopicid;?>','<?php echo $dbhelptopictitle;?>');"><?php echo htmlspecialchars($helptopictitle);?></a>
	<?php } else {?>
		<div class="listitem">
		<a style="margin-left:<?php echo $ind;?>px;<?php if ($helptopiclevel==0) echo 'font-weight:bold;'; else echo 'font-style:italic;';?>" onclick="showhelptopic('<?php echo $helptopicid;?>','<?php echo $dbhelptopictitle;?>');"><?php echo htmlspecialchars($helptopictitle);?></a>
	<?php }?>
	
	<?php if ($user['groups']['helpedit']){?>
	<div style="text-align:right;padding-bottom:5px;" class="privhide">
		<?php if ($helptopiclevel>0){?>
		<acronym title="decrease indent"><span class="labelbutton" style="cursor:pointer;" onclick="dechelptopiclevel('<?php echo $helptopicid;?>');">&#8602;</span></acronym>
		&nbsp;
		<?php } 
		
		if ($helptopiclevel<4){
		?>
		<acronym title="increase indent"><span class="labelbutton" style="cursor:pointer;" onclick="inchelptopiclevel('<?php echo $helptopicid;?>');">&#8603;</span></acronym>
		<?php
		}
		?>
		&nbsp; &nbsp;
		<span onclick="edithelptopic('<?php echo $helptopicid;?>','<?php echo $dbhelptopictitle;?>');" style="cursor:pointer;" class="labelbutton">edit</span>
	</div>
	<?php } ?>
</div>
<?php		
	}//while
	
?>
<div class="sortlistitem" style="border:none;border-left:none;" onmouseover="helptopic_mouseover(this,-1,1);"><a>&nbsp;</a></div>
<div id="helptopicshadow" style="white-space:nowrap;opacity:0.95;cursor:move;user-select:none;-webkit-user-select:none;-moz-user-select:none;display:none;position:absolute;top:20px;left:60px;background:#efefef;padding:5px;"></div>
<?php
	
	echo $pager;
	
	if ($mode!='embed'){
?>
</div>
</div>
<?php
/*
<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_helptopics');?></a>';
ajxjs(<?php jsflag('showhelptopic');?>,'helptopics.js');
</script>
<?php	
*/

	}//embed mode

}

