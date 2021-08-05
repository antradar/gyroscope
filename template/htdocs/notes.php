<?php
include 'auth.php';
include 'sql.php';

// $readonlypath='https://exodus.your-domain.com/'; //remember to modify xss.php on the exodus server
if ($SQL_READONLY) $readonlypath='';

$notetypes=array(
	'plain'=>'Simple Text',
	'user'=>'User',
);

$mode=$_GET['mode'];
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
	</style>
</head>
<body>

<div id="gsnotes_header">
	<img src="imgs/logo.png" id="gsnotes_headerlogo">
</div>

<?php }//embed
?>
<div id="gsnotes_unsupported" style="display:none;">
	Your browser does not support offline storage. Sorry =(
</div>
<div id="gsnotes_online" style="display:none;">
	Good news! The network is working again! 
	<?php if ($mode=='embed'){?>
		<a class="labelbutton" onclick="closefs();">back to work</a>
	<?php } else {?>
		<a href="./">continue to app</a>
	<?php }?>
	
	<?php if (!$SQL_READONLY&&$readonlypath!=''){?>
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
			&nbsp; <nobr><u><a href=# onclick="gsnotes_setnotetype('<?php echo $k;?>');return false;"><?php echo $v;?></a></u></nobr> &nbsp;
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
