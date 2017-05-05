<?php

function showdatepicker(){
	global $db;
	global $dict_mons;
	global $dict_wdays;

	$key=trim(GETSTR('key'));
	
	$mode=GETSTR('mode');
	$tz=GETSTR('tz');
	if ($tz=='undefined') $tz='';
	if ($tz!='') date_default_timezone_set($tz);	

	$mini=GETSTR('mini')+0;
	
	$hstart=GETSTR('hstart');
	$hend=GETSTR('hend');
	
	$dmini='';
	if ($mini) $dmini=', 1';
		
	//get current month and year
	$m=date("n")+0;
	$y=date("Y");
	$d=date('j');


	if ($_GET['nodate']) {
		if ($_GET['y']) $y=$_GET['y'];
		if ($_GET['m']) $m=$_GET['m'];
		if ($_GET['d']) $d=$_GET['d'];
		
?>
<div id="timepicker">
	<?showtimepicker($y,$m,$d,$hstart,$hend,60,1,$tz);?>
</div>
<?	
		return;	
	}//nodate

	//detect user intent
	if ($key==($key+0)) {
		if ($key>0&&$key<=12) $m=$key;
	}

	$keys=explode(" ",str_replace("-"," ",$key));
	if (strlen($keys[0])==4) $y=$keys[0];
	if (strlen($keys[1])==4) $y=$keys[1];
	if (strlen($keys[0])<3&&$keys[0]>0&&$keys[0]<=12) $m=$keys[0];
	if (strlen($keys[1])<3&&$keys[1]>0&&$keys[1]<=12) $m=$keys[1];

	$nm=$m+1;
	$ny=$y;
	$py=$y;
	$pm=$m-1;

	if ($nm>12) {$ny++;$nm-=12;}
	if ($pm<1) {$py--;$pm+=12;}

	$fd=mktime(1,1,1,$m,1,$y);
	$ld=date('j',mktime(23,59,59,$nm,0,$ny));
	$w=date("w",$fd);

	$wdays=$dict_wdays;

	$start=$fd;
	$end=mktime(23,59,59,$nm,0,$ny);
	
	$today=date('Y-n-j');
	
	$dmdate=_tr('yearmonth',array('mon'=>$dict_mons[date('n',$fd)],'year'=>date('Y',$fd)));

?>
<div style="width:100%;text-align:center;padding-top:10px;" id="cale_daypicker">

<div style="width:100%;position:relative;margin-top:5px;text-align:center;"><a class="hovlink" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?echo $y;?>&mode=<?echo $mode;?>&mini=<?echo $mini;?>&tz=<?echo $tz;?>&hstart=<?echo $hstart;?>&hend=<?echo $hend;?>');"><?echo $dmdate;?></a>
<span style="position:absolute;top:2px;left:12px;cursor:pointer;" onclick="<?if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?echo $hstart;?>',end:'<?echo $hend;?>',mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},'<?echo "$py-$pm"?>');
<?} else {?>
if (!document.hotspot) {pickdate(null,{mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},'<?echo "$py-$pm"?>');return;} document.hotspot.value='<?echo "$py-$pm"?>';pickdate(document.hotspot,{mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},null);
<?}?>">&laquo;</span>

<span style="position:absolute;top:2px;right:12px;cursor:pointer;" onclick="<?if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?echo $hstart;?>',end:'<?echo $hend;?>',mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},'<?echo "$ny-$nm"?>');
<?} else {?>
if (!document.hotspot) {pickdate(null,{mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},'<?echo "$ny-$nm"?>');return;} document.hotspot.value='<?echo "$ny-$nm"?>';pickdate(document.hotspot,{mini:<?echo $mini;?>,tz:'<?echo $tz;?>'},null);<?}?>">&raquo;</span>
</div>

<div id="calepicker">
<?for ($i=0;$i<7;$i++){?>
<div style="width:14%;float:left;">
<div class="caleheader"><?echo $wdays[$i];?></div>
</div>
<?}?>
<?for ($i=0;$i<$w;$i++){?>
<div style="width:14%;float:left;">
<div class="calecell"></div>
</div>
<?}?>
<?
for ($i=1;$i<=$ld;$i++){
?>
<div onclick="<?if ($mode!='datetime'){?>if (document.hotspot) {document.hotspot.value='<?echo "$y-$m-$i"?>';if (document.hotspot.onchange) document.hotspot.onchange();if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';}else showday('<?echo "$y-$m-$i"?>');<?} else {?>gid('cale_daypicker').style.display='none';ajxpgn('timepicker',document.appsettings.codepage+'?cmd=showtimepicker&y=<?echo $y;?>&m=<?echo $m;?>&d=<?echo $i;?>&start=<?echo $hstart;?>&end=<?echo $hend;?>&res=60&tz=<?echo $tz;?>',1);<?}?>" style="cursor:pointer;width:14%;float:left;">
<div class="calecell" style="<?if ($today=="$y-$m-$i") echo 'font-weight:bold;color:#ab0200';?>"><?echo $i;?>
</div></div>
<?
}
?>
</div>
</div>
<div style="clear:both;"></div>

<div id="timepicker" style="display:none;width:100%;position:relative;">
	
</div>
<?
}

function showtimepicker($y=null,$m=null,$d=null,$start=null,$end=null,$res=null,$h24=1,$tz=null){
	if (!isset($tz)) $tz=GETSTR('tz');
	if ($tz!='') date_default_timezone_set($tz);	
	
	if (!isset($y)){
		$y=GETVAL('y');
		$m=GETVAL('m');
		$d=GETVAL('d');
		
		if ($_GET['start']) $start=$_GET['start'];
		if ($_GET['end']) $end=$_GET['end'];
		if ($end==24) $end=26;

		$res=GETSTR('res');	
	}

	
	$base=mktime(0,0,0,$m,$d,$y);
	
	$rstart=$base+$start*3600;
	$rend=$base+$end*3600;
	
	if ($_GET['rstart']){
		$rstart=$_GET['rstart'];
		$rend=$_GET['rend'];	
	}

	if ($rend<$rstart) $rend=$rstart;

	$nextres=$res/4;
	if ($res==15) $nextres=1;
	
	$daykey=date('Y-n-j',$base);
	$ldaykey=date('Y-n-j',$base-3600);
	
	for ($i=$rstart-$res*60;$i<=$rend;$i+=$res*60){
	
		$val=$i;
		$t=date('g:ia',$val);	
		$hstart=$val+$nextres*60;
		$hend=$val+$res*60-$nextres*60;

		$picked=date('Y-n-j',$val).' '.$t;
		if ($_GET['nodate']) $picked=$t;
		$ds=date('I',$val);
		if ($ds) $picked.=' *';

		$dkey=date('Y-n-j',$val-3600);
		$mdkey=date('Y-n-j',$val);
		if ($dkey!=$daykey&&$dkey!=$ldaykey) continue;
	?>
		<div class="caletimeitem">
			<?if ($i>$rstart-$res*60){?>
			<a style="padding:10px 5px;display:block;margin-right:50px;" onclick="picklookup('<?echo $picked;?>',<?echo $val;?>);"><?echo $t;?>
				<?if ($ds){?><img src="imgs/t.gif" class="daylightsaving"><?}?>
			</a>
			<?}?>
			<?if ($res>1&&$mdkey==$daykey){?>
			<a style="position:absolute;display:block;padding:1px 5px;font-size:10px;border-radius:5px;background-color:#666666;color:#ffffff;top:20px;right:10px;"
				onclick="this.style.display='none';ajxpgn('subtime_<?echo $i;?>',document.appsettings.codepage+'?cmd=showtimepicker&nodate=<?echo $_GET['nodate']+0;?>&y=<?echo $y;?>&m=<?echo $m;?>&d=<?echo $d;?>&rstart=<?echo $hstart;?>&rend=<?echo $hend;?>&res=<?echo $nextres;?>&tz=<?echo $tz;?>');">...</a>
			<?}?>
		</div>
		<div id="subtime_<?echo $i;?>" style="margin:0 10px;">
		
		</div>
	<?
		
	} //for i
	
}

function pickdatemonths(){
	$defyear=$_GET['defyear']+0;
	if (!$defyear) $defyear=date('Y');
	
	global $dict_mons;
	
	$mode=GETSTR('mode');
	$tz=GETSTR('tz');
	if ($tz=='undefined') $tz='';
	if ($tz!='') date_default_timezone_set($tz);	

	$mini=GETSTR('mini')+0;
	
	$hstart=GETSTR('hstart');
	$hend=GETSTR('hend');
	
	$dmini='';
	if ($mini) $dmini=', 1';	
	
	$myyear=date('Y');
	$mymon=date('n');

?>		
<div style="width:100%;position:relative;margin-top:5px;text-align:center;">
	<span classa="hovlink" onclicka="listlookup(this,'Calendar','pickdateyears&defyear=<?echo $defyear;?>');"><?echo $defyear;?></span>
	<span style="position:absolute;top:2px;left:12px;cursor:pointer;" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?echo $defyear-1;?>&mode=<?echo $mode;?>&mini=<?echo $mini;?>&tz=<?echo $tz;?>&hstart=<?echo $hstart;?>&hend=<?echo $hend;?>');">&laquo;</span>
	<span style="position:absolute;top:2px;right:12px;cursor:pointer;" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?echo $defyear+1;?>&mode=<?echo $mode;?>&mini=<?echo $mini;?>&tz=<?echo $tz;?>&hstart=<?echo $hstart;?>&hend=<?echo $hend;?>');">&raquo;</span>
</div>
<div class="section">
<?
	for ($i=1;$i<=12;$i++){
		switch ($mode){
		case 'datetime':
?>
		<a onclick="document.hotspot.value='<?echo $defyear.'-'.$i;?>';pickdatetime(document.hotspot,{start:'<?echo $hstart;?>',end:'<?echo $hend;?>',mini:<?echo $mini;?>,tz:'<?echo $tz;?>'});" style="<?if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?echo $dict_mons[$i];?></a>	
<?		
		break;	
		case 'dir':
?>
		<a onclick="picklookup('<?echo $defyear.'-'.$i;?>');" style="<?if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?echo $dict_mons[$i];?></a>	
<?		
		break;	
			
		default:
	?>
		<a onclick="document.hotspot.value='<?echo $defyear.'-'.$i;?>';pickdate(document.hotspot,{mini:<?echo $mini;?>,tz:'<?echo $tz;?>'});" style="<?if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?echo $dict_mons[$i];?></a>
	<?	
			
		}
			
	}
?>
	<div class="clear"></div>
</div>
<?		
}