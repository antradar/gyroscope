<?php
include 'lb.php';
include 'auth.php';
include 'sql.php';

// $readonlypath='https://exodus.your-domain.com/'; //remember to modify xss.php on the exodus server
if (isset($SQL_READONLY)&&$SQL_READONLY) $readonlypath='';

$notetypes=array(
	'plain'=>'Simple Text',
	'user'=>'User',
);

$mode=isset($_GET['mode'])?$_GET['mode']:'';
if ($mode!='embed'){
?>
<!doctype html>
<html>
<head>
	<title>Notes - <?php echo GYROSCOPE_PROJECT;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="theme-color" content="#454242" />
	<meta id="viewport" name="viewport" content="width=device-width" />
	<link href="gsnotes.css" type="text/css" rel="stylesheet" />
	<style>
body{padding:0;margin:0;}
.logo_light{display:block;}
.logo_dark{display:none;}

@media (prefers-color-scheme:dark) {
	body{background:#21262D;color:#C9D1D9;}
	.logo_light{display:none;}
	.logo_dark{display:block;}
	
	input,textarea{background:#0D1117;color:#C2C3C5;border:solid 1px #6B7247;border-radius:3px;padding:2px 5px;line-height:20px;}
	
	.formlabel{color:#6B7247;}
	
	#gsnotes_header{border-bottom:solid 1px #21262D;}
	#gsnotes_online{background:#0E2717;border-bottom:solid 1px #105F1A;color:#18E022;}
	
	a, a:hover, a:link, a:visited{text-decoration:none;color:#68A7EA;transition:color 200ms;}
	a:hover{color:#294B70;}
	
	button{margin-top:5px;background:#187CA6;color:#F1DECE;cursor:pointer;border:solid 1px #388BFD;padding:5px 10px;}
	button:hover{background:#125B7A;}
}
	
	</style>
</head>
<body>

<div id="gsnotes_header">
	<img src="imgs/logo.png" id="gsnotes_headerlogo" class="logo_light">
	<img src="imgs/dlogo.png" id="gsnotes_headerlogo" class="logo_dark">
</div>

<?php }//embed
?>
<div id="gsnotes_unsupported" style="display:none;">
	Your browser does not support offline storage. Sorry =(
</div>
<div id="gsnotes_online" style="display:none;">
	Good news! The network is working again! &nbsp; &nbsp;
	<?php if ($mode=='embed'){?>
		<a class="labelbutton" onclick="closefs();">back to work</a>
	<?php } else {?>
		<a class="hovlink" href="./">continue to app</a>
	<?php }?>
	
	<?php if ((!isset($SQL_READONLY)||!$SQL_READONLY)&&isset($readonlypath)&&$readonlypath!=''){?>
	<div style="padding-top:5px;">
	If the server is unreachable, try the <a href="<?php echo $readonlypath;?>" target=_blank>read-only mode</a>
	</div>
	<?php }?>
			
</div>
<div id="gsnotes_main">

	<div id="gsnotes_list">
	
	</div>
	<div id="gsnotes_adder">
	Add a:	
		<?php foreach ($notetypes as $k=>$v){?>
			&nbsp; <nobr><u><a class="hovlink" href=# onclick="gsnotes_setnotetype('<?php echo $k;?>');return false;"><?php echo $v;?></a></u></nobr> &nbsp;
		<?php }?>
	</div>
	<div id="gsnotes_form">
	
	</div>
	


	&nbsp;
	
</div><!-- gsnotes_main -->

<?php
if ($mode!='embed'){?>
<script src="nano.js"></script>
<script>


function onlinestatuschanged(){
	if (navigator.onLine){
		if (gid('gsnotes_online')) gid('gsnotes_online').style.display='block';
	} else {
		if (gid('gsnotes_online')) gid('gsnotes_online').style.display='none';
	}
}

window.addEventListener('offline',onlinestatuschanged);
window.addEventListener('online',onlinestatuschanged);
</script>
<script src="gsnotes.js"></script>
<script src="validators.js"></script>
<script>
gsnotes_init();
setTimeout(onlinestatuschanged,300);
</script>
</body>
</html>
<?php
}//embed
