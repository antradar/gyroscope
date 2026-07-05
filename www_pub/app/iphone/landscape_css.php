<?php
header('Content-Type: text/css');
include '../uiconfig.php';
?>
body{background:#f2f2f2;}
#tooltitle{height:24px;background:#f2f2f2 url(msprite.png) no-repeat 0 0;width:150px;text-shadow:none;border-bottom:none;}

#gsreplayicon{margin-top:5px !important;}


#lvviews{overflow:auto;padding-top:28px;}
#tabviews{overflow-y:auto;overflow-x:hidden;padding-top:26px;}
.tab-scroll-pad{height:75vh;}
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

<?php
if (isset($uiconfig['mobile'])&&$uiconfig['mobile']['toolbar_position']=='bottom'){
?>
#toolicons{bottom:0 !important;}
#tooltitle{top:10px;}
#cornertab{bottom:50px !important;}
#cornerbutton{bottom:50px !important;}
<?php } ?>
#toolicons a img{margin:0;}
#toollist{height:35px;}
#backlist{top:40px;}
#toolbg{height:40px;}
#pusher{height:40px;}
#backlistshadow{height:43px;}

#toolbaractions{position:absolute;top:0;right:0;height:40px;}
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['enable_master_search']){?>
#mmastersearch{position:relative !important;top:auto !important;left:auto !important;right:auto !important;width:40px !important;height:40px !important;display:inline-block !important;vertical-align:middle !important;cursor:pointer !important;z-index:10 !important;overflow:visible !important;background:transparent !important;}
#msearchicon{display:block;width:40px;height:40px;cursor:pointer;background:transparent url(../imgs/mg_dark.png) no-repeat center center;background-size:20px 20px;opacity:1;}
#msearchicon:hover{opacity:1;}
.mastersearchshell{padding:0 !important;position:absolute;top:5px;right:5px;height:30px;width:0;overflow:hidden;transition:width 250ms;border-radius:6px;}
#mastersearch{font-size:14px;border:none;background:transparent;color:#000;outline:none;line-height:28px;height:30px;box-sizing:border-box;width:100%;padding-left:36px;}
#mastersearch.expanded{background:#fff url(../imgs/mg_light.png) no-repeat 6px 50%;border:solid 1px #ccc;color:#000;border-radius:6px;}
#msearchcloser{position:absolute;top:5px;right:5px;left:auto !important;width:20px;height:20px;cursor:pointer;z-index:12;display:none;}
#mainsearchview_{position:fixed !important;top:40px !important;max-height:300px !important;z-index:2020 !important;}
#adminlogout{display:inline-block !important;vertical-align:middle !important;position:relative !important;margin:0 5px !important;top:auto !important;padding-top:1px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{position:relative !important;top:auto !important;margin:0 5px !important;vertical-align:middle !important;}
#toolicons{pointer-events:none;z-index:2010 !important;}
#toolbaractions{pointer-events:auto;}
#applogo{pointer-events:auto;}
#mainmenu{pointer-events:auto;}
#bookmarkview{pointer-events:auto;}
#toollist{pointer-events:auto;}
#toollistcontent{pointer-events:auto;}
#mainuserprofile.admin-user{width:16px;height:16px;}
<?php } else { ?>
#mmastersearch{display:none !important;}
#adminlogout{display:inline-block !important;vertical-align:middle !important;margin:0 10px 0 5px !important;position:relative !important;top:13px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{display:inline-block;position:relative;top:12px;margin-right:5px;vertical-align:middle;}
#toolbaractions.hasprofile #adminlogout,#toolbaractions.hasprofile .admin-settings{top:5px !important;}
#toolbaractions.hasprofile .mainuserprofile,#toolbaractions.hasprofile .mainuserprofile.loaded,#toolbaractions.hasprofile #mainuserprofile{top:4px !important;}
<?php } ?>
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['toolbar_position']=='left'&&$uiconfig['mobile_landscape']['vertical_nav_persist']){?>
#toolbg{width:100% !important;height:40px !important;top:0 !important;}
#toolicons{width:100% !important;height:40px !important;top:0 !important;}
#toollist{display:none !important;}
#toollistcontent{display:none !important;}
#leftview{position:fixed !important;left:0;top:40px;width:0;height:0;border:none !important;z-index:9999 !important;overflow:visible !important;}
#lkv{z-index:9998 !important;}
#lvviews{display:none !important;}
#mvptr{display:none !important;}
#mainmenu{display:block !important;position:fixed !important;left:0;top:40px;width:50px !important;height:calc(100vh - 40px);overflow-y:auto !important;overflow-x:hidden !important;transition:width 200ms;z-index:2500;}
#mainmenu.collapsed{width:50px !important;}
#mainmenu:not(.collapsed){width:210px !important;}
#mainmenuitems{overflow-y:auto !important;overflow-x:hidden !important;}
#mainmenu .listmenuitem{padding:3px 0 !important;text-align:center !important;border-radius:0 !important;margin-left:0 !important;margin-right:0 !important;}
#mainmenu .listmenuitem a{display:block !important;padding:3px 0 !important;text-align:center !important;white-space:nowrap;}
#mainmenu .listmenuitem img{margin:0 auto !important;width:32px !important;height:32px !important;transform:scale(0.7);}
#mainmenu.collapsed .listmenuitem span{display:none;}
#mainmenu:not(.collapsed) .listmenuitem span{display:inline !important;font-size:13px;vertical-align:middle;}
#mainmenu:not(.collapsed) .listmenuitem{text-align:left !important;padding:6px 8px !important;}
#mainmenu:not(.collapsed) .listmenuitem img{margin-right:6px !important;vertical-align:middle !important;}
#mainmenu:not(.collapsed) .listmenuitem a{text-align:left !important;padding:0 !important;}
#mainmenu .listmenuitem.current{background:#72ADDD;color:#ffffff !important;}
#mainmenu .listmenuitem.current img{filter:brightness(0) invert(1);}
#vnav_account{border-top:solid 1px #dedede;}
#vnavside_hide,#vnavside_show{display:block;padding:10px 0;margin:10px 0px;border-radius:5px;text-align:left;}
#vnavside_hide img,#vnavside_show img{margin-left:20px !important;margin-right:20px !important;vertical-align:middle;}
#vnavside_hide:hover,#vnavside_show:hover{background:#dedede;}
#mainmenu:not(.collapsed) #vnavside_hide span{display:inline;font-size:13px;vertical-align:middle;}
#mainmenu:not(.collapsed) #vnavside_show span{display:inline;font-size:13px;vertical-align:middle;}
#mainmenu.collapsed #vnavside_hide span{display:none;}
#mainmenu.collapsed #vnavside_show span{display:none;}
#mainmenu.collapsed #vnavside_hide{width:50px;text-align:center;padding:10px 0;}
#mainmenu.collapsed #vnavside_show{width:50px;text-align:center;padding:10px 0;}
#mainmenu.collapsed #vnavside_hide img,#mainmenu.collapsed #vnavside_show img{display:block;}
#applogo{position:absolute !important;left:5px !important;top:3px !important;display:block !important;height:34px !important;width:auto !important;max-width:120px !important;object-fit:contain !important;}
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['enable_master_search']){?>
#adminlogout{display:inline-block !important;vertical-align:middle !important;position:relative !important;margin:0 5px !important;top:auto !important;padding-top:1px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{position:relative !important;top:auto !important;margin:0 5px !important;vertical-align:middle !important;}
#mmastersearch{position:relative !important;top:auto !important;left:auto !important;right:auto !important;width:40px !important;height:40px !important;display:inline-block !important;vertical-align:middle !important;cursor:pointer !important;z-index:10 !important;overflow:visible !important;background:transparent !important;}
#msearchicon{display:block;width:40px;height:40px;cursor:pointer;background:transparent url(../imgs/mg_dark.png) no-repeat center center;background-size:20px 20px;opacity:1;}
#msearchicon:hover{opacity:1;}
.mastersearchshell{padding:0 !important;position:absolute;top:5px;right:5px;height:30px;width:0;overflow:hidden;transition:width 250ms;border-radius:6px;}
#mastersearch{font-size:14px;border:none;background:transparent;color:#000;outline:none;line-height:28px;height:30px;box-sizing:border-box;width:100%;padding-left:36px;}
#mastersearch.expanded{background:#fff url(../imgs/mg_light.png) no-repeat 6px 50%;border:solid 1px #ccc;color:#000;border-radius:6px;}
#msearchcloser{position:absolute;top:5px;right:5px;left:auto !important;width:20px;height:20px;cursor:pointer;z-index:12;display:none;}
#mainsearchview_{position:fixed !important;top:40px !important;max-height:300px !important;z-index:2020 !important;}
#toolicons{pointer-events:none;z-index:2010 !important;}
#toolbaractions{pointer-events:auto;}
#applogo{pointer-events:auto;}
#mainmenu{pointer-events:auto;}
#bookmarkview{pointer-events:auto;}
#toollist{pointer-events:auto;}
#toollistcontent{pointer-events:auto;}
<?php } else { ?>
#adminlogout{display:inline-block !important;vertical-align:middle !important;margin:0 10px 0 5px !important;position:relative !important;top:13px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{position:relative !important;top:12px !important;margin:0 5px !important;vertical-align:middle !important;}
#toolbaractions.hasprofile #adminlogout,#toolbaractions.hasprofile .admin-settings{top:5px !important;}
#toolbaractions.hasprofile .mainuserprofile,#toolbaractions.hasprofile .mainuserprofile.loaded,#toolbaractions.hasprofile #mainuserprofile{top:4px !important;}
<?php } ?>
#pusher{display:none !important;}
#homeicon{display:none !important;}
#cornertab{bottom:10px !important;}
#cornerbutton{bottom:10px !important;}
#leftview{position:fixed !important;left:0;top:40px;width:0;height:0;border:none !important;z-index:9999 !important;overflow:visible !important;}
#lkv{z-index:9998 !important;}
#lvviews{display:none !important;}
#mvptr{display:none !important;}
#tooltitle{display:none !important;}
#tooltitleshadow{display:none !important;}
#tabtitles{top:17px !important;background:transparent !important;z-index:2000 !important;height:26px;}
#content{z-index:2000 !important;}
#tabviews{position:absolute !important;padding-top:0 !important;top:43px;}
.qnav_{position:fixed !important;height:auto !important;transition:none !important;}
#tabviews.boundless .qnav_{display:none !important;}
#tabviews.boundless .section.hasqnav{padding-left:15px !important;}
<?php } else if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['toolbar_position']=='left'&&!$uiconfig['mobile_landscape']['vertical_nav_persist']){?>
#toolbg{width:100% !important;height:40px !important;top:0 !important;}
#toolicons{width:100% !important;height:40px !important;top:0 !important;}
#toollist{display:none !important;}
#toollistcontent{display:none !important;}
#mainmenu{display:block !important;position:fixed !important;left:0;top:40px;width:180px !important;overflow:visible !important;}
#mainmenuitems{overflow-y:auto !important;overflow-x:hidden !important;}
#mainmenu .listmenuitem{padding:6px 8px !important;text-align:left !important;border-radius:0 !important;margin-left:0 !important;margin-right:0 !important;}
#mainmenu .listmenuitem a{display:block !important;padding:0 !important;text-align:left !important;white-space:nowrap;}
#mainmenu .listmenuitem img{margin-right:6px !important;vertical-align:middle !important;width:32px !important;height:32px !important;transform:scale(0.7);}
#mainmenu .listmenuitem span{display:inline !important;font-size:13px;vertical-align:middle;}
#mainmenu .listmenuitem.current{background:#72ADDD;color:#ffffff !important;}
#mainmenu .listmenuitem.current img{filter:brightness(0) invert(1);}
#vnav_account{border-top:solid 1px #dedede;}
#vnavside_hide,#vnavside_show{display:block;padding:10px 0;margin:10px 10px;border-radius:5px;}
#applogo{position:absolute !important;left:5px !important;top:3px !important;display:block !important;height:34px !important;width:auto !important;max-width:120px !important;object-fit:contain !important;}
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['enable_master_search']){?>
#adminlogout{display:inline-block !important;vertical-align:middle !important;position:relative !important;margin:0 5px !important;top:auto !important;padding-top:1px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{position:relative !important;top:auto !important;margin:0 5px !important;vertical-align:middle !important;}
<?php } else { ?>
#adminlogout{display:inline-block !important;vertical-align:middle !important;margin:0 10px 0 5px !important;position:relative !important;top:13px !important;}
.mainuserprofile,.admin-user,#mainuserprofile,.admin-settings{position:relative !important;top:12px !important;margin:0 5px !important;vertical-align:middle !important;}
#toolbaractions.hasprofile #adminlogout,#toolbaractions.hasprofile .admin-settings{top:5px !important;}
#toolbaractions.hasprofile .mainuserprofile,#toolbaractions.hasprofile .mainuserprofile.loaded,#toolbaractions.hasprofile #mainuserprofile{top:4px !important;}
<?php } ?>
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['enable_master_search']){?>
#mmastersearch{position:relative !important;top:auto !important;left:auto !important;right:auto !important;width:40px !important;height:40px !important;display:inline-block !important;vertical-align:middle !important;cursor:pointer !important;z-index:10 !important;overflow:visible !important;background:transparent !important;}
#msearchicon{display:block;width:40px;height:40px;cursor:pointer;background:transparent url(../imgs/mg_dark.png) no-repeat center center;background-size:20px 20px;opacity:1;}
#msearchicon:hover{opacity:1;}
.mastersearchshell{padding:0 !important;position:absolute;top:5px;right:5px;height:30px;width:0;overflow:hidden;transition:width 250ms;border-radius:6px;}
#mastersearch{font-size:14px;border:none;background:transparent;color:#000;outline:none;line-height:28px;height:30px;box-sizing:border-box;width:100%;padding-left:36px;}
#mastersearch.expanded{background:#fff url(../imgs/mg_light.png) no-repeat 6px 50%;border:solid 1px #ccc;color:#000;border-radius:6px;}
#msearchcloser{position:absolute;top:5px;right:5px;left:auto !important;width:20px;height:20px;cursor:pointer;z-index:12;display:none;}
#mainsearchview_{position:fixed !important;top:40px !important;max-height:300px !important;z-index:2020 !important;}
#toolicons{pointer-events:none;z-index:2010 !important;}
#toolbaractions{pointer-events:auto;}
#applogo{pointer-events:auto;}
#mainmenu{pointer-events:auto;}
#bookmarkview{pointer-events:auto;}
#toollist{pointer-events:auto;}
#toollistcontent{pointer-events:auto;}
<?php } else { ?>
#mmastersearch{display:none !important;}
<?php } ?>
#pusher{display:none !important;}
#homeicon{display:none !important;}
#cornertab{bottom:10px !important;}
#cornerbutton{bottom:10px !important;}
#leftview{display:none !important;}
#mvptr{display:none !important;}
#tooltitle{display:none !important;}
#tooltitleshadow{display:none !important;}
#content{z-index:2000 !important;}
#tabtitles{top:17px !important;left:180px;z-index:2000 !important;background:transparent !important;height:26px;}
#tabviews{position:absolute !important;padding-top:0 !important;left:180px;top:43px;}
.qnav_{position:fixed !important;left:183px !important;height:auto !important;transition:none !important;}
#tabviews.boundless .qnav_{display:none !important;}
#tabviews.boundless .section.hasqnav{padding-left:15px !important;}
#bookmarkview{display:none;position:fixed;left:0;top:40px;width:180px;overflow:auto;z-index:1900;background:#fff;}
#bookmarkview .qnav{padding-top:20px !important;}
#bookmarkview .qnavitem{display:block;float:none;padding:6px 8px;border-bottom:solid 1px #dedede;border-left:solid 4px transparent;border-radius:0;box-shadow:none;color:#111;background:transparent;text-align:left;font-size:13px;font-weight:normal;text-decoration:none;cursor:pointer;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
#bookmarkview .qnavitem b{display:inline;font-weight:normal;}
#bookmarkview .qnavitem:hover{background:#e8e8e8;}
#bookmarkview .qnavitem.infocus{background:#72ADDD;color:#fff !important;}
#bookmarkview .qnavitem.infocus b{color:#fff !important;}
<?php } ?>

#mmastersearch{height:40px;}
.mastersearchshell{padding-top:5px;width:0;overflow:hidden;}
#msearchcloser{display:none;}
#mainsearchview_{top:40px;z-index:2020;}

.savebar_content{padding:10px 5px;text-align:center;}
.changebar_content{padding:0 5px 5px 5px;text-align:center;}
.changebar_view,.savebar_view{left:240px;width:calc(100% - 240px);}

#homeicon{display:none;}

#applogo{display:block;position:absolute;left:5px;top:3px;height:34px;width:auto;max-width:120px;object-fit:contain;cursor:pointer;}
#toollistcontent{padding-left:0;}

#lkv_origin{display:none;}
#lkv.dismounted{z-index:3010;}
#lkv{height:auto;}

#mvptr{position:fixed;left:215px;width:20px;top:50px;height:100%;cursor:pointer;background:rgba(250,100,10,0) url(../imgs/lcollapse.png) no-repeat 50% 50%;background-size:12px 27px;opacity:0.4;z-index:999;transition:left 200ms,opacity 100ms;}
#mvptr:hover{opacity:0.95;background:rgba(250,250,10,0.2) url(../imgs/lcollapse.png) no-repeat 50% 50%;background-size:12px 27px;}
#mvptr.rexpand{left:0px;background:rgba(250,100,10,0) url(../imgs/rexpand.png) no-repeat 50% 50%;background-size:12px 27px;}
#mvptr.rexpand:hover{opacity:0.95;background:rgba(250,250,10,0.2) url(../imgs/rexpand.png) no-repeat 50% 50%;background-size:12px 27px;}

#leftview{position:absolute!important;left:5px;width:210px;top:50px;float:none!important;margin-left:0!important;margin-right:0!important;transition:left 200ms;}
#content{margin-left:240px;float:none!important;transition:margin-left 200ms,width 200ms;}
#tooltitleshadow{display:none;}
#tabtitleshadow{display:none;}
#tabtitles{transition:left 200ms,width 200ms;}

.qnav_{position:absolute;top:0px;left:237px;height:100%;overflow:hidden;transition:left 200ms;}
.qnav{padding-top:75px;}
body.tabafloat_landscape .qnav{padding-top:35px!important;}

.dash_to_list,.list_to_dash{display:inline !important;cursor:pointer;font-size:24px;color:#6096AC;opacity:0.6;transition:color 100ms,opacity 100ms;}
.dash_to_list:hover,.list_to_dash:hover{color:#3DE527;opacity:1;}

#cornertab{z-index:3000 !important;}
#cornerbutton{z-index:3000 !important;}

.tabclose_btn{display:none;}
.tabdock_btn{right:0;}
.tabdock_btn.afloat{right:0;}
.tabdock_btn+.tabreload_btn{right:32px;}
.tabreload_btn{right:32px;}

#mainmenu{display:none;}
body.tabafloat_landscape #leftview{display:none;}
body.tabafloat_landscape #mvptr{display:none;}
body.tabafloat_landscape #tabtitles{display:none;}
body.tabafloat_landscape #content{margin-left:0!important;width:100%!important;}
body.tabafloat_landscape #tabviews{padding-top:0 !important;}
body.tabafloat_landscape #toolbg{display:none;}
body.tabafloat_landscape #tooltitle{display:none;}
body.tabafloat_landscape #toollist{display:none;}
body.tabafloat_landscape #toolicons{display:none!important;}
body.tabafloat_landscape #applogo{display:none!important;}
body.tabafloat_landscape #pusher{height:0!important;}
body.tabafloat_landscape .qnav_{left:0!important;}
<?php if (isset($uiconfig['mobile_landscape'])&&$uiconfig['mobile_landscape']['toolbar_position']=='left'){?>
body.tabafloat_landscape #tabviews{left:0!important;top:0!important;}
body.tabafloat_landscape #mainmenu{display:none!important;}
body.tabafloat_landscape #bookmarkview{display:none!important;}
body.tabafloat_landscape #content{z-index:auto!important;}
body.tabafloat_landscape .qnav_{display:block!important;left:0!important;top:0!important;}
body.tabafloat_landscape #tabviews.boundless .qnav_{display:block!important;}
<?php } ?>

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

if ($dark==0||$dark==1||$dark==3){
?>

	body,#tooltitle{background:#353535;}
	#tabtitles{background:#353535;}
	.listitem,.sortlistitem{border-bottom:solid 1px #21262D;}
	.tabclose_btn,.tabreload_btn,.tabdock_btn{filter:invert(1) opacity(0.9);border-color:rgba(255,255,255,0.15);}
	#msearchicon{opacity:1;}
	#mastersearch.expanded{background:#2d333b url(../imgs/mg_dark.png) no-repeat 6px 50%;border-color:#444;color:#c9d1d9;}
	#bookmarkview{background:#1a1a1a;}
	#bookmarkview .qnavitem{border-bottom:solid 1px #333;color:#ccc;background:transparent;}
	#bookmarkview .qnavitem:hover{background:#1E2228;}
	#bookmarkview .qnavitem.infocus{background:#1a4a6a;color:#fff !important;}
	#bookmarkview .qnavitem.infocus b{color:#fff !important;}
	#mainmenu{color:#ffffff;}
	#vnavside_hide,#vnavside_show{color:#ffffff;}
	#mainmenu .listmenuitem:hover{background:#1E2228 !important;}
	#mainmenu .listmenuitem.current{background:#10233B;color:#68A7EA !important;}
	#mainmenu .listmenuitem.current img{filter:none !important;}
	#vnavside_hide:hover,#vnavside_show:hover{background:#1E2228 !important;}
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}


	