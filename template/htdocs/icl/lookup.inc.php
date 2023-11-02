<?php

function maketailparams(){
	$tailparams='';
		
	$vmode=SGET('vmode');
	if ($vmode=='test'){
			$tailparams='&vmode='.$_GET['vmode'].'&testid='.$_GET['testid'];
	}
	if ($vmode=='heat'){
			$tailparams='&vmode='.$_GET['vmode'].'&recid='.$_GET['recid'];
	}
	
	if ($vmode=='actionlog') $tailparams='&vmode=actionlog';
	
	return $tailparams;
	
}

function showdatepicker(){
	global $db;
	global $dict_mons;
	global $dict_wdays;
	
	$user=userinfo();
	$gsid=$user['gsid'];

	$key=trim(preg_replace('/[^A-Za-z0-9-\ ]/','',SGET('key')));
	
	$mode=preg_replace('/[^A-Za-z0-9-]/','',SGET('mode'));
	$tz=preg_replace('/[^A-Za-z0-9-]/','',SGET('tz'));
	if ($tz=='undefined') $tz='';
	if ($tz!='') date_default_timezone_set($tz);	

	$mini=intval(SGET('mini'));
	
	$hstart=preg_replace('/[^A-Za-z0-9-]/','',SGET('hstart'));
	$hend=preg_replace('/[^A-Za-z0-9-]/','',SGET('hend'));
	
	$dmini='';
	if ($mini) $dmini=', 1';
		
	//get current month and year
	$m=intval(date("n"));
	$y=date("Y");
	$d=date('j');


	if (isset($_GET['nodate'])&&$_GET['nodate']) {
		if ($_GET['y']) $y=$_GET['y'];
		if ($_GET['m']) $m=$_GET['m'];
		if ($_GET['d']) $d=$_GET['d'];
		
?>
	<div id="timepicker">
		<?php showtimepicker($y,$m,$d,$hstart,$hend,60,1,$tz);?>
	</div>
<?php	
		return;	
	}//nodate

	//detect user intent
	if ($key==intval($key)) {
		if ($key>0&&$key<=12) $m=$key;
	}

	$keys=explode(" ",str_replace("-"," ",$key));
	if (strlen($keys[0])==4) $y=$keys[0];
	if (isset($keys[1])&&strlen($keys[1])==4) $y=$keys[1];
	if (strlen($keys[0])<3&&$keys[0]>0&&$keys[0]<=12) $m=$keys[0];
	if (isset($keys[1])&&strlen($keys[1])<3&&$keys[1]>0&&$keys[1]<=12) $m=$keys[1];

	$nm=$m+1;
	$ny=$y;
	$py=$y;
	$pm=$m-1;

	if ($nm>12) {$ny++;$nm-=12;}
	if ($pm<1) {$py--;$pm+=12;}
	
	$woffset=0;
	
	if (isset($_COOKIE['dowoffset'])) $woffset=intval($_COOKIE['dowoffset']);
	if ($woffset<0||$woffset>6) $woffset=0;

	$fd=mktime(0,0,0,$m,1,$y);
	$ld=date('j',mktime(23,59,59,$nm,0,$ny));
	
	$ofd=$fd;
	//$fd=($fd+$woffset)%7;
	
	$pld=date('j',mktime(0,0,0,$m,0,$y));


	$w=date("w",$fd);
	$postdays=7-$ld%7-$w+14;

	$ldx=$ld+$postdays; //add extra rows	
		
	$wdays=$dict_wdays;

	$start=$fd;
	$end=mktime(23,59,59,$nm,0+$postdays,$ny);

	$tailparams=maketailparams();
	
	$colors=array('#c5c0fb','#cdc0f3','#d7c0e9','#dec0e2','#e6c0da','#efc0d1','#f8c0c8','#febbbe','#fea4a7','#ff8588','#ff6a6e','#ff4e52');

	$vmode=SGET('vmode');
	
	if ($vmode=='test'){ // implement blockmap
		$yesdays=array();
		$yesdays["$y-$m-10"]=1; //demo: enable the 10th day of the month
	}
	
	if ($vmode=='heat'){ // sample implementation of a heat map
		$colormaps=array();
		$colormaps["$y-$m-".rand(1,8)]='#848cf7';
		$colormaps["$y-$m-".rand(10,20)]='#ffab00';
	}
	
	if ($vmode=='actionlog'){
		$colormaps=array();
		$query="select * from ".TABLENAME_ACTIONLOG." where ".COLNAME_GSID."='$gsid' and logdate>=$start and logdate<=$end";
		$rs=sql_query($query,$db);

		$logdays=array();
		
		while ($myrow=sql_fetch_assoc($rs)){
			$logdaykey=date('Y-n-j',$myrow['logdate']);
			if (!isset($logdays[$logdaykey])) $logdays[$logdaykey]=0;
			$logdays[$logdaykey]++;
		}//while
		
		foreach ($logdays as $k=>$v){
			$ckey=floor(log($v)/log(2.2))+1; //adjust the second log to adjust the curve
			if ($ckey>11) $ckey=11;
			$colormaps[$k]=$colors[$ckey];	
		}
		
	}//actionlog map
	
			
	$today=date('Y-n-j');
	
	$dmdate=_tr('yearmonth',array('mon'=>$dict_mons[date('n',$fd)],'year'=>date('Y',$fd)));

?>
<div style="width:100%;text-align:center;padding-top:10px;" id="cale_daypicker">

<div style="width:100%;position:relative;margin-top:10px;text-align:center;"><a class="hovlink" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?php echo $y;?>&mode=<?php echo $mode;?>&mini=<?php echo $mini;?>&tz=<?php echo $tz;?>&hstart=<?php echo $hstart;?>&hend=<?php echo $hend;?><?php echo $tailparams;?>');"><?php echo $dmdate;?></a>
<div style="position:absolute;top:-10px;left:0;cursor:pointer;padding:10px;" onclick="<?php if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?php echo $hstart;?>',end:'<?php echo $hend;?>',mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},'<?php echo "$py-$pm"?>');
<?php } else {?>
if (!document.hotspot) {pickdate(null,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},'<?php echo "$py-$pm"?>');return;} document.hotspot.value='<?php echo "$py-$pm"?>';pickdate(document.hotspot,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},null);
<?php }?>"><img src="imgs/t.gif" class="img-pageleft"></div>

<div style="position:absolute;top:-10px;right:0;cursor:pointer;padding:10px;" onclick="<?php if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?php echo $hstart;?>',end:'<?php echo $hend;?>',mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},'<?php echo "$ny-$nm"?>');
<?php } else {?>
if (!document.hotspot) {pickdate(null,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},'<?php echo "$ny-$nm"?>');return;} document.hotspot.value='<?php echo "$ny-$nm"?>';pickdate(document.hotspot,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},null);<?php }?>"><img src="imgs/t.gif" class="img-pageright">
</div>
</div>

<div id="calepicker">
<?php for ($i=0;$i<7;$i++){?>
<div style="width:14%;float:left;">
<div class="caleheader"><?php echo $wdays[($i+$woffset)%7];?></div>
</div>
<?php }?>

<?php
/*
for ($i=0;$i<$w;$i++){?>
<div style="width:14%;float:left;">
<div class="calecell"></div>
</div>
<?php 
}
*/
?>
<?php

$ia=1-$w+$woffset;
$ib=$ldx+$woffset;

if ($ia>0) {$ia-=7;$ib-=7;}

for ($i=$ia;$i<=$ib;$i++){
	$calekey="$y-$m-$i";
	
	$di=$i;
	if ($i>$ld) {
		$di=$i-$ld;
		$calekey="$ny-$nm-$di";
	}
	if ($i<=0){
		$di=$pld+$i;
		$calekey="$py-$pm-$di";		
	}

		
	$block=0;
	if (isset($yesdays)&&is_array($yesdays)&&(!isset($yesdays[$calekey])||!$yesdays[$calekey])) $block=1;	
	
	$dbackground='';
	if (isset($colormaps)&&isset($colormaps[$calekey])&&$colormaps[$calekey]) $dbackground='background:'.$colormaps[$calekey].';';
?>
<div onclick<?php if ($block) echo 'a';?>="<?php if ($mode!='datetime'){?>if (document.hotspot) {document.hotspot.value='<?php echo $calekey?>';if (document.hotspot.onchange) document.hotspot.onchange();if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';}else showday('<?php echo "$y-$m-$i"?>');<?php } else {?>gid('cale_daypicker').style.display='none';ajxpgn('timepicker',document.appsettings.codepage+'?cmd=showtimepicker&y=<?php echo $y;?>&m=<?php echo $m;?>&d=<?php echo $di;?>&start=<?php echo $hstart;?>&end=<?php echo $hend;?>&res=60&tz=<?php echo $tz;?><?php echo $tailparams;?>',1);<?php }?>" style="cursor:pointer;width:14%;float:left;">
<div class="calecell" style="<?php if ($i>$ld||$i<=0) echo 'opacity:0.55;filter:blur(0.5px);font-style:italic;';?><?php echo $dbackground;?><?php if ($today==$calekey&&!$block) echo 'font-weight:bold;color:#ab0200';?><?php if ($block) echo 'opacity:0.4;cursor:not-allowed;filter:alpha(opacity=40);';?>"><?php echo $di;?>
</div></div>
<?php
}
?>
	<div class="clear"></div>
</div>

<?php if (true||date('Y-n',$fd)!=date('Y-n')){?>
<div style="text-align:center;padding-bottom:10px;">
	<a class="hovlink" onclick="if (!document.hotspot) document.hotspot=gid('statusc'); document.hotspot.value='<?php echo date('Y-n-j');?>';pickdate<?php if ($mode=='datetime') echo 'time';?>(document.hotspot,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'},'<?php echo date('Y-n-j');?>');if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();">Today</a>
</div>

</div>
<div style="clear:both;"></div>

<?php }?>

<div id="timepicker" style="display:none;width:100%;position:relative;">
	
</div>
<?php
}

function showtimepicker($y=null,$m=null,$d=null,$start=null,$end=null,$res=null,$h24=1,$tz=null){
	if (!isset($tz)) $tz=preg_replace('/[^A-Za-z0-9-]/','',SGET('tz'));
	if ($tz!='') date_default_timezone_set($tz);	
	
	if (!isset($y)){
		$y=GETVAL('y');
		$m=GETVAL('m');
		$d=GETVAL('d');
		
		if (isset($_GET['start'])) $start=$_GET['start'];
		if (isset($_GET['end'])) $end=$_GET['end'];
		if ($end==24) $end=26;

		$res=SGET('res');	
	}

	
	$base=mktime(0,0,0,$m,$d,$y);
	
	$rstart=$base+$start*3600;
	$rend=$base+$end*3600;
	
	if (isset($_GET['rstart'])&&$_GET['rstart']){
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
		if (isset($_GET['nodate'])&&$_GET['nodate']) $picked=$t;
		$ds=date('I',$val);
		if ($ds) $picked.=' *';

		$dkey=date('Y-n-j',$val-3600);
		$mdkey=date('Y-n-j',$val);
		if ($dkey!=$daykey&&$dkey!=$ldaykey) continue;
	?>
		<div class="caletimeitem">
			<?php if ($i>$rstart-$res*60){?>
			<a style="padding:10px 5px;display:block;margin-right:50px;" onclick="picklookup('<?php echo $picked;?>',<?php echo $val;?>);"><?php echo $t;?>
				<?php if ($ds){?><img src="imgs/t.gif" class="daylightsaving"><?php }?>
			</a>
			<?php }?>
			<?php if ($res>1&&$mdkey==$daykey){?>
			<a style="position:absolute;display:block;padding:1px 5px;font-size:10px;border-radius:5px;background-color:#666666;color:#ffffff;top:20px;right:10px;"
				onclick="this.style.display='none';ajxpgn('subtime_<?php echo $i;?>',document.appsettings.codepage+'?cmd=showtimepicker&nodate=<?php echo intval(SGET('nodate'));?>&y=<?php echo $y;?>&m=<?php echo $m;?>&d=<?php echo $d;?>&rstart=<?php echo $hstart;?>&rend=<?php echo $hend;?>&res=<?php echo $nextres;?>&tz=<?php echo $tz;?>');">...</a>
			<?php }?>
		</div>
		<div id="subtime_<?php echo $i;?>" style="margin:0 10px;">
		
		</div>
	<?php
		
	} //for i
	
}

function pickdatemonths(){
	$defyear=$_GET['defyear'];
	if (!is_numeric($defyear)) $defyear=date('Y');
	
	global $dict_mons;
	
	$tailparams=maketailparams();
	
	$mode=preg_replace('/[^A-Za-z0-9-]/','',SGET('mode'));
	$tz=preg_replace('/[^A-Za-z0-9-]/','',SGET('tz'));
	if ($tz=='undefined') $tz='';
	if ($tz!='') date_default_timezone_set($tz);	

	$mini=intval(SGET('mini'));
	
	$hstart=preg_replace('/[^A-Za-z0-9-]/','',SGET('hstart'));
	$hend=preg_replace('/[^A-Za-z0-9-]/','',SGET('hend'));
	
	$dmini='';
	if ($mini) $dmini=', 1';	
	
	$myyear=date('Y');
	$mymon=date('n');

?>		
<div style="width:100%;position:relative;margin-top:12px;text-align:center;">
	<span classa="hovlink" onclicka="listlookup(this,'Calendar','pickdateyears&defyear=<?php echo $defyear;?>');"><b><?php echo $defyear;?></b></span>
	<div style="position:absolute;top:-10px;left:20px;padding:10px;cursor:pointer;" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?php echo $defyear-1;?>&mode=<?php echo $mode;?>&mini=<?php echo $mini;?>&tz=<?php echo $tz;?>&hstart=<?php echo $hstart;?>&hend=<?php echo $hend;?><?php echo $tailparams;?>');"><img src="imgs/t.gif" class="img-pageleft"></div>
	<div style="position:absolute;top:-10px;right:20px;padding:10px;cursor:pointer;" onclick="listlookup(document.hotspot,'Calendar','pickdatemonths&defyear=<?php echo $defyear+1;?>&mode=<?php echo $mode;?>&mini=<?php echo $mini;?>&tz=<?php echo $tz;?>&hstart=<?php echo $hstart;?>&hend=<?php echo $hend;?><?php echo $tailparams;?>');"><img src="imgs/t.gif" class="img-pageright"></div>
</div>
<div class="section">
<?php
	for ($i=1;$i<=12;$i++){
		switch ($mode){
		case 'datetime':
?>
		<a onclick="document.hotspot.value='<?php echo $defyear.'-'.$i;?>';if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();pickdatetime(document.hotspot,{start:'<?php echo $hstart;?>',end:'<?php echo $hend;?>',mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'});" style="<?php if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?php echo $dict_mons[$i];?></a>	
<?php		
		break;	
		case 'dir':
?>
		<a onclick="picklookup('<?php echo $defyear.'-'.$i;?>');" style="<?php if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?php echo $dict_mons[$i];?></a>	
<?php		
		break;	
			
		default:
	?>
		<a onclick="document.hotspot.value='<?php echo $defyear.'-'.$i;?>';if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();pickdate(document.hotspot,{mini:<?php echo $mini;?>,tz:'<?php echo $tz;?>',params:'<?php echo $tailparams;?>'});" style="<?php if ($defyear==$myyear&&$i==$mymon) echo 'color:#ab0200;'?>;display:block;float:left;width:23%;margin-right:1%;margin-left:1%;padding:10px 0;text-align:center;"><?php echo $dict_mons[$i];?></a>
	<?php	
			
		}
			
	}
?>
	<div class="clear"></div>
</div>
<?php		
}