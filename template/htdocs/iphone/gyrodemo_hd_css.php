<?php
header('Content-Type: text/css');
?>
.activetab .tabclose,
.dulltab .tabclose,
.activetab, .dulltab,
.activetab .noclose,
.dulltab .noclose,
#lkvtitle, #lkvx,
.img-calel, .img-caler, 
.img-help, .img-print{background-image:url(msprite_hd.png);background-size:399px 154px;}

/*
.mediaimg-mg{border-color:#cccccc;background:#f2f2f2 url(../imgs/sprite.png) no-repeat 100% -330px;padding-left:5px;padding-right:25px;margin-bottom:8px;height:32px;line-height:30px;vertical-align:middle;width:50%;}
*/


<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1){
?>

	.activetab .tabclose,
	.dulltab .tabclose,
	.activetab, .dulltab,
	.activetab .noclose,
	.dulltab .noclose,
	#lkvtitle, #lkvx,
	.img-calel, .img-caler, 
	.img-help, .img-print{background-image:url(dmsprite_hd.png);}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
