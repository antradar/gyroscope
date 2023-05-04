<html>
<head>
<link href="https://use.fontawesome.com/releases/v6.1.1/css/all.css" rel="stylesheet">
</head>
<body>
<div style="padding:20px 0;">
	<a href="https://fontawesome.com/icons" target=_blank>Find icons &raquo;</a>
</div>


<?php

$facecolor='#72ADDE';
$darkback='#666666';//'#EAF170';//'#666666';//'#72ADDE';//'#EDF3F3';//'#18E022';//'#FF5940';//'#EDF3F3'; //'#666666'; //small face
$lightback='#ffffff';
$backbg='#CCCCCC';//'#2D3239';//'#0D1117';//'#CCCCCC';//'#0D1117';//'#2D3239';//'#CCCCCC';

$icons=array(
	array('name'=>'user','adjust'=>-6),
	array('name'=>'cog','adjust'=>-6),
	array('name'=>'grip','adjust'=>4),
	array('name'=>'expand','adjust'=>44),
	array('name'=>'compress','adjust'=>44),
	array('name'=>'magnifying-glass','adjust'=>-14),
	array('name'=>'angle-down','adjust'=>0),
	array('name'=>'chart-bar','adjust'=>-4),
	array('name'=>'temperature-full','adjust'=>-18),
	array('name'=>'clipboard-list','adjust'=>-4),
	array('name'=>'file-contract','adjust'=>0),
	array('name'=>'folder-open','adjust'=>-6,'style'=>'r','subs'=>array(
		array('name'=>'file-image','adjust'=>-24,'dx'=>14,'dy'=>-2,'style'=>'r')
	)),
	array('name'=>'folder-open','adjust'=>0),
	array('name'=>'folder-open','adjust'=>0,'style'=>'r','subs'=>array(
		array('name'=>'camera-retro','adjust'=>-36,'dx'=>16,'dy'=>20)
	)),
	array('name'=>'gamepad','adjust'=>-16),

	array('name'=>'microphone','adjust'=>-10),
	array('name'=>'unlink','adjust'=>-14),
	array('name'=>'bell-slash','adjust'=>-18,'style'=>'r'),
	array('name'=>'home','adjust'=>-14),
	array('name'=>'comment-dots','adjust'=>-14,'style'=>'r'),
	
	array('name'=>'clock','adjust'=>-18,'style'=>'r'),
	
	array('name'=>'chevron-left','adjust'=>-8),
	array('name'=>'chevron-right','adjust'=>-8),
		
	array('name'=>'flask','adjust'=>0),
	
	array('name'=>'calendar','adjust'=>-2,'style'=>'r','subs'=>array(
		array('name'=>'glass-martini','adjust'=>-34,'dx'=>14,'dy'=>25)
	)),
	array('name'=>'tags','adjust'=>-8),
	array('name'=>'shopping-basket','adjust'=>-12),
	array('name'=>'gift','adjust'=>0),
	array('name'=>'calendar-alt','adjust'=>-2,'style'=>'s'),
	array('name'=>'utensils','adjust'=>0),
	array('name'=>'elementor','adjust'=>0,'style'=>'b'),
	array('name'=>'comment-alt','adjust'=>-4),
	array('name'=>'map-marker','adjust'=>0)
);
?>

<div style="margin-bottom:10px;">
SD Icons:
</div>

<div class="clear"></div>
<?php

foreach ($icons as $icon){
	$subs=isset($icon['subs'])?$icon['subs']:null;
	$style=isset($icon['style'])?$icon['style']:'';
	if ($style=='') $style='s';
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 32+ceil($icon['adjust']/2);?>px;margin-right:10px;position:relative;">
	<?php
	if (is_array($subs)){
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 32+ceil($sub['adjust']/2);?>px;position:absolute;top:<?php echo floor($sub['dy']/2);?>px;left:<?php echo floor($sub['dx']/2);?>px;"></span>
	<?php		
		}//foreach	
	}//subs
	?>
	</span>
	
	<?php
	if (is_array($subs)){
	?>
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 32+ceil($icon['adjust']/2);?>px;margin-right:10px;position:relative;">
	<?php
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	+ <span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 32+ceil($sub['adjust']/2);?>px;"></span>
	<?php
		}
	}
	?>	
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?php echo 24+floor($icon['adjust']*12/32);?>px;margin-right:10px;">
	<?php 
	if (is_array($subs)){
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:#ffffff;font-size:<?php echo 24+ceil($sub['adjust']*12/32);?>px;position:absolute;top:<?php echo floor($sub['dy']*12/32);?>px;left:<?php echo floor($sub['dx']*12/32);?>px;"></span>
	<?php		
		}//foreach	
	}//subs
	?>
	</span>
	<?php
	if (is_array($subs)){
	?>
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="color:#ffffff;font-size:<?php echo 24+ceil($icon['adjust']*12/32);?>px;"></span>
	<?php
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span style="color:#ffffff;">+</span> <span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:#ffffff;font-size:<?php echo 24+ceil($sub['adjust']*12/32);?>px;"></span>	
	<?php
		}
	}
	?>
</div>
</div>

<?php }?>


<div style="clear:both;margin-bottom:40px;"></div>

<div style="margin-bottom:10px;">
SD Tab Icons:
</div>

<div class="clear"></div>
<?php

foreach ($icons as $icon){
	$subs=isset($icon['subs'])?$icon['subs']:null;
	$style=isset($icon['style'])?$icon['style']:'';
	if ($style=='') $style='s';
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="color:<?php echo $darkback;?>;font-size:<?php echo 16+round($icon['adjust']/4);?>px;margin-right:10px;position:relative;"></span>
</div>

<div style="padding:10px;background:<?php echo $backbg;?>;float:left;">
	<div style="position:relative;">	
		<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:<?php echo $darkback;?>;font-size:<?php echo 16+round($icon['adjust']/4);?>px;margin-right:10px;"></span>
	</div>
</div>


<?php }?>

<div style="clear:both;padding-top:20px;margin-bottom:10px;">
HD Tab Icons:
</div>

<div class="clear"></div>
<?php

foreach ($icons as $icon){
	$subs=isset($icon['subs'])?$icon['subs']:null;
	$style=isset($icon['style'])?$icon['style']:'';
	if ($style=='') $style='s';
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="color:<?php echo $darkback;?>;font-size:<?php echo 32+round($icon['adjust']/2);?>px;margin-right:10px;position:relative;"></span>
</div>

<div style="padding:10px;background:<?php echo $backbg;?>;float:left;">
	<div style="position:relative;">	
		<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:<?php echo $darkback;?>;font-size:<?php echo 32+round($icon['adjust']/2);?>px;margin-right:10px;"></span>
	</div>
</div>


<?php }?>

<div style="clear:both;padding-top:20px;margin-bottom:10px;">
	HD Icons:
</div>

<?php foreach ($icons as $icon){
	$subs=isset($icon['subs'])?$icon['subs']:null;
	$style=isset($icon['style'])?$icon['style']:'';
	if ($style=='') $style='s';
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:<?php echo $facecolor;?>;font-size:<?php echo 64+$icon['adjust'];?>px;">
	<?php
	if (is_array($subs)){
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 64+$sub['adjust'];?>px;position:absolute;top:<?php echo $sub['dy'];?>px;left:<?php echo $sub['dx'];?>px;"></span>
	<?php		
		}//foreach	
	}//subs
	?>
	</span>
	
	<?php
	if (is_array($subs)){
	?>
	<span class="fa fa-<?php echo $icon['name'];?>" style="position:relative;color:<?php echo $facecolor;?>;font-size:<?php echo 64+$icon['adjust'];?>px;">
	<?php
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span style="font-size:22px;vertical-align:middle;">+</span> <span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:<?php echo $facecolor;?>;font-size:<?php echo 64+$sub['adjust'];?>px;"></span>
	<?php
		}
	}
	?>		
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?php echo 48+$icon['adjust'];?>px;">
	<?php
	if (is_array($subs)){
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle=='') $sstyle='s';
	?>
	<span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:#ffffff;font-size:<?php echo 48+ceil($sub['adjust']*24/32);?>px;position:absolute;top:<?php echo floor($sub['dy']*24/32);?>px;left:<?php echo floor($sub['dx']*24/32);?>px;"></span>
	<?php		
		}//foreach	
	}//subs
	?>
	</span>
	<?php
	if (is_array($subs)){
	?>
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?php echo 48+$icon['adjust'];?>px;">
	<?php
		foreach ($subs as $sub){
			$sstyle=isset($sub['style'])?$sub['style']:'';
			if ($sstyle='') $sstyle='s';
	?>
	<span style="color:#ffffff;font-size:20px;vertical-align:middle;">+</span> 
	<span class="fa<?php echo $sstyle;?> fa-<?php echo $sub['name'];?>" style="color:#ffffff;font-size:<?php echo 48+ceil($sub['adjust']*24/32);?>px;"></span>
	<?php
		}
	}
	?>	
</div>
</div>


<?php }?>

<div style="clear:both;"></div>

</body>
</html>
