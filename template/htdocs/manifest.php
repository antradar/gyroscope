<?php
include 'lb.php';
include 'auth.php';
header('Cache-Control: no-store');

?>
{
	"name":"<?php echo GYROSCOPE_PROJECT;?>",
	"short_name":"<?php echo GYROSCOPE_PROJECT;?>",
	"icons":[
	{
		"src":"appicons/192x192.png",
		"type":"image/png",
		"sizes":"192x192"
	},
	{
	"src": "appicons/512x512.png",
	"type": "image/png",
	"sizes": "512x512"
	}
	],
	"start_url":"login.php",
	"background_color":"#ffffff",
	"display":"standalone",
	"theme_color":"#454242"
}