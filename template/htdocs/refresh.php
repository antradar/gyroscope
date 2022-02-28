<?php

include 'lb.php';
include 'lang.php';
include 'auth.php';

if (isset($usehttps)&&$usehttps) include 'https.php'; 

?>
<!doctype html>
<html>
<head>
	<title><?php echo GYROSCOPE_PROJECT;?> - Refresh</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name = "viewport" content = "width=device-width, user-scalable=no" />
	<meta name="theme-color" content="#9FA3A7" />	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	
	<?php include 'appicon.php';?>
	<link rel="manifest" href="manifest.php?hb=<?php echo time();?>">
<style>
<?php
$framecolor='rgba(200,200,200,0.4)';
if ($SQL_READONLY) $framecolor='rgba(255,200,100,0.4)';
?>
body{padding:0;margin:0;background:transparent url(imgs/bgtile.png) repeat;font-size:13px;font-family:arial,sans-serif;text-align:center;<?php if ($dict_dir=='rtl') echo 'direction:rtl;';?>}
#loginbox__{width:320px;margin:0 auto;background-color:<?php echo $framecolor;?>;margin-top:100px;border-radius:4px;}
#loginbox_{padding:10px;}
#loginbox{background-color:#FFFFFF;text-align:<?php if ($dict_dir=='rtl') echo 'right'; else echo 'left';?>;}

#cacheres{
	line-height:1.5em;	
}

#cacheres em{color:#848cf7;}

@media screen and (max-width:400px){
	#loginbox__,.powered{width:90%;}
	#loginbox__{margin-top:50px;}
}


@media (prefers-color-scheme:dark) {
	body{background-image:url(imgs/dbgtile.png);}
	#loginbox{background: #21262D;color:#C9D1D9;}
	#logo_light{display:none;}
	#logo_dark{display:block;}
}



</style>
</head>
<body>
<div id="loginbox__"><div id="loginbox_">
<div id="loginbox">
	<div style="padding:20px;margin:0;padding-top:10px;">
	<img src="imgs/logo.png" style="margin:10px 0;width:100%;" alt="Gyroscope Logo" id="logo_light">
	<img src="imgs/dlogo.png" style="margin:10px 0;width:100%;" id="logo_dark">
	
	<div id="cacheres">
	
	</div>
	
	</div>
</div>
</div></div>
		

<script>
if (window.caches){
caches.open('gyroscope').then(function(cache) { //vendor portal specific
	cache.keys().then(function(keys) {		
		keys.forEach(function(request, index, array){
			cache.delete(request);
			var parts=request.url.split('/');
			var fn=parts[parts.length-1];
			document.getElementById('cacheres').innerHTML+='cleared <em>'+fn+'</em><br>';
		});
			
		return cache.addAll([
		'imgs/logo.png',
		'imgs/dlogo.png',
		'nano.js',
		'notes.php',
		'notes.php?mode=embed',
		'gsnotes.css',
		'gsnotes.js',
		'validators.js'
		]);	
	});	
	
});
} else {
	document.getElementById('cacheres').innerHTML='App Cache empty - nothing to clear';
}
</script>

</body>
</html>
