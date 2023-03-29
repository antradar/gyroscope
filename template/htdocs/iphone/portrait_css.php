<?php
header('Content-Type: text/css');
?>
body{background:#ffffff;}
#tooltitle{height:25px;background:transparent url(tbbg.png) repeat top left;border-bottom:solid 1px #6f6e6e;width:320px;text-shadow:0 1px 0 #000000;}
#lvviews{height:auto;overflow:visible;}
#tabviews{overflow:visible;height:auto;}
#content{height:auto;overflow:visible;}

.listitem{line-height:1.5em;border-bottom:solid 1px #efefef;padding:10px 0;font-size:16px;}
#lvviews .listitem{color:#696F7F;background:transparent url(rec_hd.gif) no-repeat center right;-webkit-background-size:17px 13px;background-size:17px 13px;}
#lvviews .listitem a{display:block;padding-right:15px;}

.listlookup{width:250px;margin-bottom:15px;}

.col{float:none;margin:0 auto;margin-bottom:20px;width:auto;}

.inplong, .inp, .inpmed{padding:3px;width:95%;}
.inpshort{width:40%;max-width:80px;}

#mmastersearch{height:50px;}
.mastersearchshell{padding-top:10px;}
#msearchcloser{top:15px;}

#mainsearchview_{top:45px;}

.savebar_content{padding:10px 5px 10px 5px;text-align:center;}

#homeicon{display:block;}
.qnav_{position:fixed;left:0;top:0px;height:100%;overflow:hidden;}
.qnav{padding-top:160px;}

#lkv_origin{display:none;}
#lkv.dismounted{z-index:1000;}

#toolicons a img{margin:5px 3px;}
#toollist{height:45px;}
#backlist{top:50px;}
#toolbg{height:50px;}
#pusher{height:50px;}
#backlistshadow{height:43px;}

<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1){
?>

	body{background:#0D1117;}
	.listitem,.sortlistitem{border-bottom:solid 1px #21262D;}
	#tooltitle{background:#131F2F;border-bottom:none;border-top:solid 1px #3690D9;border-bottom:solid 1px #3690D9;}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}


