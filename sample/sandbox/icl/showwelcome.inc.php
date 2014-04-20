<?php
include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle">Gyroscope Sandbox</div>
	
	<p style="width:80%;line-height:20px;">
	Welcome to the playground of Antradar Gyroscope <?echo GYROSCOPE_VERSION;?>.<br><br>
	You may use the Code Generator to rapidly build prototypes against
	predefined data tables, such as Landlord-Property-Lease-Tenant or Film-Actor, which is bundled as a full demo in the Gyroscope package.
	<br><br>
	When you are done, simply click the <a style="background-color:#ab0200;" class="labelbutton" onclick="resetsandbox();">Reset All</a> button and start again.
	</p>

	<?		
		auto_welcome();	
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


