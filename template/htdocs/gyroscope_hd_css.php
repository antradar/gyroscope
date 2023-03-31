<?php
header('Content-Type: text/css');
?>
#tooltitle, #lkvtitle, #lkvx, #lefticons, #statusinfo,
.activetab, .dulltab,
.activetab .tabclose, .activetab .closeall, .activetab .noclose,
.dulltab .noclose, .activetab, .dulltab,
.dulltab .tabclose, .img-close,
.img-calel, .img-caler, .img-mg, .mediaimg-mg,
#leftviewcloser img,
.img-help,
.img-print{background-image:url(imgs/sprite_hd.png);background-size:399px 370px;}


<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1){
?>
	
	#tooltitle, #lkvtitle, #lkvx, #lefticons, #statusinfo,
	.activetab, .dulltab,
	.activetab .tabclose, .activetab .closeall, .activetab .noclose,
	.dulltab .noclose, .activetab, .dulltab,
	.dulltab .tabclose, .img-close,
	.img-calel, .img-caler, .img-mg, .mediaimg-mg,
	.img-help,
	.img-print{background-image:url(imgs/dsprite_hd.png);}
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
