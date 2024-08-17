<?php
header('Content-Type: text/css');
?>
body{background:#f2f2f2;}
#tooltitle{height:24px;background:#f2f2f2 url(msprite.png) no-repeat 0 0;width:150px;text-shadow:none;border-bottom:none;}

#lvviews{height:210px;overflow:auto;}
#tabviews{overflow:auto;}
#content{overflow-x:auto;}

.listitem{line-height:1.5em;border-bottom:solid 1px #dedede;padding:5px 0;font-size:13px;}
#lvviews .listitem{color:#000000;background-image:none;}
#lvviews .listitem a{display:inline;}

.listlookup{width:120px;margin-bottom:10px;}

.col{float:left;width:40%;margin-right:8%;}

.inplong{width:95%;}
.inp{width:90%;}
.inpmed{width:80%;}
.inpshort{width:50%;}
#toolicons a img{margin:0;}
#toollist{height:35px;}
#backlist{top:40px;}
#toolbg{height:40px;}
#pusher{height:40px;}
#backlistshadow{height:43px;}

#mmastersearch{height:40px;}
.mastersearchshell{padding-top:5px;}
#msearchcloser{top:10px;}

#mainsearchview_{top:40px;}

.savebar_content{padding:10px 235px 10px 5px;text-align:center;}

#homeicon{display:none;}

#lkv.dismounted{z-index:auto;}

.qnav_{position:absolute;top:0px;left:230px;height:100%;overflow:hidden;}
.qnav{padding-top:145px;}

@media screen and (max-width:1100px){	
	.col{float:none;width:auto;margin:0 auto;margin-bottom:10px;}
	.mceToolbar .mceSeparator, .mceToolbar .mce_link, .mceToolbar .mce_unlink{display:none !important;}
}

<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1){
?>

	body,#tooltitle{background:#353535;}
	#tabtitles{background:#353535;}
	.listitem,.sortlistitem{border-bottom:solid 1px #21262D;}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}


	