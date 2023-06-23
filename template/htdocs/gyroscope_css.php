<?php
header('Content-Type: text/css');
?>
body{margin:0;padding:0;background:#F2F2F2;overflow:hidden;}
body, td, textarea, .img-mg{font-family:arial,sans-serif;font-size:13px;-webkit-text-size-adjust:none;}

.img-mg,.sectiontitle,
.listitem,.inplong, .inp, .inpmed, .inpshort, .inpxshort{font-family:Arial,serif;}


table{line-height:25px;}
table.subtable{line-height:18px;}
a{cursor:pointer;}
acronym{text-decoration:none;border:none;}
acronym.help{cursor:help;border-bottom:dotted 1px #4444ee;}

.mobileonly{display:none;}


.inpback{display:none;}

.kpwlink{display:none;}

.medialibtile{float:left;width:19%;margin-right:1%;position:relative;overflow:hidden;margin-bottom:10px;}

#applogo{position:relative;z-index:10;}

#iconbelt{height:48px;position:relative;width:400px;overflow:hidden;float:left;}
#topicons{position:absolute;top:0;left:0;transition:left 500ms;}

#mastersearchanchor{margin-right:20px;display:inline-block;position:relative;}
#mastersearchshadow,#mastersearch{font-size:15px;border:solid 1px #5F5C5C;border-radius:6px;line-height:28px;height:30px;box-sizing:border-box;color:#ffffff;outline:0;background:#0D1117 url(imgs/mg_dark.png) no-repeat 6px 50%;width:120px;padding-left:40px;transition:width 250ms,background 250ms,color 250ms,opacity 250ms;}
#mastersearchshadow{color:#5E5B5B;}
#mastersearch{position:absolute;top:0;right:0;opacity:0;}
#mastersearch.expanded{background:#ffffff url(imgs/mg_light.png) no-repeat 6px 50%;color:#000000;opacity:1;}
#mainsearchview_{position:absolute;top:44px;right:120px;width:300px;background:#ffffff;border:solid 1px #aaaaaa;border-radius:0 0 10px 10px;box-shadow:0 0 4px #000000;max-height:100px;overflow:auto;opacity:1;display:none;transition:opacity 100ms;}
#mainsearchview{padding:10px;}

#beltprev{visibility:hidden;margin-right:5px;}
#beltnext{margin-left:5px;margin-right:0;}

.warnbox{padding:10px;margin-bottom:10px;background:#ffdede;line-height:1.5em;}
.infobox{padding:10px 0;font-size:13px;font-style:italic;color:#666666;line-height:1.5em;}

.welcometile img{width:32px;height:32px;}
.recadder img{width:18px;height:18px;}

.touchonly{display:none;}

.changebar{display:none;z-index:1100;}
.changebar_anchor{position:relative;z-index:1000;}
.changebar_view{position:fixed;background:rgba(255,220,193,0.9);width:100%;}
.changebar_content{padding:0 300px 5px 5px;text-align:center;}
.changebar_shadow{visibility:hidden;display:none;}
.changebar_content button{font-size:11px;padding:3px 8px;}

.autosaver{color:#444444;}
.autosavekiller{display:inline-block;background:#ab0200;padding:0 4px;vertical-align:middle;border-radius:20px;color:#ffffff;font-weight:bold;margin-left:5px;}

.savebar{display:none;z-index:1200;}
.savebar_anchor{position:relative;z-index:1100;}
.savebar_view{position:fixed;background:#CEFFC7;width:100%;}
.savebar_content{padding:10px 300px 10px 5px;text-align:center;}

/* Loaders */
#mainmenu{position:absolute;top:56px;left:0;width:260px;overflow-y:auto;overflow-x:hidden;z-index:250;}
#mainmenu.silent{display:none;}
#leftview{background-color:#ffffff;position:absolute;left:20px;width:260px;top:122px;transition:background 250ms,left 50ms;-webkit-transition:background 250ms,left 50ms;}
#leftview_{overflow:auto;overflow-y:auto;overflow-x:hidden;border-left:solid 1px #CCCCCC; border-right:solid 1px #cccccc;}
#leftview.promoted{left:0px;z-index:300;}
#leftview.promoted #leftview_{background_x:#dedede;}


#tooltitle{background:transparent url(imgs/sprite.png) no-repeat 0 -35px;position:absolute;left:20px;width:260px;height:32px;top:90px;transition:left 50ms;}
	#tooltitle a{font-weight:bold;font-size:16px;color:#f2f2f2;margin-left:15px;margin-top:6px;display:block;transition:color 250ms;}
	#tooltitle a:hover{color:#72ADDE;}
	
#tooltitle.promoted{left:0;}

#closeall{margin-left:4px;display:none;float:left;margin-top:5px;margin-right:10px;background:#971B2F;height:20px;font-size:10px;padding:0 5px;padding-right:6px;}
#closeall b{font-weight:normal;padding-top:2px;display:block;color:#f2f2f2;}
		
#tabviews{transition:background 250ms,left 50ms,width 50ms;-webkit-transition:background 250ms,left 50ms,width 50ms;border-left:solid 1px #cccccc;box-shadow:-8px 0 4px -4px #dedede;}	
#tabviews.boundless{box-shadow:none;border:none;}

.lksel{position:absolute;top:5px;right:5px;}
	
#lkv{position:absolute;left:0;width:258px;top:40px;left:-280px;transition:left 100ms;-webkit-transition:left 100ms;border-radius:4px 4px 0 0;}
#lkvtitle{background:transparent url(imgs/sprite.png) no-repeat 0 -223px;height:32px;position:relative;border-radius:4px 4px 0 0;overflow:hidden;}
	#lkvtitle a{font-weight:bold;font-size:14px;color:white;margin-left:30px;margin-right:30px;padding-top:6px;display:block;}
	#lkvx{position:absolute;top:0;right:0;cursor:pointer;background:transparent url(imgs/sprite.png) no-repeat -285px -174px;}	
	#lkvc{border-right:solid 1px #666666;overflow:auto;background-color:#ffffff;transition:background 250ms;-webkit-transition:background 220ms;}
	#lkvs{width:260px;height:60px;position:absolute;top:-40px;left:0;background:#ffffff;opacity:0.8;filter:alpha(opacity=80);}	
	
#lkv_origin{display:none;}
#lkv.dismounted{z-index:3010;border:none;}
#lkv.dismounted #lkvc{border:solid 1px #666666;border-top:none;box-shadow:0 2px 4px #000000;}
#lkv.dismounted #lkvtitle{background:#187CA6;border-radius:4px 4px 0 0;overflow:hidden;cursor:move;-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;}
#lkv.dismounted #lkvtitle #lkvt{cursor:move;}
#lkv.dismounted #lkvs{display:none;}
	
#lefticons{width:100%;position:absolute;top:0px;left:0;height:72px;background:transparent url(imgs/sprite.png) repeat-x 0 -258px;}
#lefticons.solid{background:#3A3A3A;height:57px;}
#vsptr{position:absolute;left:280px;width:12px;height:100px;top:122px;font-size:1px;cursor:pointer;transition:left 50ms,background 100ms,opacity 100ms;background:rgba(250,100,10,0) url(imgs/lcollapse.png) no-repeat 50% 50%;background-size:12px 27px;opacity:0.4;}
#vsptr:hover{background:rgba(250,250,10,0.2) url(imgs/lcollapse.png) no-repeat 50% 50%;background-size:12px 27px;opacity:0.95;}
  #vsptr.rexpand{background:rgba(250,250,10,0) url(imgs/rexpand.png) no-repeat 50% 50%;background-size:12px 27px;}
  #vsptr.rexpand:hover{background:rgba(250,250,10,0.2) url(imgs/rexpand.png) no-repeat 50% 50%;background-size:12px 27px;opacity:0.95;}
#sptr{background:#666666;position:absolute;left:0;bottom:24px;height:6px;width:100%;font-size:1px;opacity:0.2;-webkit-opacity:0.2;filter:alpha(opacity=20);}
#tabtitles{background:transparent url(imgs/tabbg.gif) repeat top left;position:absolute;left:295px;height:38px;top:90px;transition:left 50ms,width 50ms;}
#tabtitles.moveup{background:transparent url(imgs/tabbg2.gif) repeat top left;top:25px;left:0;width:100%;}
#tabtitles.moveup .firsttab{margin-left:212px;}

/* Fullscreen*/
#fsmask{position:absolute;top:0;left:0;width:100%;height:100%;background:#000000;opacity:0.4;filter:alpha(opacity=40);display:none;z-index:3001;}
#fstitlebar{position:absolute;top:10px;left:10px;width:100%;height:30px;background:#555555;border-radius:3px 3px 0 0;display:none;z-index:3002;}
#fstitle{color:#ffffff;padding:5px 10px;font-size:15px;font-weight:bold;}
#fsclose{display:block;width:24px;height:18px;position:absolute;top:6px;right:6px;background:#971B2F;border:solid 1px #888888;}
#fsclose:hover{border-color:#ffffff;}
#fsclose .img-closeall{margin:4px 6px;}
#fsview{position:absolute;top:40px;left:10px;width:100%;background:#ffffff;display:none;overflow:auto;z-index:3002;}

/* Status */
.hourglass{margin:10px;}
#statusinfo .hourglass{margin:0;}

#statusinfo{width:100%;bottom:0;border-top:solid 1px #666666;background:#BBCDD5 url(imgs/sprite.png) no-repeat top right;position:absolute;left:0;height:24px;}
#statusicons{margin-left:20px;}
#statusc{margin-left:10px;padding-top:3px;display:inline-block;}


#speechstart{display:none;transition:margin 300ms;}
#speechstart img{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -50px 0;margin-left:4px;margin-top:2px;}

#wsswarn{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -32px 0;margin-left:8px;margin-top:2px;display:none;}
#barcodewarn{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -48px -16px;margin-left:8px;margin-top:2px;display:none;}
#diagwarn{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -64px -32px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#sysreswarn{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -512px -16px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#lI01{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -80px -32px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#imecree{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -96px -48px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#gsnotesclip{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -112px -48px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#gamepadicon{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -512px -32px;margin-left:8px;margin-top:2px;display:none;cursor:pointer;}
#gamepadicon.spotactive{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -512px -48px;margin-left:8px;margin-top:2px;cursor:pointer;}

#chatindicator{width:16px;height:16px;background:transparent url(imgs/toolbar.gif) -96px -32px;margin-left:8px;margin-top:2px;cursor:pointer;display:none;}
#chatindicator.offline{background-position:-112px -32px;}

.break{border-right:solid 1px #cccccc;float:left;height:32px;margin-right:15px;font-size:1px;}
.break span{display:block;border-right:solid 1px #f0f0f0;height:32px;float:left;}

#logoutlink{font-weight:bold;font-size:11px;position:absolute;right:20px;top:28px;color:#f2f2f2;z-index:500;}
	#logoutlink a, #logoutlink a:visited{text-decoration:none;color:#f2f2f2;}
	
#logoutlink.bigprofile{top:20px;}

#logoutlink #mainuserprofile{width:16px;height:16px;}
#logoutlink.bigprofile #mainuserprofile{width:32px;height:32px;border-radius:50%;overflow:hidden;background:#dedede;}


#logoutlink.moveup{top:20px;}
#logoutlink.bigprofile.moveup{top:13px;}

#logoutlink.hassearch{top:18px;}
#logoutlink.moveup.hassearch{top:14px;}
#logoutlink.bigprofile.moveup.hassearch{top:14px;}

#logoutlink.moveup #labellogin{display:none;}
	
#logoutlink img{margin-right:7px;border:none;vertical-align:middle;}

#logoutlink a:hover{
	opacity:0.6; filter:alpha(opacity=60);
	transition:opacity 400ms; -webkit-transition:opacity 400ms;
}


/* Tabs */
.tabclose, .noclose, .closeall{display:block;float:left;height:32px;width:30px;cursor:pointer;}
.closeall{width:32px;}
.noclose{width:8px;}
.activetab a, .dulltab a{float:left;}
.tt{min-width:30px;padding-top:9px;padding-bottom:5px;font-size:12px;padding-left:10px;-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;}
.tt img{vertical-align:middle;}
.activetab .tabclose{background:transparent url(imgs/sprite.png) no-repeat -369px -174px;}
.dulltab .tabclose, .img-close{background:transparent url(imgs/sprite.png) no-repeat -325px -174px;}

.activetab .closeall{background:transparent url(imgs/sprite.png) no-repeat -290px -176px;}
.dulltab .closeall{background:transparent url(imgs/sprite.png) no-repeat -254px -176px;}

.activetab .noclose{background:transparent url(imgs/sprite.png) no-repeat -391px -219px;}
.dulltab .noclose{background:transparent url(imgs/sprite.png) no-repeat -347px -219px;}

.activetab, .dulltab {font-weight:bold;display:block;float:left;height:38px;margin-right:8px;}
.activetab{color:#000000;background:transparent url(imgs/sprite.png) no-repeat 0 -76px;box-shadow:0 -1px 2px 0 #cccccc;border-radius:5px 5px 0 0;}
.dulltab{color:#000000;background:transparent url(imgs/sprite.png) no-repeat 0 -122px;}

#tabtitles.moveup .activetab .tabclose{border-radius:0 5px;}
#tabtitles.moveup .activetab .noclose{background:transparent;}
#tabtitles.moveup .dulltab{border-radius:5px 5px 0 0;}
#tabtitles.moveup .dulltab .tabclose{border-radius:0 5px 0 0;}
#tabtitles.moveup .dulltab .noclose{background:transparent;}


/* Icons */

.img-calel{background:transparent url(imgs/sprite.png) no-repeat -47px -178px;margin-right:10px;width:5px;height:12px;} /* 5x12 */
.img-caler{background:transparent url(imgs/sprite.png) no-repeat -70px -178px;margin-left:10px;width:5px;height:12px;}
.img-mg{border-color:#cccccc;background:#f2f2f2 url(imgs/sprite.png) no-repeat 100% -330px;padding-left:5px;padding-right:25px;margin-bottom:8px;height:32px;line-height:30px;width:185px;vertical-align:middle;} /* 12x12 +14 */
.mediaimg-mg{border-color:#cccccc;background:#f2f2f2 url(imgs/sprite.png) no-repeat 100% -330px;padding-left:5px;padding-right:25px;margin-bottom:8px;height:32px;line-height:30px;vertical-align:middle;width:50%;} /* 12x12 +14 */

.searchsubmit{display:none;}
.img-help{border:none;background:transparent url(imgs/sprite.png) no-repeat -248px -45px;width:12px;height:12px;} /* 12x12 */
.img-print{border:none;background:transparent url(imgs/sprite.png) no-repeat -279px -27px;width:16px;height:16px;vertical-align:middle;} /* 16x16 */
.recadder{color:#187CA6;margin-bottom:5px;font-size:14px;}
.recadder:hover{color:#29ABE1;}
.recadder img{vertical-align:middle;margin-right:4px;}


/* List */
.listitem, .sortlistitem{line-height:1.5em;border-bottom:solid 1px #D4EDC9;padding:3px 0;margin-bottom:3px;}
.listitem a,.listitem a:link,.listitem a:visited,.listitem a:hover{text-decoration:none;color:#000000;}
.listitem a:focus{color:#ab0200;}


.sortlistitem{border-left:solid 6px #D8D8D8;padding-left:12px;}

.section{padding:24px 15px;}
.section.hasqnav{padding-left:45px;}

#tabviews.boundless .section.hasqnav{padding-left:15px;}

.qnav_{position:absolute;top:0px;left:0;height:100%;overflow:hidden;}
.qnav{padding-top:45px;}

#tabviews.boundless .qnav_{display:none;}
#tabviews.boundless .afloat .hasqnav{padding-left:45px;}
#tabviews.boundless .afloat .qnav_{display:block;}

#tabexpander{position:absolute;width:28px;height:28px;top:-200px;right:12px;z-index:3000;cursor:pointer;background:transparent url(imgs/toolbar.gif) no-repeat -643px -4px;transform:scale(0.65);transition:transform 250ms;}
#tabexpander:hover{transform:scale(0.8);}

#tabexpander.afloat{background:transparent url(imgs/toolbar.gif) no-repeat -643px -36px;transform:scale(0.7);}
#tabexpander.afloat:hover{transform:scale(0.55);}

.wideview{margin-left:296px;}
.wideviewmenu_{position:absolute;top:0;left:0;width:295px;height:100%;overflow:auto;direction:rtl;}
.wideviewmenu{direction:ltr;padding:10px 0;}

.wideviewmenu .sectiontitle{padding-left:15px;font-size:16px;}

.wideviewmenu .listitem{border-left:solid 6px transparent;padding:8px 0;padding-left:10px;margin-bottom:0;}
.wideviewmenu .listitem.current{border-left:solid 6px #72ADDD;}

.wideview .qnav_{display:none;}


.afloat .qnav_{position:fixed;}
.afloat{background:#ffffff;}
.tabchanged{background:#ffdddd;}

.afloat .wideviewmenu{display:none;}
.afloat .wideviewmenu.transpose{display:block;}
.afloat .wideview .qnav_{display:block;}
.afloat .wideview{margin-left:0;}

.qnavitem{clear:both;float:left;font-weight:bold;margin-bottom:10px;font-size:13px;background:#848cf7;color:#ffffff;padding:5px 8px;border-radius:0 5px 5px 0;box-shadow:0 2px 4px #999999;display:block;overflow:hidden;white-space:nowrap;}
.qnavitem:hover{font-weight:normal;}
.qnavitem b{display:none;font-weight:normal;}
.qnavitem:hover b{display:inline;}

#bookmarkview{}
#bookmarkview .qnavitem{float:none;display:block;font-weight:normal;border-radius:0;text-align:left; padding:5px 0;padding-left:10px;font-size:14px;background:transparent;color:#111111;box-shadow:none;border:none;border-bottom:solid 1px #dedede;border-left:solid 4px transparent;}
#bookmarkview .qnavitem b{display:inline;font-weight:normal;}
#bookmarkview .qnavitem.infocus{border-left:solid 4px #388BFD;}

.enl_date{border-bottom:solid 1px #804000;color:#201000;}
.enl_client{border-bottom:solid 1px #400080;color:#100020;}
.form div div{height:22px;}

.sectiontitle{font-weight:bold;font-size:18px;margin-bottom:20px;}

.sectiontitle a .edithover{display:inline-block;width:16px;height:16px;background:transparent;margin-left:5px;transition:margin 50ms;}
.sectiontitle a:hover .edithover{margin-left:0;background:transparent url(imgs/toolbar.gif) no-repeat -704px 0;margin-right:5px;}

.sectiontitle a{
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;	
}

input,textarea{border:solid 1px #999999;}
.sectionheader{color:#000000;font-size:13px;font-weight:bold;background-color:#dedede;margin-top:12px;margin-bottom:10px;padding:3px 10px;}
.minicaletitle{position:relative;text-align:center;padding-left:20px;padding-right:20px;width:135px;margin-top:5px;margin-bottom:5px;}
.minicaletitle span{}
.minicaleprev{position:absolute;display:block;top:0px;left:5px;}
.minicalenext{position:absolute;display:block;top:0px;right:5px;}

.iconbuttons img{border:none;}
.iconbuttons a{display:block;float:left;text-align:center;margin-right:20px;font-size:10px;transition:opacity 400ms; -webkit-transition:opacity 400ms;}
.iconbuttons a, .iconbuttons a:hover, .iconbuttons a:visited, .iconbuttons a:link{color:#F2F2F2;text-decoration:none;}

.iconbuttons a:hover{opacity:0.6; filter:alpha(opacity=60);}
.iconbuttons .noblink, .iconbuttons .noblink:hover{opacity:1;filter:none;cursor:default;}

.labelbutton{font-size:11px;background-color:#444444;padding:1px 4px;border-radius:2px;color:#ffffff;white-space:nowrap;}
.labelbutton:hover{background:#000040;}
.labelbutton:focus{background:#ab0200;}

.sectionheader .labelbutton{font-weight:normal;}
.labelbutton, .labelbutton:visited, .labelbutton:hover, .labelbutton:link{text-decoration:none;}

.minilookup{width:200px;font-size:11px;display:none;position:relative;padding-top:16px;}
.lookupview{height:200px;width:200px;overflow:auto;border:solid 1px #666666;box-shadow:2px 2px 4px #666666;}
.lookupview #timepicker{height:200px;overflow:auto;}
.minilookup .closer{position:absolute;top:3px;right:20px;line-height:16px;}

#leftviewcloser{display:none;position:absolute;top:-31px;right:0;cursor:pointer;padding:8px;}
#leftviewcloser img{dislay:block;width:9px;height:9px;background:transparent url(imgs/sprite.png) -301px -31px;}

.clear{clear:both;}
.col{width:450px;margin-right:20px;float:left;}
.majorcol, .minorcol, .rmajorcol, .rminorcol{float:left;}
.majorcol{width:580px;}
.minorcol{width:380px;margin-right:20px;}
.rmajorcol{width:580px;margin-right:20px;}
.rminorcol{width:380px;}

/*#tabviews.boundless .col{width:auto;float:none;margin-bottom:20px;}*/

.formlabel{color:#444444;padding-right:10px;margin-bottom:5px;font-weight:bold;}
.inplong, .inp, .inpmed, .inpshort, .inpxshort{padding:5px 0;padding-left:5px;margin-bottom:5px;border-color:#cccccc;}
.inplong{width:98%;}
.inp{width:350px;}
.inpmed{width:280px;}
.inpshort{width:80px;}
.inpxshort{width:40px;}

.inplong.num, .inp.num, .inpmed.num, .inpshort.num, .inpxshort.num{padding-left:0;padding-right:2px;text-align:right;}

.inputrow{margin-bottom:10px;}

textarea.inplong, textarea.inp, textarea.inpmed{height:150px;}
select.inp, select.inplong, select.inpmed, select.inpshort, select.inpxshort{width:auto;}

.stable{overflow-x:auto;}
.stable td{padding:0 5px;font-size:12px;}

.stable::-webkit-scrollbar {height:12px;}
 
.stable::-webkit-scrollbar-track {
	border-radius: 2px;
	background:#fcfcfc url('iphone/darrow.png') no-repeat center center;
	background-size:16px 10px;
}
 
.stable::-webkit-scrollbar-thumb {background-color:rgba(200,200,200,0.2); border-radius:2px;}

button, .button{white-space:nowrap;color:#ffffff;background:#187CA6;padding:5px 15px;border-radius:3px;border:none;cursor:pointer;box-shadow:0px 1px 2px #c9c9c9;margin-top:5px;-webkit-appearance: none;}
button:focus, .button:focus{background:#29ABE1;}
button.warn, .button.warn{background:#881818;}
button.disabled, .button.disabled{background:#dedede;box-shadow:none;}
button:active, .button:active{box-shadow:1px 1px 3px #999999;}
button.disabled:active, .button.disabled:active{box-shadow:none;}
button.trivial{background:none;color:#666666;box-shadow:none;font-weight:bold;padding-left:0;}
button.trivial:active{box-shadow:none;}

.button, .button:hover, .button:visited, .button:link{text-decoration:none;}

button:hover, .button:hover{background:#29ABE1;}
button.warn:hover, .button.warn:hover{background:#971B2F;}
button.disabled:hover, .button.disabled:hover{background:#dedede;}
button.trivial:hover, .button.trivial:hover{background:none;}

.changebar_button{background:#9D620D;}
.changebar_button:hover{background:#EEA226;}

.phelpspot button, .phelpspot .button{border:solid 1px #999999;}

.subtable .inplong, .subtable .inp, .subtable .inpmed, .subtable .inpshort, .subtable .inpxshort{margin-top:10px;}

.listbar{font-size:12px;padding:5px 0;}


input:disabled{color:#000000;}

.welcometile{float:left;width:24%;margin-right:1%;margin-bottom:20px;}

.listmenuitem{padding:8px 0;padding-left:6px;border-left:solid 4px #999999;margin-top:2px;margin-bottom:2px;}
.listmenuitem img{width:32px;height:32px;transform:scale(0.7);}

@media screen and (min-width:10px){
	.tt{max-width:150px;overflow-x:hidden;}
	
	#tabtitles.compact .tt{min-width:30px;max-width:75px;font-family:"Arial Narrow",sans-serif;padding-left:5px;}
	#tabtitles.compact .tt img{margin-right:2px;}
	
	button{min-width:50px;}
}

@media screen and (max-width:1330px){
	.col{width:49%;margin-right:1%;}
	.minorcol{width:38%;margin-right:2%;}
	.majorcol{width:60%;}
	.rmajorcol{width:60%;margin-right:2%;}
	.rminorcol{width:40%;}	
}


@media screen and (max-width:1070px){
	.welcometile{width:32%;margin-right:1%;}
	.inplong,.inp{width:95%;}
}


@media screen and (max-width:990px){
	.col, .majorcol, .minorcol, .rmajorcol, .rminorcol{width:auto;float:none;margin-bottom:10px;}	
}

@media screen and (max-width:940px){
	.medialibtile{width:24%;}
}

@media screen and (max-width:840px){
	.welcometile{width:49%;margin-right:1%;}
}

@media screen and (max-width:780px){
	.mceToolbar .mceSeparator, .mceToolbar .mce_link, .mceToolbar .mce_unlink{display:none !important;}
	.welcometile{float:none;width:auto;margin-right:auto;}
}

@media screen and (max-width:720px){
	.medialibtile{width:32%;}
}

@media screen and (max-width:480px){
	.medialibtile{width:49%;}
	.mediaimg-mg{width:80%;}
	.stable{overflow:auto;}
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

	#tabexpander{filter:invert(1);}
	
	#mainmenu{color:#ffffff;}
	
	#bookmarkview .qnavitem	{color:#dedede;border-color:#999999;border-left:solid 4px transparent;}
	#bookmarkview .qnavitem.infocus{border-left:solid 4px #E87655;}
	
	#mainsearchview_{background:#2F3235;color:#dedede;}
	.mainsearchitem{border-color:#666666;}
	.mainsearchitem:hover{background:#3A3A3A;}	
	
	#tooltitle,#lefticons, .tabclose, .activetab, .dulltab,
	.activetab .noclose, .dulltab .noclose,
	.activetab .tabclose, .dulltab .tabclose,#lkvtitle,#lkvx,
	#statusinfo{background-image:url(imgs/dsprite.png);}
	
	#lkvc{border-right:solid 1px #2F5464;}
	
	.wideviewmenu .listitem.current{border-left:solid 6px #294B70;background:#10233B;color:#68A7EA;}
	
	.img-mg{border-color:##21262D;background:#0D1117 url(imgs/dsprite.png) no-repeat 100% -330px;}	
	

	.afloat{background:#0D1117;}
	.tabchanged{background:#291313;}	
	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}

