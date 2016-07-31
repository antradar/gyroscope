<html>
<head>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
</head>
<body>
<div style="padding:20px 0;">
	<a href="http://fortawesome.github.io/Font-Awesome/icons/" target=_blank>Find icons &raquo;</a>
</div>
<?
$icons=array(array('name'=>'user','adjust'=>0),
	array('name'=>'stethoscope','adjust'=>0),
	array('name'=>'user-md','adjust'=>0),
	array('name'=>'ambulance','adjust'=>-2),
	array('name'=>'flask','adjust'=>0),
	array('name'=>'car','adjust'=>-4),
	array('name'=>'tags','adjust'=>-4),
	array('name'=>'map-marker','adjust'=>0)
);

foreach ($icons as $icon){
?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#72ADDE;font-size:<?echo 32+$icon['adjust'];?>px;margin-right:10px;"></span>	
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#ffffff;font-size:<?echo 24+$icon['adjust'];?>px;margin-right:10px;"></span>
</div>
</div>

<?}?>

<div style="clear:both;margin-bottom:40px;"></div>

<?foreach ($icons as $icon){?>

<div style="position:relative;padding:10px;float:left;">
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#72ADDE;font-size:<?echo 64+$icon['adjust']*2;?>px;"></span>
</div>

<div style="padding:10px;background:#3C3839;float:left;">
<div style="position:relative;">	
	<span class="fa fa-<?echo $icon['name'];?>" style="color:#ffffff;font-size:<?echo 48+$icon['adjust']*2;?>px;"></span>
</div>
</div>


<?}?>

<div style="clear:both;"></div>

</body>
</html>
