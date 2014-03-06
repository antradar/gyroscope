<?php

function showdatepicker(){
	global $db;

	$key=trim(GETSTR('key'));

	$mini=GETSTR('mini')+0;
	
	$dmini='';
	if ($mini) $dmini=', 1';
		
	//get current month and year
	$m=date("m")+0;
	$y=date("Y");

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
<div style="width:100%;text-align:center;padding-top:10px;">

<div style="width:100%;position:relative;margin-top:5px;text-align:center;"><?echo date("M Y",$fd);?>
<span style="position:absolute;top:2px;left:12px;cursor:pointer;" onclick="if (!document.hotspot) {pickdate(null,'<?echo "$py-$pm"?>'<?echo $dmini;?>);return;} document.hotspot.value='<?echo "$py-$pm"?>';pickdate(document.hotspot,null<?echo $dmini;?>);"><img class="img-calel" src="imgs/t.gif" width="5" height="12"></span>
<span style="position:absolute;top:2px;right:12px;cursor:pointer;" onclick="if (!document.hotspot) {pickdate(null,'<?echo "$ny-$nm"?>'<?echo $dmini;?>);return;} document.hotspot.value='<?echo "$ny-$nm"?>';pickdate(document.hotspot,null<?echo $dmini;?>);"><img class="img-caler" src="imgs/t.gif" width="5" height="12"></span>
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
<div onclick="if (document.hotspot) {document.hotspot.value='<?echo "$y-$m-$i"?>';if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';}else showday('<?echo "$y-$m-$i"?>');" style="cursor:pointer;width:14%;float:left;">
<div style="height:25px;border:solid 1px #444444;margin:1px;<?if ($today=="$y-$m-$i") echo 'font-weight:bold;color:#ab0200';?>"><?echo $i;?>
</div></div>
<?
}
?>
</div>
</div>
<div style="clear:both;"></div>
<?
}

