<?php

function rptactionlog(){
	global $db;
	global $codepage;
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$syslevel=0;
	if (!is_numeric($gsid)) $syslevel=NULL_UUID;

	$now=time();
	
	$day=date('j',$now);
	$mon=date('n',$now);
	$year=date('Y',$now);

	global $paytypes;
	global $paymethods;
	global $lang;
	
	//override date stamp
	$ds=explode('-',SGET('date'));
	if (count($ds)==3){
		$day=$ds[2];
		$mon=$ds[1];
		$year=$ds[0];
		$now=mktime(0,0,0,$mon,$day,$year);	
	}
	
	//// Report Header
	
	$query="select * from ".TABLENAME_REPORTS." where reportkey='actionlog' and (gsid=? or gsid=?) ";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where reportkey='actionlog' and (".COLNAME_GSID."=? or ".COLNAME_GSID."=?) ";
	
	$rs=sql_prep($query,$db,array($gsid,$syslevel));
	$myrow=sql_fetch_assoc($rs);
	$reportgroupnames=$myrow['reportgroupnames'];
	authreport($reportgroupnames);
	
?>
<div class="section">

<div class="sectiontitle" style="margin-bottom:0;"><a ondblclick="toggletabdock();"><?php echo htmlspecialchars($myrow['reportname_'.$lang]);?></a></div>
<div class="infobox"><?php echo htmlspecialchars($myrow['reportdesc_'.$lang]);?></div>
<?php	
	////
	
	$key=SGET('key');
	$opairs=SGET('pairs');
	$pairs=explode(';',$opairs);

	$start=mktime(0,0,0,$mon,$day,$year);
	$end=mktime(23,59,59,$mon,$day,$year);
	
	// $prevday=date('Y-n-j',$start-3600);
	// $nextday=date('Y-n-j',$end+3600);

	$params=array($gsid);
	$query="select * from ".TABLENAME_ACTIONLOG." left join ".TABLENAME_USERS." on ".TABLENAME_ACTIONLOG.".userid=".TABLENAME_USERS.".userid where ".TABLENAME_ACTIONLOG.".".COLNAME_GSID."=? ";

	if ($key!=''||$opairs!='') {
		$query.=" and alogid!=0 and logmessage!='' ";
		if ($key!='') {
			$query.=" and (logmessage like ? or login like ? or rawobj like ? or logname like ?) ";
			array_push($params,"%$key%","%$key%","%$key%","%$key%");
		}
		foreach ($pairs as $pair){
			$parts=explode('=',$pair);
			if (count($parts)<2) continue;
			$k=trim($parts[0]);
			$v=trim($parts[1]);
			if ($k==''||$v=='') continue;
			$query.=" and rawobj like '%\"$k\":\"$v\"%' ";
		
		}

	}  else {
		$query.=" and logmessage!='' and logdate>=? and logdate<=? ";
		array_push($params,$start,$end);
		
		$q="select logdate from ".TABLENAME_ACTIONLOG." where ".COLNAME_GSID."=? and logmessage!='' and logdate<? order by logdate desc limit 1";
		$rs=sql_prep($q,$db,array($gsid,$start));
		if ($myrow=sql_fetch_array($rs)) $prevday=date('Y-n-j',$myrow['logdate']);

		$q="select logdate from ".TABLENAME_ACTIONLOG." where ".COLNAME_GSID."=? and logmessage!='' and logdate>? order by logdate limit 1";
		$rs=sql_prep($q,$db,array($gsid,$end));
		if ($myrow=sql_fetch_array($rs)) $nextday=date('Y-n-j',$myrow['logdate']);
				
	}
	
	$query.=" order by logdate desc,alogid desc ";
	
	$rs=sql_prep($query,$db,$params);
	$count=sql_affected_rows($db,$rs);
	
		
	if ($key!=''||$opairs!=''){
		$perpage=10;
		$page=isset($_GET['page'])?intval($_GET['page']):0;
		$maxpage=ceil($count/$perpage)-1;
		if ($maxpage<0) $maxpage=0;
		if ($page<0) $page=0;
		if ($page>$maxpage) $page=$maxpage;
		$start=$page*$perpage;
		
		$query.=" limit $start,$perpage ";
		$rs=sql_prep($query,$db,$params);
		
	}
	
	
?>

<form onsubmit="reloadtab('rptactionlog',null,'rptactionlog&key='+encodeHTML(gid('actionlog_key').value)+'&pairs='+encodeHTML(gid('actionlog_pairs').value),null,null,{persist:true});return false;">
Search: <input autocomplete="off" class="inp" style="width:30%;" id="actionlog_key" placeholder="Keyword in Action" value="<?php echo htmlspecialchars($key);?>"> 
	<input autocomplete="off" class="inp" style="width:30%;" id="actionlog_pairs" placeholder="Advanced Pattern" value="<?php echo htmlspecialchars($opairs);?>"> <input class="button" type="submit" value="Go">
</form>
<?php
	$pager='';
	
	if ($key!=''||$opairs!=''){
		if ($maxpage>1){
			ob_start();
?>
<div class="listpager">
	<a onclick="reloadtab('rptactionlog',null,'rptactionlog&key=<?php echo urlencode($key);?>&pairs=<?php echo urlencode($opairs);?>&page=<?php echo $page-1;?>',null,null,{persist:true});return false;" class="hovlink" href=#><img src="imgs/t.gif" class="img-pageleft">Prev</a>
	&nbsp; &nbsp;
	<a onclick="var pagenum=sprompt('Go to page:',<?php echo $page+1;?>);if (pagenum==null||parseInt(pagenum,0)!=pagenum) return false;reloadtab('rptactionlog',null,'rptactionlog&key=<?php echo urlencode($key);?>&pairs=<?php echo urlencode($opairs);?>&page='+(pagenum-1),null,null,{persist:true});" class="pageskipper"><?php echo $page+1;?></a> of <?php echo $maxpage+1;?>
	&nbsp; &nbsp;
	<a onclick="reloadtab('rptactionlog',null,'rptactionlog&key=<?php echo urlencode($key);?>&pairs=<?php echo urlencode($opairs);?>&page=<?php echo $page+1;?>',null,null,{persist:true});return false;" class="hovlink" href=#>Next<img src="imgs/t.gif" class="img-pageright"></a>
</div>
<?php			$pager=ob_get_clean();
		}
			
	} else {
		ob_start();
?>
	<div style="padding:20px 0;">
	<?php if (isset($prevday)){?>
	<a onclick="reloadtab('rptactionlog',null,'rptactionlog&date=<?php echo $prevday;?>',null,null,{persist:true});" style="padding:10px;"><img src="imgs/t.gif" class="img-pageleft"></a> 
	<?php }?>
	&nbsp; &nbsp; <a class="hovlink" onclick="document.hotspotref=this;gid('rptactionlogdate').value='<?php echo "$year-$mon-1";?>';pickdate(gid('rptactionlogdate'),{params:'vmode=actionlog'});"><?php echo date('M j, Y',$now);?></a><input type="hidden" id="rptactionlogdate" value="<?php echo date('Y-n-j',$now);?>" onchange="if (valdate(this)) reloadtab('rptactionlog',null,'rptactionlog&date='+this.value,null,null,{persist:true});">
	&nbsp; &nbsp;
	<?php if (isset($nextday)){?>
	<a onclick="reloadtab('rptactionlog',null,'rptactionlog&date=<?php echo $nextday;?>',null,null,{persist:true});" style="padding:10px;"><img src="imgs/t.gif" class="img-pageright"></a>
	<?php }?>
	</div>
<?php
		$pager=ob_get_clean();
		
	//makelookup('rptactionlogdate',1);

}

	echo $pager;
?>
<style>
.alogcol1,.alogcol2,.alogcol3,.alogcol4{float:left;overflow:hidden;}
.alogcol1{width:11%;margin-right:1%;}
.alogcol2{width:14%;margin-right:1%;}
.alogcol3{width:33%;margin-right:1%;}
.alogcol4{width:31%;}

</style>

<?php if ($count==0){
?>
<div class="infobox">
	No activities on this day
</div>
<?php
}
?>

<div class="stable" <?php if ($count==0) echo 'style="display:none;";';?>>

<div class="grid">
	<div class="gridheader"><div class="gridrow">
		<div class="alogcol1">Time</div>
		<div class="alogcol2">User</div>
		<div class="alogcol3">Action</div>
		<div class="alogcol4">Extra</div>
		<div class="clear"></div>
	</div></div>

<?php
	$idx=0;
	
	$hasbulldozed=0;
	
	while ($myrow=sql_fetch_array($rs)){
		$alogid=$myrow['alogid'];
		$username=htmlspecialchars($myrow['login']);
		if ($username=='') $username='<span style="color:#ee6666;">'.htmlspecialchars($myrow['logname']).'</span>';
		$logdate=$myrow['logdate'];
		$dlogdate=date('H:i:s',$logdate);
		if ($key!=''||$opairs!='') $dlogdate=date('Y-n-j H:i:s',$logdate);
		$logmessage=$myrow['logmessage'];
		$extra='';
		$obj=json_decode($myrow['rawobj'],1);
		foreach ($obj as $k=>$v) $extra.="; $k=".htmlspecialchars($v);
		$extra=str_replace(' -&gt; ',' <b class="lcarr">&rArr;</b> ',$extra);
		$extra=trim($extra,'; ');
		$bulldozed=$myrow['bulldozed'];
		if ($bulldozed) $hasbulldozed=1;
?>
	<div class="gridrow<?php if ($idx%2==1) echo ' even';?><?php if ($bulldozed) echo ' warn';?>">
		<div class="alogcol1"><?php echo $dlogdate;?>&nbsp;</div>
		<div class="alogcol2"><?php echo $username;?>&nbsp;</div>
		<div class="alogcol3"><?php if ($bulldozed){?><span style="color:#cc0000;">*</span> <?php }?><?php echo htmlspecialchars($logmessage);?>&nbsp;</div>
		<div class="alogcol4"><?php echo $extra;?>&nbsp;</div>
		<div class="clear"></div>
	</div>
<?php
		$idx++;
	}//while
?>
</div><!-- grid -->
</div><!-- stable -->

<?php
	if ($count>=5||$key!=''||$opairs!='') echo $pager;
	
	if ($hasbulldozed){
?>
	<span style="display:inline-block;vertical-align:middle;width:20px;height:20px;text-align:center;border:solid 1px #dedede;border-radius:4px;" class="legend warn">
		<span style="color:#cc0000;">*</span>
	</span>
	<span style="vertical-align:middle;">The user knowingly overwrote the record despite the edit conflict warning.</span>
<?php		
	}
?>

</div><!-- section -->


<?php
	
}