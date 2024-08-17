<?php

/*
# /mods_available/opcache.ini

#revalidate every 300 seconds

opcache.revalidate_freq=300

*/

//opcache_reset();

include 'lb.php';

$mode=$_GET['mode']??'';

if ($_SERVER['REMOTE_ADDR']!='127.0.0.1'&&$_SERVER['REMOTE_ADDR']!='::1') {
	die('This script is only available on localhost');
	if ($mode==''){
	?>
	<div class="funcgroup warn">
		Remember to disable this script for non-localhost access.
	</div>
	<?php
	}
}



if ($mode=='reset'){
	opcache_reset();
	echo 'opcache reset at '.date('H:i:s');
	die();
}

if ($mode=='invalidate'){
	$res=opcache_get_status();
	//echo '<pre>'; print_r($res); echo '</pre>';
	$scrs=array_keys($res['scripts']);
	foreach ($scrs as $scr) opcache_invalidate($scr);
	echo "Invalidated ".count($scrs)." scripts at ".date('H:i:s');
	die();
}

if ($mode=='showcache'){
	$res=opcache_get_status();
	
	$mem_used=$res['memory_usage']['used_memory'];
	$mem_free=$res['memory_usage']['free_memory'];
	$mem_wasted=$res['memory_usage']['wasted_memory'];
	$mem_total=$mem_used+$mem_free+$mem_wasted;
	if ($mem_total>0){
		$mem_pct=round($mem_used*100/$mem_total,2);
		$mem_wpct=round($mem_wasted*100/$mem_total,2);
	?>
	<div class="statlabel">Memory: &nbsp; <em>(<?php echo round($mem_used/1024/1024);?> / <?php echo round($mem_total/1024/1024);?> MB)</em></div>
	<div class="pbar unused">
		<div class="sbar used" style="float:left;width:<?php echo $mem_pct;?>%;"><span><?php echo $mem_pct;?>%</span></div>
		<div class="sbar wasted" style="float:right;width:<?php echo $mem_wpct;?>%;"></div>
		<div style="clear:both;"></div>
	</div>
	<?php	
		
	}
	
	$mem_used=$res['interned_strings_usage']['used_memory'];
	$mem_total=$res['interned_strings_usage']['buffer_size'];
	$nstrs=$res['interned_strings_usage']['number_of_strings'];
	if ($mem_total>0){
		$mem_pct=round($mem_used*100/$mem_total,2);
	?>
	<div class="statlabel">Interned Strings: &nbsp; <em>(<?php echo round($mem_used/1024/1024);?> / <?php echo round($mem_total/1024/1024);?> MB in <?php echo $nstrs;?> Strings)</em></div>
	<div class="pbar unused">
		<div class="sbar used" style="width:<?php echo $mem_pct;?>%;"><span><?php echo $mem_pct;?>%</span></div>
	</div>
	<?php	
		
	}
	
	
	$hitrate=round($res['opcache_statistics']['opcache_hit_rate'],2);
	?>
	<div class="statlabel">Hit Rate: &nbsp; <em>(<?php echo $res['opcache_statistics']['oom_restarts'].' OOM Restarts';?>)</em></div>
	<div class="pbar miss">
		<div class="sbar unused" style="width:<?php echo $hitrate;?>%;"><span><?php echo $hitrate;?>%</span></div>
	</div>

	<?php
	$scrs=$res['scripts'];
	$scrmem=0;
	$maxmem=0;
	foreach ($scrs as $scr) {$scrmem+=$scr['memory_consumption']; if ($maxmem<$scr['memory_consumption']) $maxmem=$scr['memory_consumption'];}
	uasort($scrs,function($a,$b){
		$va=$a['memory_consumption'];
		$vb=$b['memory_consumption'];
		if ($va==$vb) return 0;
		if ($va<$vb) return 1; else return -1;	
	});
	?>
		
	<div class="statlabel">Scripts: &nbsp; <em>(<?php echo round($scrmem/1024/1024);?> MB in <?php echo count($res['scripts']);?> files)</em></div>

	<div class="pbar blank">
		<?php
		$idx=0; 
		foreach ($scrs as $fn=>$scr){
			$itemclass='even';
			if ($idx%2==1) $itemclass='odd';
			
	?>
		<div onmouseover="this.onclick();" onclick="sn('<?php echo $fn;?>',<?php echo round($scr['memory_consumption']/1024);?>);" class="sbar item_<?php echo $itemclass;?>" style="width:<?php echo round($scr['memory_consumption']*100/$scrmem,2);?>%;float:left;"></div>
		<?php 
		$idx++;
			if ($scr['memory_consumption']<$maxmem/22) break;
		}
		?>
		<div style="clear:both;"></div>
	</div>
	
	<div id="scriptname"></div>
	
	
		
	<?php
	
	
	//echo '<pre>'; print_r($scrs); echo '</pre>';
	die();	
}


//die('nopcache disabled'); //disable this script on production

if (!is_callable('opcache_reset')) die('opcache disabled. nothing to do here.');

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Opcache Manager</title>
	<meta id="viewport" name="viewport" content="width=device-width" />
	<style>
	#page{font-size:16px;}
	#res{padding:5px 10px;}
	#cacheres,#resetres{padding-top:20px;}
	.statlabel{font-size:16px;margin-bottom:5px;}
	.funcgroup{border:solid 1px #848cf7;border-radius:10px;padding:20px 10px;margin-bottom:10px;max-width:500px;}
	.funcgroup.warn{border-color:#ff0000;color:#ab0200;}
	button{color:#ffffff;cursor:pointer;box-shadow:none;padding:8px 20px;border-radius:3px;-webkit-appearance:none;background:#187CA6;box-shadow:0px 1px 2px #c9c9c9;border:none;}
	button:hover{background:#29ABE1;}
	
	#scriptname{font-size:15px;padding:5px 20px;overflow-wrap:break-word;min-height:60px;}
	
	.pbar{overflow:hidden;border-radius:2px;max-width:400px;margin-bottom:20px;}
		.pbar.unused, .sbar.unused{background:#8CDB00;}
		.sbar.wasted{background:#ab0200;}
		.pbar.miss{background:#D73A4A}
		.pbar.blank{background:#516E70;margin-bottom:5px;}
		
	.sbar{height:18px;font-size:14px;}
		.sbar.used{background:#E0B88D;}
		.sbar.item_odd{background:#E0B88D;}
		.sbar.item_even{background:#8CDB00;}
		
	.sbar span{margin-left:5px;display:inline-block;}
	
	@media (prefers-color-scheme:dark) {
		input{border:solid 1px #37484B;background:#1A2227;color:#E6EDF3;}
		input:disabled{color:#BB8B2C;background-color:#353A2C;border-color:#6B7247;}
		button{background:#29ABE1;color:#ffffff;border:solid 1px #388BFD;}
		button:hover{background:#125B7A;}
		body{background:#0D1117;color:#E6EDF3;}
		.pbar.used, .sbar.unused{background:#332F2C;}
		.pbar.miss{background:#321820;}
		.pbar.blank{background:#1A2227;}
		.sbar{color:#1A2227;}
		.sbar.unused{background:#3FB950;}
		.sbar.item_odd{background:#7E9FA1;}
		.sbar.item_even{background:#DFA963;}
	}	
	
	</style>
</head>
<body>
<div id="page">
	<div class="funcgroup">
		Clear: &nbsp; &nbsp;
		<button onclick="ajxpgn('resetres','nopcache.php?mode=invalidate');">Invalidate</button>
		&nbsp; &nbsp;
		<button onclick="ajxpgn('resetres','nopcache.php?mode=reset');">Reset</button>
		<div id="resetres"></div>
	</div>
	<div class="funcgroup">
		Auto refresh every <input id="freq" value="5" style="width:30px;text-align:right;padding:5px;"> seconds
		<div style="padding:10px;">
			<button onclick="toggleresetter(this);">Start</button>
		</div>
		<div id="res"></div>
	</div>
	
	<div class="funcgroup">
		<button onclick="showcache();">Show Cache Stats.</button>
		<div id="cacheres"></div>
	</div>
	
</div>
<script src="nano.js"></script>
<script>
function toggleresetter(d){
	var freq=parseInt(gid('freq').value,10);
	if (!freq) return;
	
	if (!d.playing){
		d.playing=1;
		d.innerHTML='Pause';
		gid('freq').disabled='disabled';
		document.itv=setInterval(itv_invalidate,freq*1000);
		itv_invalidate();	
	} else {
		d.playing=null;
		d.innerHTML='Resume';
		if (document.itv) clearInterval(document.itv);	
		gid('freq').disabled='';
		gid('res').innerHTML='cache refresher paused';
	}
}

function showcache(){
	ajxpgn('cacheres','nopcache.php?mode=showcache');	
}

function itv_invalidate(){
	ajxpgn('res','nopcache.php?mode=invalidate');	
}

function sn(fn,size){
	var o=gid('scriptname');
	gid('scriptname').innerHTML=fn+' &nbsp; '+size+'KB';
	if (o.timer) clearTimeout(o.timer);
	o.timer=setTimeout(function(){o.innerHTML='&nbsp;';},2000);
}

</script>
</body>
</html>
<?php

