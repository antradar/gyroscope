<?php

function showdatepicker(){
	global $db;

	$key=trim(GETSTR('key'));
	
	$mode=GETSTR('mode');

	$mini=GETSTR('mini')+0;
	
	$hstart=GETSTR('hstart');
	$hend=GETSTR('hend');
	
	$dmini='';
	if ($mini) $dmini=', 1';
		
	//get current month and year
	$m=date("m")+0;
	$y=date("Y");

	if ($_GET['nodate']) {
?>
<div id="timepicker">
	<?showtimepicker($y,$m,1,$hstart,$hend,60);?>
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

	$wdays=array('Su','Mo','Tu','We','Th','Fr','Sa');

	$start=$fd;
	$end=mktime(23,59,59,$nm,0,$ny);
	
	$today=date('Y-n-j');

?>
<div style="width:100%;text-align:center;padding-top:10px;" id="cale_daypicker">

<div style="width:100%;position:relative;margin-top:5px;text-align:center;"><?echo date("M Y",$fd);?>
<span style="position:absolute;top:2px;left:12px;cursor:pointer;" onclick="<?if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?echo $hstart;?>',end:'<?echo $hend;?>',mini:<?echo $mini;?>},'<?echo "$py-$pm"?>');
<?} else {?>
if (!document.hotspot) {pickdate(null,{mini:<?echo $mini;?>},'<?echo "$py-$pm"?>');return;} document.hotspot.value='<?echo "$py-$pm"?>';pickdate(document.hotspot,{mini:<?echo $mini;?>},null);
<?}?>"><img class="img-calel" src="imgs/t.gif" width="5" height="12"></span>

<span style="position:absolute;top:2px;right:12px;cursor:pointer;" onclick="<?if ($mode=='datetime'){?>
pickdatetime(null,{start:'<?echo $hstart;?>',end:'<?echo $hend;?>',mini:<?echo $mini;?>},'<?echo "$ny-$nm"?>');
<?} else {?>
if (!document.hotspot) {pickdate(null,{mini:<?echo $mini;?>},'<?echo "$ny-$nm"?>');return;} document.hotspot.value='<?echo "$ny-$nm"?>';pickdate(document.hotspot,{mini:<?echo $mini;?>},null);<?}?>"><img class="img-caler" src="imgs/t.gif" width="5" height="12"></span>
</div>

<div id="calepicker" style="font-size:12px;width:100%;height:200px;margin:0 auto;margin-top:5px;">
<?for ($i=0;$i<7;$i++){?>
<div style="width:14%;float:left;">
<div style="height:20px;border:solid 1px #ffffff;margin-left:1px;"><?echo $wdays[$i];?></div>
</div>
<?}?>
<?for ($i=0;$i<$w;$i++){?>
<div style="width:14%;float:left;">
<div style="height:25px;border:solid 1px #444444;margin:1px;"></div>
</div>
<?}?>
<?
for ($i=1;$i<=$ld;$i++){
?>
<div onclick="<?if ($mode!='datetime'){?>if (document.hotspot) {document.hotspot.value='<?echo "$y-$m-$i"?>'; if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';}else showday('<?echo "$y-$m-$i"?>');<?} else {?>gid('cale_daypicker').style.display='none';ajxpgn('timepicker',document.appsettings.codepage+'?cmd=showtimepicker&y=<?echo $y;?>&m=<?echo $m;?>&d=<?echo $i;?>&start=<?echo $hstart;?>&end=<?echo $hend;?>&res=60',1);<?}?>" style="cursor:pointer;width:14%;float:left;">
<div style="height:25px;border:solid 1px #444444;margin:1px;<?if ($today=="$y-$m-$i") echo 'font-weight:bold;color:#ab0200';?>"><?echo $i;?>
</div></div>
<?
}
?>
</div>
</div>
<div style="clear:both;"></div>

<div id="timepicker" style="display:none;">
	
</div>
<?
}

function showtimepicker($y=null,$m=null,$d=null,$start=null,$end=null,$res=null,$h24=1){
	
	if (!isset($y)){
		$y=GETVAL('y');
		$m=GETVAL('m');
		$d=GETVAL('d');
		
		if ($_GET['start']) $start=$_GET['start'];
		if ($_GET['end']) $end=$_GET['end'];

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
	
	
	for ($i=$rstart-$res*60;$i<=$rend;$i+=$res*60){
	
		$val=$i;
		$t=date('g:ia',$val);	
		$hstart=$val+$nextres*60;
		$hend=$val+$res*60-$nextres*60;

		$picked=date('Y-n-j',$val).' '.$t;
		if ($_GET['nodate']) $picked=$t;
	?>
		<div style="position:relative;height:30px;border-bottom:solid 1px #999999;">
			<?if ($i>$rstart-$res*60){?>
			<a style="padding:10px 5px;display:block;margin-right:50px;" onclick="picklookup('<?echo $picked;?>',<?echo $val;?>);"><?echo $t;?></a>
			<?}?>
			<?if ($res>1){?>
			<a style="position:absolute;display:block;padding:1px 5px;font-size:10px;border-radius:5px;background-color:#666666;color:#ffffff;top:20px;right:10px;"
				onclick="this.style.display='none';ajxpgn('subtime_<?echo $i;?>',document.appsettings.codepage+'?cmd=showtimepicker&nodate=<?echo $_GET['nodate']+0;?>&y=<?echo $y;?>&m=<?echo $m;?>&d=<?echo $d;?>&rstart=<?echo $hstart;?>&rend=<?echo $hend;?>&res=<?echo $nextres;?>');">more</a>
			<?}?>
		</div>
		<div id="subtime_<?echo $i;?>" style="margin:0 10px;">
		
		</div>
	<?
		
	} //for i
	
}
