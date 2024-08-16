<?php

function listwelcome(){
	$user=userinfo();
	global $toolbaritems;
	
	?>
	<div style="height:20px;"></div>
	<?php
	foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']=='custom'){
	?>
	<?php echo $ti['desktop'];?>
	<?php	
		continue;
	}
	
	if (!isset($ti['icon'])||$ti['icon']=='') continue;
	
	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}

	$binmode='null';
	if (isset($ti['bingo'])&&$ti['bingo']==1) $binmode=1;	

	$action="showview('".$modid."',1,1,null,null,".$binmode.");";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	
?>	
	<div class="listmenuitem">
	<a onclick="<?php echo $action;?>"><img alt="<?php echo $ti['title'];?>" style="vertical-align:middle;margin-right:5px;" class="<?php echo $ti['icon'];?>-light" src="imgs/t.gif"> <span style="vertical-align:middle;"><?php echo $ti['title'];?></span></a>
	</div>
	
<?php }//foreach

	
}
