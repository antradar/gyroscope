<?php

function showhelp(){
	$topic=GETSTR('topic');
	$title=GETSTR('title');
?>
<div class="section">
	<div class="sectiontitle" style="margin-bottom:20px;"><?echo $title;?></div>
	<?
	$fn="help/$topic.help.php";
	if (file_exists($fn)) include $fn; else echo "Help file <u>$fn</u> is missing";
	?>
</div>
<?
}