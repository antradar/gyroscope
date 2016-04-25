<?php
include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle"><?tr('hometab_welcome');?></div>
	
<?
/*
	<input id="test" onfocus="pickdatetime(this,{mini:1,start:0,end:24});" onkeyup="_pickdatetime(this,{mini:1,start:0,end:24});">
	<span id="test_val2"></span>
	<?makelookup('test',1);?>
	<br><br>
	<input id="test2" onfocus="picktime(this,{mini:1,start:0,end:24,y:2015,m:11,d:1});" onkeyup="picktime(this,{start:0,end:24,y:2015,m:11,d:1});">
	<?makelookup('test2',1);?>
*/
?>
	<?
		//lazy way to generate a starter screen, but better than nothing
		
		auto_welcome();	
		showgyroscopeupdater();
		
		if ($_SERVER['REMOTE_ADDR']=='127.0.0.1') showguide(); else echo '<div style="padding-bottom:100px;"></div>';
		
	?>			

	
</div><!-- section -->
<?
}

function auto_welcome(){
	global $toolbaritems;
	?>
	<div class="section">
	
	<?
	foreach ($toolbaritems as $ti){
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
	<div style="margin-bottom:20px;" class="welcometile">
	<a onclick="<?echo $action;?>"><img style="vertical-align:middle;margin-right:5px;" class="<?echo $ti['icon'];?>-light" src="imgs/t.gif" width="32" height="32"> <span style="vertical-align:middle;"><?echo $ti['title'];?></span></a>
	</div>
	
<?}//foreach
?>

	
	<div class="clear"></div>
</div>
<?
		
}


