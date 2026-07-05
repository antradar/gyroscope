<?php
header('Content-Type: text/css');
include '../uiconfig.php';
?>
body{background:#ffffff;}
#tooltitle{height:25px;background:transparent url(tbbg.png) repeat top left;border-bottom:solid 1px #6f6e6e;width:320px;text-shadow:0 1px 0 #000000;}
#lvviews{height:auto;overflow:visible;}
#tabviews{overflow-y:auto;height:auto;}
.tab-scroll-pad{height:40vh;}
#content{height:auto;overflow:visible;}

#gsreplayicon{margin-top:10px !important;}

.listitem{line-height:1.5em;border-bottom:solid 1px #efefef;padding:10px 0;font-size:16px;}
#lvviews .listitem{color:#696F7F;background:transparent url(rec_hd.gif) no-repeat center right;-webkit-background-size:17px 13px;background-size:17px 13px;}
#lvviews .listitem a{display:block;padding-right:15px;}

.listlookup{width:250px;margin-bottom:15px;}

.col{float:none;margin:0 auto;margin-bottom:20px;width:auto;}

.inplong, .inp, .inpmed{padding:3px;width:95%;}
.inpshort{width:40%;max-width:80px;}

#toolbaractions{position:absolute;top:0;right:0;height:50px;}
#adminlogout{position:relative !important;top:10px !important;right:auto !important;display:inline-block !important;vertical-align:middle !important;margin:0 5px !important;}
<?php if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['enable_master_search']){?>
#mmastersearch{position:relative !important;top:auto !important;left:auto !important;right:auto !important;width:40px !important;height:50px !important;display:inline-block !important;vertical-align:middle !important;cursor:pointer !important;overflow:visible !important;background:transparent !important;}
#msearchicon{display:block;width:28px;height:28px;cursor:pointer;background:transparent url(../imgs/mg_dark.png) no-repeat center center;background-size:20px 20px;opacity:1;margin:11px auto 0 auto;}
#msearchicon:hover{opacity:1;}
.mastersearchshell{padding:0 !important;position:absolute;top:10px;right:5px;height:30px;width:0;overflow:hidden;transition:width 250ms;border-radius:6px;}
#mastersearch{font-size:15px;border:none;background:transparent;color:#000;outline:none;line-height:28px;height:30px;box-sizing:border-box;width:100%;padding-left:36px;}
#mastersearch.expanded{background:#fff url(../imgs/mg_light.png) no-repeat 6px 50%;border:solid 1px #ccc;color:#000;border-radius:6px;}
#msearchcloser{position:absolute;top:5px;right:5px;left:auto !important;width:20px;height:20px;cursor:pointer;z-index:12;display:none;}
#mainsearchview_{top:45px !important;left:0 !important;right:0 !important;width:auto !important;max-height:300px !important;}
#adminlogout{top:auto !important;}
#toolicons{pointer-events:none;z-index:2010 !important;}
#toolbaractions{pointer-events:auto;}
#mainmenu{pointer-events:auto;}
#bookmarkview{pointer-events:auto;}
#toollist{pointer-events:auto;}
#toollistcontent{pointer-events:auto;}
#mainuserprofile{display:none !important;}
<?php } else { ?>
#mmastersearch{display:none !important;}
.mastersearchshell{width:0;overflow:hidden;}
#msearchcloser{display:none;}
#msearchicon{display:none;}
#mainuserprofile{display:none !important;}
<?php } ?>

#mainsearchview_{top:45px;}

.savebar_content{padding:10px 5px;text-align:center;}
.changebar_content{padding:0 5px 5px 5px;text-align:center;}
.changebar_view,.savebar_view{left:0;width:100%;}

#homeicon{display:block;}
#applogo{display:none;}
#toollistcontent{padding-left:0;}
#leftview{position:static!important;float:left!important;margin-left:0!important;}
#content{margin-left:0!important;}
.qnav_{position:fixed;left:0;top:0px;height:100%;overflow:hidden;}
.qnav{padding-top:<?php if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['toolbar_position']=='bottom') echo '60'; else echo '80';?>px;}
body.tabafloat_portrait .qnav{padding-top:35px!important;}

#lkv_origin{display:none;}
#lkv.dismounted{z-index:1000;}

#toolicons a img{margin:5px 3px;}
#toollist{height:45px;}
#mainmenu{display:none;}
#backlist{
<?php if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['toolbar_position']=='bottom'){?>
	top:0;
<?php } else {?>	
	top:50px;
<?php } ?>
}
#toolbg{height:50px;}
#pusher{height:50px;}
#backlistshadow{height:43px;}
#mvptr{display:none;}

#cornertab{z-index:2000 !important;}
#cornerbutton{z-index:2000 !important;}

body.tabafloat_portrait #tabtitles{display:none;}
body.tabafloat_portrait #tabviews{padding-top:0;}
body.tabafloat_portrait #backlist{display:none!important;}
body.tabafloat_portrait #backlistshadow{display:none!important;}
body.tabafloat_portrait #pusher{height:0!important;}
body.tabafloat_portrait #toolbg{display:none;}
body.tabafloat_portrait #tooltitle{display:none;}
body.tabafloat_portrait #toollist{display:none;}
body.tabafloat_portrait #toolicons{display:none!important;}
body.tabafloat_portrait #applogo{display:none!important;}
body.tabafloat_portrait .qnav_{left:0!important;}

<?php if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['singletab']){?>
.tabclose_btn{display:none;}
<?php } ?>

<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1||$dark==3){
?>

	body{background:#0D1117;}
	.listitem,.sortlistitem{border-bottom:solid 1px #21262D;}
	#tooltitle{background:#131F2F;border-bottom:none;border-top:solid 1px #3690D9;border-bottom:solid 1px #3690D9;}
	#msearchicon{opacity:1;}
	#mastersearch.expanded{background:#2d333b url(../imgs/mg_dark.png) no-repeat 6px 50%;border-color:#444;color:#c9d1d9;}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}


