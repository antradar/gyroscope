<?php

function rptfaultlog(){
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
	
	$query="select * from ".TABLENAME_REPORTS." where reportkey='faultlog' and (gsid=? or gsid=?) ";
	if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where reportkey='faultlog' and (".COLNAME_GSID."=? or ".COLNAME_GSID."=?) ";
	
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
	

	$params=array($gsid);
	$query="select * from ".TABLENAME_FAULTS." left join ".TABLENAME_USERS." on ".TABLENAME_FAULTS.".userid=".TABLENAME_USERS.".userid where ".TABLENAME_FAULTS.".".COLNAME_GSID."=? ";
	
	$query.=" order by faultdate desc,faultid desc ";
	
	$rs=sql_prep($query,$db,$params);
	$count=sql_affected_rows($db,$rs);
	
		
	$perpage=25;
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$page*$perpage;
	
	$query.=" limit $start,$perpage ";
	$rs=sql_prep($query,$db,$params);
			
	$pager='';
	

	if ($maxpage>1){
			ob_start();
?>
<div class="listpager">
	<a onclick="reloadtab('rptfaultlog',null,'rptfaultlog&page=<?php echo $page-1;?>',null,null,{persist:true});return false;" class="hovlink" href=#><img src="imgs/t.gif" class="img-pageleft">Prev</a>
	&nbsp; &nbsp;
	<a onclick="var pagenum=sprompt('Go to page:',<?php echo $page+1;?>);if (pagenum==null||parseInt(pagenum,0)!=pagenum) return false;reloadtab('rptfaultlog',null,'rptfaultlog&page='+(pagenum-1),null,null,{persist:true});" class="pageskipper"><?php echo $page+1;?></a> of <?php echo $maxpage+1;?>
	&nbsp; &nbsp;
	<a onclick="reloadtab('rptfaultlog',null,'rptfaultlog&page=<?php echo $page+1;?>',null,null,{persist:true});return false;" class="hovlink" href=#>Next<img src="imgs/t.gif" class="img-pageright"></a>
</div>
<?php			$pager=ob_get_clean();
	}
			

	echo $pager;
?>
<style>
.faultdiagdata{display:none;padding:10px 0;}

.faultlogcol1,.faultlogcol2,.faultlogcol3,.faultlogcol4,.faultlogcol5,.faultlogcol6,.faultlogcol7{float:left;overflow:hidden;}
.faultlogcol1{width:11%;margin-right:1%;}
.faultlogcol2{width:14%;margin-right:1%;}
.faultlogcol3{width:17%;margin-right:1%;}
.faultlogcol4{width:14%;margin-right:1%;}
.faultlogcol5{width:14%;margin-right:1%;}
.faultlogcol6{width:14%;margin-right:1%;}
.faultlogcol7{width:10%;}

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
		<div class="faultlogcol1">Time</div>
		<div class="faultlogcol2">User</div>
		<div class="faultlogcol3">Fault</div>
		<div class="faultlogcol4">File</div>
		<div class="faultlogcol5">Func</div>
		<div class="faultlogcol6">Caller</div>
		<div class="faultlogcol7"></div>
		<div class="clear"></div>
	</div></div>

<?php
	$idx=0;
	
	while ($myrow=sql_fetch_array($rs)){
		$faultid=$myrow['faultid'];
		$username=htmlspecialchars($myrow['login']);
		if ($username=='') $username='<span style="color:#ee6666;">'.htmlspecialchars($myrow['logname']).'</span>';
		$logdate=$myrow['faultdate'];
		$dlogdate=date('Y-n-j H:i:s',$logdate);
		$logmessage=$myrow['faultmessage'];
		$obj='';
		if (isset($myrow['rawobj'])) json_decode($myrow['rawobj'],1);
		
		$faultfile=$myrow['faultfile'];
		$faultline=$myrow['faultline'];
		$callfunc=$myrow['callfunc'];
		$callargs=$myrow['callargs'];
		$callfile=$myrow['callfile'];
		$callline=$myrow['callline'];
		$diagdata=$myrow['faultdiagdata'];
?>
	<div class="gridrow<?php if ($idx%2==1) echo ' even';?>">
		<div class="faultlogcol1"><?php echo $dlogdate;?>&nbsp;</div>
		<div class="faultlogcol2"><?php echo $username;?>&nbsp;</div>
		<div class="faultlogcol3"><?php echo htmlspecialchars($logmessage);?>&nbsp;</div>
		<div class="faultlogcol4">
		<?php echo htmlspecialchars($faultfile);?><?php if ($faultline!='') echo '<br>Line '.$faultline;?>&nbsp;
		</div>
		<div class="faultlogcol5"><?php echo $callfunc;?><?php if ($callargs!='') echo '<br><em>('.$callargs.')</em>';?>&nbsp;</div>
		<div class="faultlogcol6">
		<?php echo htmlspecialchars($callfile);?><?php if ($callline!='') echo '<br>Line '.$callline;?>&nbsp;
		</div>
		<div class="faultlogcol7">
			<?php if ($diagdata!=''){?>
			<a class="hovlink" onclick="showhide('faultdiagdata_<?php echo $faultid;?>');">view data</a>
			<?php }?>
		&nbsp;
		</div>
		
		<div class="clear"></div>
		
		<div class="faultdiagdata" id="faultdiagdata_<?php echo $faultid;?>">
			<textarea class="inplong" style="height:80px;"><?php echo htmlspecialchars($diagdata);?></textarea>
		</div>
		
	</div>
<?php
		$idx++;
	}//while
?>
</div><!-- grid -->
</div><!-- stable -->

<?php
	if ($count>=5) echo $pager;
?>

</div><!-- section -->


<?php
	
}