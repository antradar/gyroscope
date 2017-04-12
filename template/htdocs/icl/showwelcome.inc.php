<?php
include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';


function showwelcome(){

?>
<div class="section">
	<div class="sectiontitle"><?tr('hometab_welcome');?></div>

<?/*
	<input id="mtest" onfocus="pickmonth(this,<?echo date('Y');?>);" placeholder="Month">
	<?makelookup('mtest',1);?>
	<br><br>
	<input id="dtest" onfocus="pickdate(this);" onkeyup="_pickdate(this);" placeholder="Date">
	<span id="dtest_val2"></span>
	<?makelookup('dtest',1);?>
	<br><br>
	<input id="test" onfocus="pickdatetime(this,{start:0,end:24});" onkeyup="_pickdatetime(this,{start:0,end:24});" placeholder="Date/Time">
	<span id="test_val2"></span>
	<?makelookup('test',1);?>
	<br><br>
	<input id="test2" onfocus="picktime(this,{start:0,end:24,y:2015,m:11,d:1});" placeholder="Time on 2015-11-1">
	<?makelookup('test2',1);?>
*/?>

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
	$user=userinfo();
	global $toolbaritems;
	?>
	<div class="section">

	<?
	foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']=='custom'){
	?>
	<?echo $ti['desktop'];?>
	<?	
		continue;
	}
	
	if (!isset($ti['icon'])||$ti['icon']=='') continue;
	
	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}
	
	$action="showview('".$modid."',null,1);";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	
?>	
	<div class="welcometile">
	<a onclick="<?echo $action;?>"><img style="vertical-align:middle;margin-right:5px;" class="<?echo $ti['icon'];?>-light" src="imgs/t.gif"> <span style="vertical-align:middle;"><?echo $ti['title'];?></span></a>
	</div>
	
<?}//foreach
?>

	
	<div class="clear"></div>
</div>
<?
		
}


