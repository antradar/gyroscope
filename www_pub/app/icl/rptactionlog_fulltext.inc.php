<?php

function rptactionlog_fulltext(){
	global $db;
	global $codepage;
	global $manticore;
	
	global $dict_mons;
	if (!defined('KB_PREFIX')) define('KB_PREFIX','');
	
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

	$key=SGET('key');
	$opairs=SGET('pairs');
	$pairs=explode(';',$opairs);
		
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
	
	$facets='';
	$dims=array();
	
	$filter='';
	
	
	if ($key!=''||$opairs!=''){
		$dims=array(
			'ctxid'=>array('label'=>'Context','field'=>'ctxid','sort'=>'ctxid asc','limit'=>20+1),
			'rectype'=>array('label'=>'Type','field'=>'rectype','sort'=>'rectype asc','limit'=>20+1),
			'logdate_year'=>array('label'=>'Year','field'=>'year(logdate)','sort'=>'logdate asc'),
			'logdate_month'=>array('label'=>'Month','field'=>'month(logdate)','sort'=>'logdate asc', 'parent'=>'logdate_year'),
			'userid'=>array('label'=>'User','field'=>'userid','sort'=>'count(*) desc','limit'=>500+1),
		);
		
		$navfilters=array();
				
		//remove pinned dims from dims
		if (isset($_GET['rectype'])){
			$navfilters['rectype']=$_GET['rectype'];
			if (isset($dims['rectype'])) $dims['rectype']['selected']=1;
			$filter.=" and rectype='".addslashes($_GET['rectype'])."'";
		}
		
		if (isset($_GET['logdate_year'])){
			$navfilters['logdate_year']=$_GET['logdate_year'];
			if (isset($dims['logdate_year'])) $dims['logdate_year']['selected']=1;
			$filter.=" and yr=".intval($_GET['logdate_year']).' ';
			
			if (isset($_GET['logdate_month'])){
				$navfilters['logdate_month']=$_GET['logdate_month'];
				if (isset($dims['logdate_month'])) $dims['logdate_month']['selected']=1;
				$filter.=" and mon=".intval($_GET['logdate_month']).' ';
			}			
		}//year
		
		if (isset($_GET['userid'])){
			$navfilters['userid']=$_GET['userid'];
			if (isset($dims['userid'])) $dims['userid']['selected']=1;
			$filter.=" and userid=".intval($_GET['userid'])." ";
		}
		
		if (isset($_GET['ctxid'])){
			$navfilters['ctxid']=$_GET['ctxid'];
			if (isset($dims['ctxid'])) $dims['ctxid']['selected']=1;
			$filter.=" and ctxid=".intval($_GET['ctxid'])." ";
		}		
		
		///		
						
		
		foreach ($dims as $dk=>$dim){
			if (isset($dim['parent'])&&isset($dims[$dim['parent']])&&!isset($dims[$dim['parent']]['selected'])) continue;
			if (isset($dim['selected'])) continue;
			$facets.=" FACET ".$dim['field'].' as '.$dk.' ';
			if (isset($dim['sort'])) $facets.=' order by '.$dim['sort'].' ';
			if (isset($dim['limit'])) $facets.=' limit '.$dim['limit'].' ';
		}
	}//facets are only for filtered results

	//echo "$facets<hr>";	

	$ta=microtime(1);	
?>
<div class="section">

<div class="sectiontitle" style="margin-bottom:0;"><a ondblclick="toggletabdock();"><?php echo htmlspecialchars($myrow['reportname_'.$lang]);?> (Fulltext)</a></div>
<div class="infobox"><?php echo htmlspecialchars($myrow['reportdesc_'.$lang]);?></div>
<?php	
	////
	

	$start=mktime(0,0,0,$mon,$day,$year);
	$end=mktime(23,59,59,$mon,$day,$year);
	
	// $prevday=date('Y-n-j',$start-3600);
	// $nextday=date('Y-n-j',$end+3600);

	$params=array($gsid);
	$query="select * from ".TABLENAME_ACTIONLOG." left join ".TABLENAME_USERS." on ".TABLENAME_ACTIONLOG.".userid=".TABLENAME_USERS.".userid where ".TABLENAME_ACTIONLOG.".".COLNAME_GSID."=? ";
	
	$query="select *,year(logdate) as yr,month(logdate) as mon from ".KB_PREFIX."actionlog_rt where gsid=$gsid ";
	$cquery="select count(*) as c,year(logdate) as yr,month(logdate) as mon from ".KB_PREFIX."actionlog_rt where gsid=$gsid ";

	
	$terms=array();
	
	if ($key!=''||$opairs!='') {
		$haspositive=0;
		if ($opairs!='') $haspositive=1;
		
		if ($key!='') {
			$tokens=explode(' ',$key);
			foreach ($tokens as $token) {
				$token=str_replace('"','',$token);
				$neg='';
				$usetoken=$token;
				if ($token[0]=='-'){
					if ($haspositive){
						$usetoken=ltrim($token,'-');
						$neg='-';
					} else {
					?>
					<div class="warnbox">exclusion search term ignored without any inclusive terms.</div>
					<?php
						continue;	
					}
				} else {
					$haspositive=1;	
				}
				
				array_push($terms, $neg.'"'.addslashes($token).'"' );
			}
			
		}
		
		foreach ($pairs as $pair){
			$parts=explode('=',$pair);
			if (count($parts)<2) continue;
			$k=trim($parts[0]);
			$v=trim($parts[1]);
			if ($k=='') continue;
			
			$k=str_replace("'",'',$k);
			
			$pterm='@rawobj "'.$k.'":';
			if ($v!='') $pterm.='"'.addslashes($v).'"';
			//echo "$pterm<br>";

			array_push($terms, $pterm);
		
		}

		//echo '<pre>'; print_r($terms); echo '</pre>';
		
		if (count($terms)>0){
			$filter.=" and match ('";
			$filter.=implode(' ', $terms);
			$filter.=" ') ";	
		}
				
		$filter.=" and gsid=$gsid ";
		

	}  else {
				
		$filter.=" and gsid=$gsid and logdate>=$start and logdate<=$end ";

				
		$q="select logdate from ".TABLENAME_ACTIONLOG." where ".COLNAME_GSID."=? and logmessage!='' and logdate<? order by logdate desc limit 1";
		$rs=sql_prep($q,$db,array($gsid,$start));
		if ($myrow=sql_fetch_array($rs)) $prevday=date('Y-n-j',$myrow['logdate']);

		$q="select logdate from ".TABLENAME_ACTIONLOG." where ".COLNAME_GSID."=? and logmessage!='' and logdate>? order by logdate limit 1";
		$rs=sql_prep($q,$db,array($gsid,$end));
		if ($myrow=sql_fetch_array($rs)) $nextday=date('Y-n-j',$myrow['logdate']);
				
	}
	
	
	$query.=$filter;
	$cquery.=$filter;	
	
	$count=-1;
	
	if ($key!=''||$opairs!=''){
		$rs=sql_query($cquery,$manticore);
		$myrow=sql_fetch_assoc($rs);
		$count=$myrow['c'];
		
	} else {	
		//daily page, no need to count at all
	}

	$query.=" order by logdate desc, alogid desc ";

	$rs=sql_query($query,$manticore);
	
	if ($key!=''||$opairs!=''){
		$perpage=10;
		$page=isset($_GET['page'])?intval($_GET['page']):0;
		$maxpage=ceil($count/$perpage)-1;
		if ($maxpage<0) $maxpage=0;
		if ($page<0) $page=0;
		if ($page>$maxpage) $page=$maxpage;
		$start=$page*$perpage;
		
		$query.=" limit $start,$perpage ".$facets;
		//$rs=sql_query($query,$manticore);
		$rss=array();
		if ($manticore->multi_query($query)){
			do {
				if ($rs = $manticore->store_result()){
					array_push($rss,$rs);
				}
			} while ($manticore->next_result());
		} else {
			echo 'Error executing query: '.$db->error."<hr>";	
		}

		$rsidx=0;
		foreach ($dims as $dkey=>$dim){
			if (isset($dim['parent'])&&isset($dims[$dim['parent']])&&!isset($dims[$dim['parent']]['selected'])) continue;
			if (isset($dim['selected'])) continue;
			
			$rsidx++;
			if (!isset($rss[$rsidx])) break;
			$rs=$rss[$rsidx];
			if (!isset($dims[$dkey]['counts'])) $dims[$dkey]['counts']=array();
			while ($myrow=sql_fetch_assoc($rs)){
				//echo '<pre>'; print_r($myrow); echo '</pre>';
				if ($myrow[$dkey]!='') $dims[$dkey]['counts'][$myrow[$dkey]]=$myrow['count(*)'];
			}//while	
		}	

		
		$rs=$rss[0];
		
		//echo '<pre>'; print_r($dims); echo '</pre>';
		
	}
	

	$dctxmap=array();
	
	$recs=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		$alogid=$myrow['alogid'];
		$recs[$alogid]=$myrow;
	}
			
	$chunksize=100;
	$groups=array_chunk($recs,$chunksize,true);

	foreach ($groups as $group){
		$recids=implode(',',array_keys($group));
		$uquery="select alogid,login from ".TABLENAME_ACTIONLOG." left join ".TABLENAME_USERS." on ".TABLENAME_ACTIONLOG.".userid=".TABLENAME_USERS.".userid where alogid in ($recids)";
		$rs=sql_prep($uquery,$db);
			
		while ($myrow=sql_fetch_assoc($rs)){
			$alogid=$myrow['alogid'];
			$login=$myrow['login'];
			$recs[$alogid]['login']=$login;
		}
		
		$dctxids=array();
		
		foreach ($group as $item){
			$dctxids[$item['ctxid']]=$item['ctxid'];	
		}

		if (count($dctxids)>0){
			$query="select ctxid, ctxname from ".TABLENAME_ACTIONCTXS." where ctxid in (".implode(',',$dctxids).")";
			$rs=sql_prep($query,$db);
			while ($myrow=sql_fetch_assoc($rs)) $dctxmap[$myrow['ctxid']]=$myrow['ctxname'];	
		}		

				
	}	
	
	
	$usermap=array();
	$userids=array();

	if (isset($dims)&&isset($dims['userid'])&&isset($dims['userid']['counts'])){		
		foreach ($dims['userid']['counts'] as $uid=>$_){
			array_push($userids,$uid);	
		}	
	}
	if (isset($navfilters['userid'])) array_push($userids,$navfilters['userid']);
	if (count($userids)>0){
		$query="select userid,login from ".TABLENAME_USERS." where userid in (".implode(',',$userids).") and ".COLNAME_GSID.'=?';
		$rs=sql_prep($query,$db,$gsid);
		while ($myrow=sql_fetch_assoc($rs)) $usermap[$myrow['userid']]=$myrow['login'];
	}
	
	$ctxids=array();
	$ctxmap=array();
	if (isset($dims)&&isset($dims['ctxid'])&&isset($dims['ctxid']['counts'])){		
		foreach ($dims['ctxid']['counts'] as $ctxid=>$_){
			array_push($ctxids,$ctxid);	
		}	
	}
	if (isset($navfilters['ctxid'])) array_push($ctxids,$navfilters['ctxid']);
	if (count($ctxids)>0){
		$query="select ctxid,ctxname from ".TABLENAME_ACTIONCTXS." where ctxid in (".implode(',',$ctxids).")";
		$rs=sql_prep($query,$db);
		while ($myrow=sql_fetch_assoc($rs)) $ctxmap[$myrow['ctxid']]=$myrow['ctxname'];
	}	
	
?>

<form onsubmit="reloadtab('rptactionlog',null,'rptactionlog&key='+encodeHTML(gid('actionlog_key').value)+'&pairs='+encodeHTML(gid('actionlog_pairs').value),null,null,{persist:true});return false;">
Search: <input autocomplete="off" class="inp" style="width:30%;" id="actionlog_key" placeholder="Keyword in Action" value="<?php echo htmlspecialchars($key);?>"> 
	<input autocomplete="off" class="inp" style="width:30%;" id="actionlog_pairs" placeholder="Advanced: key= ; key=val; key=val; ..." value="<?php echo htmlspecialchars($opairs);?>"> <input class="button" type="submit" value="Go">
</form>
<?php
	
	foreach ($dims as $dkey=>$dim){
		if (!isset($dim['counts'])&&!isset($dim['selected'])) continue;
		if (isset($dim['counts'])&&count($dim['counts'])==0&&!isset($dim['selected'])){
			//display removal link
			continue;	
		}
		
	?>
	<div class="listitem smallertext" style="margin-left:45px;">
		<b><?php echo $dim['label'];?>:</b> 
		<?php
			$subfilters=rptactionlog_strfilters($navfilters,$dkey);
			if (!isset($dim['counts'])) {
				$dim['counts']=array();
				$dv=$_GET[$dkey];
				if ($dkey=='userid'&&isset($usermap[$dv])) $dv=$usermap[$dv];
				if ($dkey=='logdate_month') $dv=$dict_mons[$dv];
				if ($dkey=='ctxid') $dv=$ctxmap[$dv]??'n/a';
			?>
			<nobr><?php echo htmlspecialchars($dv);?>&nbsp;<a onclick="reloadtab('rptactionlog',null,'rptactionlog&key='+encodeHTML(gid('actionlog_key').value)+'<?php echo $subfilters;?>&pairs='+encodeHTML(gid('actionlog_pairs').value),null,null,{persist:true});return false;"><img src="imgs/t.gif" class="img-del"></a></nobr>
			<?php	
			}
		?>
		<?php foreach ($dim['counts'] as $label=>$c){			
			$dimfilters=$subfilters.'&'.$dkey.'='.urlencode($label);
			$dv=$label;
			if ($dkey=='userid'&&isset($usermap[$dv])) $dv=$usermap[$dv];
			if ($dkey=='ctxid') {
				if ($dv==0) continue; //uncommit to skip n/a type
				$dv=$ctxmap[$dv]??'n/a';
			}
			
			if ($dkey=='logdate_month') $dv=$dict_mons[$dv];
		?>
			<nobr><a class="hovlink" onclick="reloadtab('rptactionlog',null,'rptactionlog&key='+encodeHTML(gid('actionlog_key').value)+'<?php echo $dimfilters;?>&pairs='+encodeHTML(gid('actionlog_pairs').value),null,null,{persist:true});return false;"><?php echo htmlspecialchars($dv);?></a> <em class="diminished">(<?php echo $c;?>)</em></nobr> &nbsp; &nbsp;
		<?php }?>
		<?php if (isset($dim['limit'])&&count($dim['counts'])>=$dim['limit']-1) echo '<b>...</b>';?>
	</div>
	<?php	
	}//foreach dim
	
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
.alogcol1,.alogcol2,.alogcol3,.alogcol4,.alogcol5{float:left;overflow:hidden;}
.alogcol1{width:8%;margin-right:1%;}
.alogcol2{width:11%;margin-right:1%;}
.alogcol3{width:28%;margin-right:1%;}
.alogcol4{width:16%;margin-right:1%;font-style:italic;overflow-wrap:break-word;}
.alogcol5{width:32%;overflow-wrap:break-word;}

</style>

<?php if (count($recs)==0){
?>
<div class="infobox">
	<?php if ($key!=''||$opairs!='') echo 'No records found'; else echo 'No activities on this day';?>
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
		<div class="alogcol4">Context</div>
		<div class="alogcol5">Extra</div>
		<div class="clear"></div>
	</div></div>

<?php
	$idx=0;
	
	$hasbulldozed=0;
	
	foreach ($recs as $myrow){
		$alogid=$myrow['alogid'];
		$username=htmlspecialchars($myrow['login']??'');
		if ($username=='') $username='<span style="color:#ee6666;">'.htmlspecialchars($myrow['logname']??'').'</span>';
		
		$ctxname=$dctxmap[$myrow['ctxid']]??'';
		
		$logdate=$myrow['logdate'];
		$dlogdate='-';
		if (is_numeric($logdate)&&$logdate!=0) {
			$dlogdate=date('H:i:s',$logdate);
			if ($key!=''||$opairs!='') $dlogdate=date('Y-n-j H:i:s',$logdate);
		}
		
		$logmessage=$myrow['logmessage'];
		$extra='';
		$obj=json_decode($myrow['rawobj'],1);
		if (isset($obj)&&is_array($obj)) foreach ($obj as $k=>$v) $extra.="; $k=".htmlspecialchars($v);
		$extra=str_replace(' -&gt; ',' <b class="lcarr">&rArr;</b> ',$extra);
		$extra=trim($extra,'; ');
		$bulldozed=$myrow['bulldozed'];
		if ($bulldozed) $hasbulldozed=1;
?>
	<div class="gridrow<?php if ($idx%2==1) echo ' even';?><?php if ($bulldozed) echo ' warn';?>">
		<div class="alogcol1"><?php echo $dlogdate;?>&nbsp;</div>
		<div class="alogcol2"><?php echo $username;?>&nbsp;</div>
		<div class="alogcol3"><?php if ($bulldozed){?><span style="color:#cc0000;">*</span> <?php }?><?php echo htmlspecialchars($logmessage);?>&nbsp;</div>
		<div class="alogcol4"><?php echo $ctxname;?>&nbsp;</div>
		<div class="alogcol5"><?php echo $extra;?>&nbsp;</div>
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
	
	$tb=microtime(1);
	
?>
<div style="padding:20px 0;opacity:0.6;" class="smallertext">
	search time: <?php echo round(($tb-$ta)*1000);?> ms
</div>	


</div><!-- section -->


<?php
	
}

function rptactionlog_strfilters($navfilters,$minus=null){
	if (isset($minus)&&isset($navfilters[$minus])) unset($navfilters[$minus]);
	
	$filters='';
	foreach ($navfilters as $k=>$v) $filters.='&'.$k.'='.urlencode($v);
	return $filters;
}