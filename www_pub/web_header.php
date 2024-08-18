<?php

function show_web_header($egress='', $opts=array()){
	$pagekey=$opts['pagekey']??'';
	$title=isset($opts['page_title'])?$opts['page_title']:'Gyroscope Home';
?>
<!doctype html>
<html>
<head>
	<title><?php echo $title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "viewport" content = "width=device-width" />
	<meta name="theme-color" content="#9FA3A7" />	
	<!-- link rel="shortcut icon" href="favicon.ico" type="image/x-icon" / -->
	<link rel="stylesheet" href="<?php echo $egress;?>style.css" type="text/css" />
	<?php if ($pagekey=='login'){?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">	
	<?php }?>
</head>
<body>
<div id="mmenu">
	<div id="mmenu_">
		<a class="mmenuitem<?php if ($pagekey=='home') echo ' current';?>" href="<?php echo $egress;?>index.php">Home</a>
		<a class="mmenuitem<?php if ($pagekey=='about') echo ' current';?>" href="<?php echo $egress;?>about.php">About</a>
		<a class="mmenuitem<?php if ($pagekey=='login') echo ' current';?>" href="<?php echo $egress;?>app/">Log In</a>
	</div>
</div><!-- mmenu -->
<div id="page">
	<div id="header">
		<a href="<?php echo $egress;?>index.php"><img id="weblogo_light" src="<?php echo $egress;?>images/web_logo.png"><img id="weblogo_dark" src="<?php echo $egress;?>images/web_logo_dark.png"></a>
	
		<div id="menu">
			<a class="menuitem<?php if ($pagekey=='home') echo ' current';?>" href="<?php echo $egress;?>index.php">Home</a>
			<a class="menuitem<?php if ($pagekey=='about') echo ' current';?>" href="<?php echo $egress;?>about.php">About</a>
			<a class="menuitem<?php if ($pagekey=='login') echo ' current';?>" href="<?php echo $egress;?>app/">Log In</a>
			<div class="clear"></div>
		</div>
		<a id="mtrigger" onclick="showmmenu();"><img src="<?php echo $egress;?>images/mtrigger.png"></a>		
	</div><!-- header -->

<?php	
}

