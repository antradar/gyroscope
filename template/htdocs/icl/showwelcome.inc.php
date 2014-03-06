<?php
include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle">Welcome to Antradar Gyroscope</div>
	<?
		showguide();
		showgyroscopeupdater();
	?>			

	
</div><!-- section -->
<?
}
