<?php

//once a real dashboard is implemented, this link can simply point to the actual dash
//e.g. include 'dash_real.inc.php';

function dash_default(){
	//then call: dash_real(); return;
	$title=SGET('title');
	if ($title=='') $title='Default Dashboard';
?>
<div class="section">
	<div class="sectiontitle"><?php echo htmlspecialchars($title);?></div>
</div>
<?php	
}