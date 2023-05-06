<?php
header('Content-Type: text/css');
?>
.activetab .tabclose,
.dulltab .tabclose,
.activetab, .dulltab,
.activetab .noclose,
.dulltab .noclose,
#lkvtitle, #lkvx, #lkv.dismounted #lkvx,
.img-calel, .img-caler, 
.img-help, .img-print{background-image:url(msprite_hd.png);background-size:399px 154px;}

/*
.mediaimg-mg{border-color:#cccccc;background:#f2f2f2 url(../imgs/sprite.png) no-repeat 100% -330px;padding-left:5px;padding-right:25px;margin-bottom:8px;height:32px;line-height:30px;vertical-align:middle;width:50%;}
*/

/*
sync background size with toolbar_hd_css.php
*/

.sectiontitle a .edithover{background-image:url(../imgs/toolbar_hd.gif);background-size:768px 64px;}

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

	.sectiontitle a .edithover{filter:invert(1);}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
