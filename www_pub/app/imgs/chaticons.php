<html>
<head>
	<title>Chatbot Icons</title>
	<link href="https://use.fontawesome.com/releases/v6.1.1/css/all.css" rel="stylesheet">
</head>
<body>
<div style="padding:20px 0;">
	<a href="https://fontawesome.com/icons" target=_blank>Find icons &raquo;</a>
</div>


<?php

$facecolor='#72ADDE';
$darkback='#ffffff';
$lightback='#ffffff';
$backbg='#0D1117';//'#2D3239';//'#0D1117';//'#CCCCCC';//'#0D1117';//'#2D3239';//'#CCCCCC';

$icons=array(
	array('name'=>'book','adjust'=>0,'dx'=>-2,'dy'=>-4),
	array('name'=>'file-lines','adjust'=>0,'dx'=>1,'dy'=>-4),
	array('name'=>'user','adjust'=>-4,'dx'=>-1,'dy'=>-3),
	array('name'=>'magnifying-glass','adjust'=>0,'dx'=>-4,'dy'=>-3),

	array('name'=>'brush','adjust'=>-4,'dx'=>0,'dy'=>-1),
	array('name'=>'paint-roller','adjust'=>-2,'dx'=>-1,'dy'=>0),
		
	array('name'=>'calendar-alt','adjust'=>0,'dx'=>-1,'dy'=>-4,'style'=>'s'),
	
	array('name'=>'arrows-split-up-and-left','adjust'=>0,'dx'=>-5,'dy'=>-3),
	
	array('name'=>'life-ring','adjust'=>0,'dx'=>-3,'dy'=>-3),
	array('name'=>'chart-bar','adjust'=>0,'dx'=>-2,'dy'=>-3),
	array('name'=>'temperature-full','adjust'=>0,'dx'=>0,'dy'=>-3),
	array('name'=>'clipboard-list','adjust'=>0,'dx'=>0,'dy'=>-4),
	array('name'=>'file-contract','adjust'=>0,'dx'=>0,'dy'=>-3),
	array('name'=>'folder-open','adjust'=>0,'dx'=>-2,'dy'=>-3),

	array('name'=>'bell','adjust'=>0,'style'=>'r','dx'=>-2,'dy'=>-3),
	array('name'=>'comment-dots','adjust'=>0,'dx'=>-3,'dy'=>-3,'style'=>'r'),
	
	array('name'=>'clock','adjust'=>0,'style'=>'r','dx'=>-3,'dy'=>-3),
			
	array('name'=>'flask','adjust'=>0,'dx'=>-2,'dy'=>-3),
	
	array('name'=>'tags','adjust'=>0,'dx'=>-3,'dy'=>-2),
	array('name'=>'shopping-basket','adjust'=>0,'dx'=>-4,'dy'=>-3),
	array('name'=>'gift','adjust'=>0,'dx'=>-3,'dy'=>-3),
	array('name'=>'microchip','adjust'=>0,'dx'=>-3,'dy'=>-3),
	array('name'=>'database','adjust'=>0,'dx'=>-1,'dy'=>-4),
	array('name'=>'briefcase','adjust'=>0,'dx'=>-3,'dy'=>-4),
	array('name'=>'shield','adjust'=>0,'dx'=>-3,'dy'=>-2),
	array('name'=>'utensils','adjust'=>0,'dx'=>-2,'dy'=>-2),
	array('name'=>'elementor','adjust'=>0,'dx'=>-3,'dy'=>-3,'style'=>'b'),
	array('name'=>'comment-alt','adjust'=>0,'dx'=>-3,'dy'=>-1),
	array('name'=>'map-marker','adjust'=>0,'dx'=>0,'dy'=>-2)
);
?>


<div style="margin-bottom:10px;">
Chatbot Icons: (40x40)
</div>

<div class="clear"></div>
<?php

foreach ($icons as $icon){
	$subs=isset($icon['subs'])?$icon['subs']:null;
	$style=isset($icon['style'])?$icon['style']:'';
	if ($style=='') $style='s';
	
	$dx=$icon['dx']??0;
	$dy=$icon['dy']??0;
?>

<div style="position:relative;padding:10px;float:left;width:40px;height:40px;box-sizing:border-box;overflow:hidden;text-align:center;border-radius:100%;overflow:hidden;">
	<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="transform:translateX(<?php echo $dx;?>px) translateY(<?php echo $dy;?>px);color:<?php echo $facecolor;?>;font-size:<?php echo 26+round($icon['adjust']/4);?>px;margin-right:10px;position:relative;"></span>
</div>

<div style="padding:10px;background:<?php echo $backbg;?>;float:left;width:40px;height:40px;box-sizing:border-box;overflow:hidden;text-align:center;border-radius:100%;overflow:hidden;">
		<span class="fa<?php echo $style;?> fa-<?php echo $icon['name'];?>" style="transform:translateX(<?php echo $dx;?>px) translateY(<?php echo $dy;?>px);color:<?php echo $darkback;?>;font-size:<?php echo 26+round($icon['adjust']/4);?>px;"></span>
</div>


<?php }?>


<div style="clear:both;"></div>

</body>
</html>
