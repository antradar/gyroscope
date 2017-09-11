<html>
<head>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div style="padding:20px 0;">
	<a href="http://fortawesome.github.io/Font-Awesome/icons/" target=_blank>Find icons &raquo;</a>
</div>
<?
$icons=array(array('name'=>'user','adjust'=>0),
	array('name'=>'folder-open-o','adjust'=>-6,'subs'=>array(
		array('name'=>'file-image-o','adjust'=>-24,'dx'=>14,'dy'=>-2)
	)),
	array('name'=>'folder-o','adjust'=>0),
	array('name'=>'folder-o','adjust'=>0,'subs'=>array(
		array('name'=>'camera-retro','adjust'=>-36,'dx'=>16,'dy'=>20)
	)),
	array('name'=>'flask','adjust'=>0),
	array('name'=>'calendar-o','adjust'=>-2,'subs'=>array(
		array('name'=>'glass','adjust'=>-34,'dx'=>14,'dy'=>25)
	)),
	array('name'=>'tags','adjust'=>-4),
	array('name'=>'map-marker','adjust'=>0)
);

foreach ($icons as $icon){
	$subs=$icon['subs'];
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#72ADDE;font-size:<?echo 32+ceil($icon['adjust']/2);?>px;margin-right:10px;position:relative;">
	<?
	if (is_array($subs)){
		foreach ($subs as $sub){
	?>
	<span class="fa fa-<?echo $sub['name'];?>" style="color:#72ADDE;font-size:<?echo 32+ceil($sub['adjust']/2);?>px;position:absolute;top:<?echo floor($sub['dy']/2);?>px;left:<?echo floor($sub['dx']/2);?>px;"></span>
	<?		
		}//foreach	
	}//subs
	?>
	</span>
	
	<?
	if (is_array($subs)){
	?>
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#72ADDE;font-size:<?echo 32+ceil($icon['adjust']/2);?>px;margin-right:10px;position:relative;">
	<?
		foreach ($subs as $sub){
	?>
	+ <span class="fa fa-<?echo $sub['name'];?>" style="color:#72ADDE;font-size:<?echo 32+ceil($sub['adjust']/2);?>px;"></span>
	<?
		}
	}
	?>	
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa fa-<?echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?echo 24+floor($icon['adjust']*12/32);?>px;margin-right:10px;">
	<?
	if (is_array($subs)){
		foreach ($subs as $sub){
	?>
	<span class="fa fa-<?echo $sub['name'];?>" style="color:#ffffff;font-size:<?echo 24+ceil($sub['adjust']*12/32);?>px;position:absolute;top:<?echo floor($sub['dy']*12/32);?>px;left:<?echo floor($sub['dx']*12/32);?>px;"></span>
	<?		
		}//foreach	
	}//subs
	?>
	</span>
	<?
	if (is_array($subs)){
	?>
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#ffffff;font-size:<?echo 24+ceil($icon['adjust']*12/32);?>px;"></span>
	<?
		foreach ($subs as $sub){
	?>
	<span style="color:#ffffff;">+</span> <span class="fa fa-<?echo $sub['name'];?>" style="color:#ffffff;font-size:<?echo 24+ceil($sub['adjust']*12/32);?>px;"></span>	
	<?
		}
	}
	?>
</div>
</div>

<?}?>

<div style="clear:both;margin-bottom:40px;"></div>

<?foreach ($icons as $icon){
	$subs=$icon['subs'];
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa fa-<?echo $icon['name'];?>" style="position:relative;color:#72ADDE;font-size:<?echo 64+$icon['adjust'];?>px;">
	<?
	if (is_array($subs)){
		foreach ($subs as $sub){
	?>
	<span class="fa fa-<?echo $sub['name'];?>" style="color:#72ADDE;font-size:<?echo 64+$sub['adjust'];?>px;position:absolute;top:<?echo $sub['dy'];?>px;left:<?echo $sub['dx'];?>px;"></span>
	<?		
		}//foreach	
	}//subs
	?>
	</span>
	
	<?
	if (is_array($subs)){
	?>
	<span class="fa fa-<?echo $icon['name'];?>" style="position:relative;color:#72ADDE;font-size:<?echo 64+$icon['adjust'];?>px;">
	<?
		foreach ($subs as $sub){
	?>
	<span style="font-size:22px;vertical-align:middle;">+</span> <span class="fa fa-<?echo $sub['name'];?>" style="color:#72ADDE;font-size:<?echo 64+$sub['adjust'];?>px;"></span>
	<?
		}
	}
	?>		
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa fa-<?echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?echo 48+$icon['adjust'];?>px;">
	<?
	if (is_array($subs)){
		foreach ($subs as $sub){
	?>
	<span class="fa fa-<?echo $sub['name'];?>" style="color:#ffffff;font-size:<?echo 48+ceil($sub['adjust']*24/32);?>px;position:absolute;top:<?echo floor($sub['dy']*24/32);?>px;left:<?echo floor($sub['dx']*24/32);?>px;"></span>
	<?		
		}//foreach	
	}//subs
	?>
	</span>
	<?
	if (is_array($subs)){
	?>
	<span class="fa fa-<?echo $icon['name'];?>" style="position:relative;color:#ffffff;font-size:<?echo 48+$icon['adjust'];?>px;">
	<?
		foreach ($subs as $sub){
	?>
	<span style="color:#ffffff;font-size:20px;vertical-align:middle;">+</span> 
	<span class="fa fa-<?echo $sub['name'];?>" style="color:#ffffff;font-size:<?echo 48+ceil($sub['adjust']*24/32);?>px;"></span>
	<?
		}
	}
	?>	
</div>
</div>


<?}?>

<div style="clear:both;"></div>

</body>
</html>
