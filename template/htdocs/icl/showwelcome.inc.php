<?php
include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle">Welcome to Antradar Gyroscope</div>
	<?
		//lazy way to generate a starter screen, but better than nothing
		
		auto_welcome();
	
		if ($_SERVER['REMOTE_ADDR']=='127.0.0.1') showguide(); else echo '<div style="padding-bottom:100px;"></div>';
		
		showgyroscopeupdater();
	?>			

	
</div><!-- section -->
<?
}

function auto_welcome(){
	global $toolbaritems;
	?>
	<div class="section">
	
	<div class="col">
	<?
	foreach ($toolbaritems as $ti){
	if ($ti['type']=='break') {
	?>
	<!-- {{ -->
	</div>
	<div class="col">
	<!-- }} -->
	<?
		continue;	
	}
	if ($ti['type']=='custom'){
	?>
	<?echo $ti['desktop'];?>
	<?	
		continue;
	}
	
	$action='';
	if (is_numeric($ti['viewindex'])) $action='showview('.$ti['viewindex'].',null,1);';
	if ($ti['action']!='') $action.=$ti['action'];
	
?>	
	<div style="margin-bottom:10px;">
	<a onclick="<?echo $action;?>"><img style="vertical-align:middle;margin-right:5px;" class="<?echo $ti['icon'];?>" src="imgs/t.gif" width="32" height="32"> <span style="vertical-align:middle;"><?echo $ti['title'];?></span></a>
	</div>
	
<?}//foreach
?>

		<div class="clear"></div>
	
	</div>
	
	<div class="clear"></div>
</div>
<?
		
}


