<?php
header('Content-Type: text/css');
?>
/*
use toolbar.psp to generate toolbar.gif
*/

.scal_view::-webkit-scrollbar{
	display:none;
}

.scal_head .scal_hcell{width:14%;}
.scal_view .scal_cell{width:14%;}


.scal_view .scal_cell{border:solid 1px #444444;}
.scal_view .scal_cell.today{border:solid 3px #ffab00;}
.scal_view .scal_cell.dow_0,.scal_view .scal_cell.dow_6{background:#ffeecc;} /* remember to update dark mode */


/*
.scal_head .scal_hcell{width:20%;}
.scal_view .scal_cell{width:20%;}
.scal_view .scal_cell.dow_0,.scal_view .scal_cell.dow_6,
.scal_head .scal_hcell.dow_0, .scal_head .scal_hcell.dow_6 {display:none;}

*/


.charttoolbar{
	border:solid 1px #dedede;
	background:#ffffff;
	box-shadow:0 0 4px #666666;	
}

.watchonly{display:none;}

.list_to_dash, .dash_to_list{
	cursor:pointer;font-size:24px;
	color:#6096AC;opacity:0.6;transition:color 100ms,opacity 100ms;
}

.list_to_dash:hover, .dash_to_list:hover{color:#3DE527;opacity:1;}

.list_to_dash{display:none;}
#leftview .list_to_dash{display:inline;}

.afloat .dash_to_list{display:none;}

.inputrow input[type=checkbox],.inputrow input[type=radio]{vertical-align:middle;}
.inputrow label{vertical-align:middle;}

#tabviews.bgready{background:#ffffff;}
#tabviews.bgflash{background:#ffffc0;}

#leftview.bgready{background:#ffffff;}
#leftview.bgflash{background:#ffffc0;}

.inpgood{background:#D1E7D1;}
.inpbad{background:#EFCAC9;}

.recbad{color:#cc0000;}
.recdim{font-style:italic;color:#666666;}

.reloader.busy{background:#ffcccc;}
.listchanged{background:#ffcccc;}

.listpager{padding:10px 0;font-size:12px;}
#calepicker{font-size:12px;width:100%;height_x:200px;margin:0 auto;margin-top:5px;padding-bottom:5px;}

.ssrp_title{font-family:arial, sans-serif; font-size:18px; color:#1A0DAB;line-height:21.6px;}
.ssrp_link{font-family:arial, sans-serif; color:#006621; font-size:14px;line-height:16px;}
.ssrp_desc{font-family:arial, sans-serif; color:#545454; font-size:13px;line-height:18.2px;}

.listitem{overflow-wrap:break-word;}
.listitem span, .listitem .img-del {vertical-align:middle;}

.hovlink, .hovlink:visited, .hovlink:hover, .hovlink:link,
.listitem .hovlink, .listitem .hovlink:visited, .listitem .hovlink:hover, .listitem .hovlink:link{color:#187CA6;text-decoration:none;border-bottom:dotted 1px #187CA6;}

.listitem .diminished{color:#888888;}
.listitem:hover .diminished{color:#444444;}

.dashbuttons .listitem{border:solid 1px #dedede;box-shadow:0 0 2px #000000;border-radius:5px;float:left;margin-bottom:10px;width:18%;margin-right:2%;text-align:center;padding:8px 0;height:20px;overflow:hidden;}
.dashbuttons .listitem a{display:block;}


.hovlink:focus, .listitem .hovlink:focus{color:#29ABE1;}

.hovlink:hover, .listitem .hovlink:hover{border-bottom:solid 1px #29ABE1;color:#29ABE1;}

.largertext{font-size:18px;}
.smallertext{font-size:13px;}

.tsinfo{text-align:right;}
.tslogo,.tsdark{width:210px;}
.tsdark{display:none;}

#gsstickerview{z-index:3003;border-radius:20px;background:rgba(240,248,255,0.9);width:90%;height:40%;position:fixed;top:25%;left:5%;display:none;border:solid 4px #8f8cf7;}
#gsstickercontent{line-height:1.3em;font-family:'Times New Roman',Georgia,serif;font-size:40px;text-align:center;width:100%;height:100%;display:table-cell;vertical-align:middle;}

.caleheader{height:20px;border:solid 1px #ffffff;margin-left:1px;}
.calecell{height:25px;border:solid 1px #444444;margin:1px;}

#calepicker{padding-top:10px;}

.calledout{background:#ffffcc;}

.caletimeitem{position:relative;height:30px;border-bottom:solid 1px #999999;}

.videoframe_16x9{position:relative;width:100%;height:0;padding-bottom:58%;overflow:hidden;}
.videoframe{position:absolute;top:0;left:0;width:100%;height:100%;display:block;border:none;}

input:disabled{
	color:#000000;
	background-color:#F8F8F8;
}

#gyroscope_updater{font-size:13px;}
.footerpoweredby{font-size:12px;}

.sectionheader.open, .sectionheader.close{cursor:pointer;padding-left:20px;}
.sectionheader.open:hover, .sectionheader.close:hover{opacity:0.8;}
.sectionheader.open{background:#dedede url(imgs/title_open.gif) no-repeat 0 50%;}
.sectionheader.close{background:#dedede url(imgs/title_close.gif) no-repeat 0 50%;}

.admin-logout{background:transparent url(imgs/toolbar.gif) no-repeat 0 0;}	
.admin-settings{background:transparent url(imgs/toolbar.gif) no-repeat 0 -16px;}	
.admin-user{background:transparent url(imgs/toolbar.gif) no-repeat -16px -16px;}
.img-addrec{background:transparent url(imgs/toolbar.gif) no-repeat -32px -32px;}	

.beltprev{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -64px 0;width:16px;height:32px;vertical-align:middle;}
.beltnext{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -80px 0;width:16px;height:32px;vertical-align:middle;}

.img-del{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -52px -32px;width:12px;height:12px;vertical-align:middle;}
.img-save{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -52px -52px;width:12px;height:12px;vertical-align:middle;}
.img-tick{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -32px -53px;width:12px;height:12px;vertical-align:middle;}
.img-xls{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -64px -52px;width:12px;height:12px;vertical-align:middle;}
.img-pdf{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -76px -52px;width:12px;height:12px;vertical-align:middle;}

.img-up{border:none;background:transparent url(imgs/toolbar.gif) no-repeat 0 -32px;width:12px;height:12px;vertical-align:middle;}
.img-down{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -12px -32px;width:12px;height:12px;vertical-align:middle;}

.daylightsaving{border:none;background:transparent url(imgs/toolbar.gif) no-repeat -32px -20px;width:12px;height:12px;vertical-align:top;}

.img-closeall{background:transparent url(imgs/toolbar.gif) no-repeat -21px -1px;vertical-align:middle;margin-right:4px;}

.img-home{background:transparent url(imgs/toolbar.gif) no-repeat -96px 0;}
.img-gsclip{background:transparent url(imgs/toolbar.gif) no-repeat -544px 0;}
#gsnotesclipicon{display:none;}
.img-settings{background:transparent url(imgs/toolbar.gif) no-repeat -384px 0;}
.img-settings-light{background:transparent url(imgs/toolbar.gif) no-repeat -384px -32px;}

.img-reports{background:transparent url(imgs/toolbar.gif) no-repeat -224px 0;}
.img-reports-light{background:transparent url(imgs/toolbar.gif) no-repeat -224px -32px;}

.img-helptopics{background:transparent url(imgs/toolbar.gif) no-repeat -576px 0;}
.img-helptopics-light{background:transparent url(imgs/toolbar.gif) no-repeat -576px -32px;}

.img-speechrecog{background:transparent url(imgs/toolbar.gif) no-repeat -128px 0;}

.img-chats{background:transparent url(imgs/toolbar.gif) no-repeat -320px 0;}
.img-chats-light{background:transparent url(imgs/toolbar.gif) no-repeat -320px -32px;}

#chaticon{background:transparent url(imgs/toolbar.gif) no-repeat -448px 0;display:none;}
#chaticon.offline{background:transparent url(imgs/toolbar.gif) no-repeat -448px -32px;display:none;}

.img-search{background:transparent url(imgs/toolbar.gif) no-repeat -544px -32px;}

.img-clock{width:12px;height:12px;background:transparent url(imgs/toolbar.gif) no-repeat 0 -52px;}
.img-tracker{background:transparent url(imgs/toolbar.gif) no-repeat -288px 0;}
.img-tracker-light{background:transparent url(imgs/toolbar.gif) no-repeat -288px -32px;}

.img-salesforce{background:transparent url(imgs/toolbar.gif) no-repeat -736px 0;}
.img-salesforce-light{background:transparent url(imgs/toolbar.gif) no-repeat -736px -32px;}

.img-sap{background:transparent url(imgs/toolbar.gif) no-repeat -608px 0;}
.img-sap-light{background:transparent url(imgs/toolbar.gif) no-repeat -608px -32px;}

.ico-homedash{background:transparent url(imgs/toolbar.gif) no-repeat -528px -32px;width:16px;height:16px;vertical-align:middle;margin-left:6px;}
.dulltab .ico-homedash{background:transparent url(imgs/toolbar.gif) no-repeat -528px -48px;width:16px;height:16px;vertical-align:middle;margin-left:6px;}

.ico-setting{background:transparent url(imgs/toolbar.gif) no-repeat -480px 0;width:16px;height:16px;vertical-align:middle;margin-right:8px;}
.dulltab .ico-setting{background:transparent url(imgs/toolbar.gif) no-repeat -480px -16px;}

.ico-user{background:transparent url(imgs/toolbar.gif) no-repeat -496px 0;width:16px;height:16px;vertical-align:middle;margin-right:8px;}
.dulltab .ico-user{background:transparent url(imgs/toolbar.gif) no-repeat -496px -16px;}

.ico-report{background:transparent url(imgs/toolbar.gif) no-repeat -480px -32px;width:16px;height:16px;vertical-align:middle;margin-right:8px;}
.dulltab .ico-report{background:transparent url(imgs/toolbar.gif) no-repeat -480px -48px;}

.ico-helptopic{background:transparent url(imgs/toolbar.gif) no-repeat -496px -32px;width:16px;height:16px;vertical-align:middle;margin-right:8px;}
.dulltab .ico-helptopic{background:transparent url(imgs/toolbar.gif) no-repeat -496px -48px;}

.ico-salesforce{background:transparent url(imgs/toolbar.gif) no-repeat -720px 0;width:16px;height:16px;vertical-align:middle;margin-right:8px;}
.dulltab .ico-salesforce{background:transparent url(imgs/toolbar.gif) no-repeat -720px -16px;}

.phelpspot{z-index:500;position:absolute;top:0;left:0;width:auto;max-width:270px;}
.phelpspot_static{position:relative;}

.helpack{margin-top:8px;padding-top:4px;min-width:140px;cursor:pointer;}
.helpack button{box-shadow:none;}

.tiptitle{color:#ffffff;font-size:13px;font-weight:bold;margin-bottom:5px;}

.helpspot,.helpspot_static{border:solid 1px #333132;border-radius:2px;display:none;position:absolute;top:35px;padding:15px;background:#454142;color:#cdcdcd;}
.helpspot_static{width:200px;}
.helpanchor_static{width:10px;cursor:pointer;}

.lcarr{color:#EEA226;}
.chatmarch{padding:20px 0;width:100%;}

.msgraph-drive,.msgraph-folder,.msgraph-file,.msgraph-site{background:transparent url(imgs/msgraphicons.gif) no-repeat 0 0;width:16px;height:16px;vertical-align:middle;margin-right:4px;}
.msgraph-drive{background-position:0 -32px;}
.msgraph-folder{background-position:-16px -32px;}
.msgraph-file{background-position:-32px -32px;}
.msgraph-site{background-position:-48px -32px;}

.helppulse {
  width: 10px;
  height: 10px;
  border: 5px solid #D1E3E8;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  border-radius: 30px;
  background-color: #6BBBE8;
  position: absolute;
  top:10px;
  left:12px;
}

.helpdot {
  border: 10px solid #6BBBE8;
  background: transparent;
  -webkit-border-radius: 60px;
  -moz-border-radius: 60px;
  border-radius: 60px;
  height: 50px;
  width: 50px;
  -webkit-animation: pulse 2s ease-out;
  -moz-animation: pulse 2s ease-out;
  animation: pulse 2s ease-out;
  -webkit-animation-iteration-count: infinite;
  -moz-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
  position: absolute;
  top: -15px;
  left: -13px;
  opacity: 0;
}

@keyframes pulse {
 0% {
    transform: scale(0);
    opacity: 0.0;
 }
 25% {
    transform: scale(0);
    opacity: 0.1;
 }
 50% {
    transform: scale(0.1);
    opacity: 0.3;
 }
 75% {
    transform: scale(0.5);
    opacity: 0.5;
 }
 100% {
    transform: scale(1);
    opacity: 0.0;
 }
}

@-moz-keyframes pulse {
 0% {
    -moz-transform: scale(0);
    opacity: 0.0;
 }
 25% {
    -moz-transform: scale(0);
    opacity: 0.1;
 }
 50% {
    -moz-transform: scale(0.1);
    opacity: 0.3;
 }
 75% {
    -moz-transform: scale(0.5);
    opacity: 0.5;
 }
 100% {
    -moz-transform: scale(1);
    opacity: 0.0;
 }
}

@-webkit-keyframes "pulse" {
 0% {
    -webkit-transform: scale(0);
    opacity: 0.0;
 }
 25% {
    -webkit-transform: scale(0);
    opacity: 0.1;
 }
 50% {
    -webkit-transform: scale(0.1);
    opacity: 0.3;
 }
 75% {
    -webkit-transform: scale(0.5);
    opacity: 0.5;
 }
 100% {
    -webkit-transform: scale(1);
    opacity: 0.0;
 }
}

/* Editor preview styles (also change in tiny_mce/editor.css) */

.pickerstyle_headline{font-size:22px;color:#000044;}

.pickerstyle_narrowquote{padding:20px;color:#000044;}
.pickerstyle_narrowquote span{display:block;border-top:dashed 1px #31518E;border-bottom:dashed 1px #31518E;padding:10px 0;font-style:italic;}

.msgraphanchor,.msgraphanchored{cursor:pointer;display:inline-block;padding:1px 5px;opacity:0.6;border:solid 1px #dedede;border-radius:5px;margin-left:10px;font-size:12px;}
.msgraphanchor:hover{opacity:1;border-color:#848cf7;color:#848cf7;}

.msgraphanchored{opacity:1;background:#848cf7;color:#ffffff;}

/* ---------- */

.gridheader{background:#C4C4C4;}
.gridrow{padding:10px;color:#4a4a4a;border-bottom:solid 1px #CACACA;}
.gridrow.even{background:#F2F2F2;}
.gridheader .gridrow{font-weight:bold;}

.gridrow.warn, .legend.warn{color:#4A4831;background:#FFF9AC;border-color:#CAC9B8;}
.gridrow.evenwarn{color:#4A4831;background:#F2ECA3;border-color:#CAC588;}

.gridpager{padding:20px 0;}
.pageskipper{display:inline-block;padding:6px 8px;border:solid 1px #CACACA;color:#29ABE2;font-weight:bold;}
.img-pageleft{width:8px;height:16px;background:transparent url(imgs/toolbar.gif) no-repeat -512px 0;margin-right:6px;vertical-align:middle;}
.img-pageright{width:8px;height:16px;background:transparent url(imgs/toolbar.gif) no-repeat -520px 0;margin-left:6px;vertical-align:middle;}

.navgroup,.navgroupx{border:solid 1px #dedede;padding:10px;margin:0;margin-bottom:10px;}
.userdash .navgroupx{width:30%;margin-right:2%;float:left;max-width:400px;}	

.navtitle{text-transform:uppercase;margin-bottom:5px;font-size:14px;font-weight:bold;color:#333333;}

.multiand{border-color:#009966;-webkit-appearance: none;-moz-appearance: none;width:14px;height:14px;vertical-align:middle;}
.multiand:hover{border-color:#8f8cf7;}
.multiand:checked{background:transparent url(imgs/checkdot.gif) no-repeat center center;}

.neglabel{font-style:italic;font-size:14px;margin-bottom:5px;}
.neggroup{padding-left:20px;margin-bottom:10px;font-size:14px;}

.navfilter{margin-bottom:5px;}
.navfilter a, .navfilter a:hover, .navfilter a:visited, .navfilter a:link{text-decoration:none;color:#333333;}
.navfilter a:hover{text-decoration:underline;}
.navfilter em{color:#666666;font-size:14px;}

.navfilter .filterclear, .navfilter .filterclear:hover, .navfilter .filterclear:link, .navfilter .filterclear:visited{text-decoration:none;color:#ab0200;}
.filterclear, .filterclear:hover, .filterclear:link, .filterclear:visited{text-decoration:none;color:#ab0200;}
.navfilter .filterneg, .navfilter .filterneg:hover, .navfilter .filterneg:link, .navfilter .filterneg:visited{text-decoration:none;color:#ab0200;}

.filterneg acronym{border:none;text-decoration:none;}

.filterneg{display:nonea;}

.navopen, .navclose{text-align:center;margin-bottom:10px;background:#dedede;padding:2px 0;font-size:11px;}
.navopen{border-radius:0 0 10px 10px;}
.navopen a, .navclose a{display:block;color:#29ABE2;}

svg > g > g.google-visualization-tooltip { pointer-events: none }

.navchartview{float:left;width:32%;margin-right:1%;margin-bottom:20px;}

.navchartanchor{padding-bottom:45%;}
.navchart{position:absolute;top:0;left:0;width:100%;height:100%;background:#dedede;}

.navdash .navgroup{box-sizing:border-box;width:48%;margin-right:2%;float:left;max-height:200px;overflow:auto;}
.navdash .navgroupx{box-sizing:border-box;width:48%;margin-right:2%;float:left;margin-bottom:20px;}
.navdash .filterneg{display:inline;}
.navfilters{max-height:200px;overflow:auto;}

.mainsearchitem{margin-bottom:5px;border-bottom:solid 1px #cccccc;font-size:15px;}
.mainsearchitem:hover{background:#dedede;}
.mainsearchitem a{display:block;cursor:pointer;padding:5px;}

@media screen and (max-width:1080px){
	.dashbuttons .listitem{width:20%;}
}

@media screen and (max-width:1040px){
	.grid,.chatmarch{width:110%;}	
}

@media screen and (max-width:920px){
	.grid,.chatmarch{width:120%;}	
}

@media screen and (max-width:870px){
	.grid,.chatmarch{width:140%;}	
}	

@media screen and (max-width:860px){
	.dashbuttons .listitem{width:44%;}
}

@media screen and (max-width:790px){
	.grid,.chatmarch{width:160%;}	
}

@media screen and (max-width:740px){
	.grid,.chatmarch{width:200%;}	
}

@media screen and (max-width:710px){
	.dashbuttons .listitem{width:70%;}
}


@media screen and (max-width:660px){
	.grid,.chatmarch{width:240%;}	
}

@media screen and (max-width:650px){
	.navchartview{width:90%;margin-left:5%;margin-right:5%;float:none;}	
	.navchartanchor{padding-bottom:50%;}
	.navdash .navgroup,.navdash .navgroupx{width:90%;margin-left:1%;margin-right:1%;float:none;}	
}

/* ------------ */

<?php
$dark=isset($_GET['dark'])?intval($_GET['dark']):0;

if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php	
}

if ($dark==0||$dark==1){
?>

	.scal_view .scal_cell.dow_0,.scal_view .scal_cell.dow_6{background:#2C2935;color:#E87655;}

	#gsstickerview{background:rgba(53,41,46,0.9);color:#ffffff;border-color:#6A494C;}
	.sectiontitle a:hover .edithover{filter:invert(1);}
	
	.calledout{background:#35292E;}
	
	.charttoolbar{
		border:solid 1px #6B7247;
		background:#161B22;
		color:#C9D1D9;
		box-shadow:none;	
	}
	
	.tslogo{display:none;}
	.tsdark{display:inline;}
	
	.inpgood{background:#0B2313;color:#00FD00;border-color:#07650D;}
	.inpbad{background:#2C1115;color:#F89591;border-color:#69393A;}
	
	.recbad{color:#ff4444;}
	.recdim{font-style:italic;color:#aaaaaa;}	
	
	.dashcoltitle, .dashcolsubtitle{color:#000000;}
	.dashcolbutton{}		
	
	body,#lvviews{background:#0D1117;}
	input, textarea, select{background:#0D1117;color:#C2C3C5;border-color:#8B949E;}
	input:disabled{
		color:#BB8B2C;
		background-color:#353A2C;
		border-color:#6B7247;
	}
		
	#fsview{background:#0D1117;color:#C9D1D9;}

	.providerlogo_light{display:none;}
	.providerlogo_dark{display:inline !important;}
		
	#leftview_,#tabviews{border-color:#21262D;}
	
	#tabviews,#tabviews.bgready{background:#0D1117;color:#C9D1D9;box-shadow:none;}
	#tabviews.bgflash{background:#000000;color:#C9D1D9;box-shadow:none;}

	#leftview,#lkv.dismounted{background:#0D1117;color:#C9D1D9;}
	#leftview.bgready{background:#0D1117;color:#C9D1D9;}
	#leftview.bgflash{background:#000000;color:#C9D1D9;}
	

	
	#tabtitles{background-image:url(imgs/dtabbg.gif);}
	#statusinfo{border-color:#0D1117;background-color:#2D3239;color:#C9D1D9;}
	
	.activetab{box-shadow:0px -1px 2px #080A0C;}
	
	#lkvs{background-color:#000000;}
	#lkvc{background:#0D1117;}

	.listitem, .sortlistitem{border-bottom:solid 1px #21262D;}
	.listitem a,.listitem a:link,.listitem a:visited,.listitem a:hover{text-decoration:none;color:#C9D1D9;}
	.listitem:hover .diminished{color:#aaaade;}
		
	.qnavitem{box-shadow:none;background:#10233B;color:#68A7EA;border:solid 1px #294B70;}
	
	.gridheader{background:#161B22;}
	.gridrow{color:#C9D1D9;border-bottom:solid 1px #21262D;}
	.gridrow.even{background:#1F221A;}	

	.formlabel{color:#999999;}
	
	.warnbox{background:#35292E;border:solid 1px #6A494C;color:#E87655;border-radius:5px;}
	.reloader.busy{background:#35292E;border-bottom:solid 1px #6A494C;color:#E87655;}
	.listchanged{background:#35292E;border:solid 1px #6A494C;color:#E87655;}
	
	button, .button{box-shadow:0px 1px 2px #080A0C;border:solid 1px #388BFD;}
	button.warn, .button.warn{border:solid 1px #6A3438;}
	button.trivial, .button.trivial, .changebar_button{border:none;}
	button.disabled, .button.disabled,
	button.disabled:hover, .button.disabled:hover{background:#5A5F80;color:#161B22;border:none;cursor:default;}	
	
	.labelbutton{background:#1C2D38;color:#5DACC2;border:solid 1px #2F5464;border-radius:5px;padding:1px 6px;display:inline-block;transition:background 200ms,color 200ms,border-color 200ms;}
	.labelbutton:hover{background:#201746;color:#D7D8FE;border-color:#5C517D;}
	
	.changebar_view{background:rgba(41,22,28,0.9);border-bottom:solid 1px #6A3438;}
	.changebar_button{background-color:#353A2C;border-color:#6B7247;color:#EAD450;}
	.changebar_button:hover{background-color:#24271F;}
	.savebar_view{background:#0E2717;border-bottom:solid 1px #105F1A;color:#18D41B;}
	.autosaver{color:#6B7247;}
	
	.sectionheader{background:#161B22;border-top:solid 1px #161B22;border-bottom:solid 1px #161B22;color:#8B949E;}
	
	.codegenicon{display:none;}
	
	.ssrp_title{color:#97AED8;}
	.ssrp_link{color:#6B8B73;}
	.ssrp_desc{color:#B4B4B4;}
	
	
	.img-reports-light{background-position: -224px 0;}
	.img-chats-light{background-position: -320px 0;}
	.img-settings-light{background-position: -384px 0;}
	.img-helptopics-light{background-position: -576px 0;}
	.img-salesforce-light{background-position: -736px 0;}
	.img-sap-light{background-position: -608px 0;}
	
	.stable::-webkit-scrollbar-track {
		border-radius: 2px;
		background:#10233B url('iphone/ddarrow.png') no-repeat center center;
		background-size:16px 10px;
		border:solid 1px #6B7247;
	}
	 
	.stable::-webkit-scrollbar-thumb {background-color:rgba(107,114,71,0.3); border-radius:2px;}
	
	.daylightsaving{background:transparent url(imgs/sysicons.gif) no-repeat -52px -32px;}	
	#statusicons #speechstart img{background:transparent url(imgs/sysicons.gif) -16px 0;}
	.img-addrec{background:transparent url(imgs/sysicons.gif) no-repeat -32px -32px;}	
	#wsswarn{background:transparent url(imgs/sysicons.gif) 0 0;}
	#barcodewarn{background:transparent url(imgs/sysicons.gif) -48px 0;}
	#diagwarn{background:transparent url(imgs/sysicons.gif) -32px 0;}
	#sysreswarn{background:transparent url(imgs/sysicons.gif) -128px 0;}
	#lI01{background:transparent url(imgs/sysicons.gif) -80px -32px;}
	#imecree{background:transparent url(imgs/sysicons.gif) -96px -48px;}
	#gsnotesclip{background:transparent url(imgs/sysicons.gif) -112px 0;}
	
	#gamepadicon{background:transparent url(imgs/sysicons.gif) -160px -32px;}
	#gamepadicon.spotactive{background:transparent url(imgs/sysicons.gif) -160px -48px;}
	
	#chatindicator{background:transparent url(imgs/sysicons.gif) -96px -32px;}
	#chatindicator.offline{background-position:-112px -32px;}
	
	.img-pageleft{background:transparent url(imgs/sysicons.gif) no-repeat -66px 0;}
	.img-pageright{background:transparent url(imgs/sysicons.gif) no-repeat -86px 0;}
	
	.mceLayout{filter:brightness(0.85);transition:filter 500ms;}
	.mceLayout:hover{filter:brightness(1);}
	
	.hovlink, .hovlink:visited, .hovlink:hover, .hovlink:link,
	.listitem .hovlink, .listitem .hovlink:visited, .listitem .hovlink:hover, .listitem .hovlink:link, .recadder{color:#4AB8E6;transition:color 200ms;}
	.recadder:hover{color:#1C8EBE;}	
			
	.hovlink:focus, .listitem .hovlink:focus{color:#1C8EBE;transition:color 200ms;}
	.hovlink:hover, .listitem .hovlink:hover{border-bottom:solid 1px #1C8EBE;color:#1C8EBE;}
	
  
	.navgroup,.navgroupx{background:#2C3041;border-color:#545A7A;color:#A6BAF9;border-radius:5px;}
	.navtitle{color:#A6BAF9;}
	.navfilter a, .navfilter a:hover, .navfilter a:visited, .navfilter a:link{text-decoration:none;color:#C9D1D9;}
	.navfilter a:hover{text-decoration:underline;}
	.navfilter em{color:#AEB4BA;font-size:14px;}
		
	.navopen, .navclose{background:#2C3041;border-bottom:solid 1px #545A7A;}
	.navopen a, .navclose a{color:#A6BAF9;}

	.highcharts-background {
		fill: #0D1117;
	}
	
	.highcharts-title{fill:#A6BAF9 !important;}
	
	.highcharts-grid-line {
		stroke: #C9D1CD;
		stroke-opacity: 0.2;
	}
	
	.highcharts-label text{fill:#C9D1B4 !important; color:#C9D1B4 !important;font-weight:normal;}
	.highcharts-label text .highcharts-text-outline{stroke:transparent !important;}		
	
	.highcharts-color-0 {
		stroke: #294B70;
	}
	
	.highcharts-color-1 {
		stroke: #C9D1CF;
	}
	
	.highcharts-label.highcharts-tooltip{
		stroke: #0D1117;
	}
	
	.highcharts-button.highcharts-contextbutton .highcharts-button-box{
		stroke:#C9D1CF;
		fill:#0D1117;
	}
	.highcharts-contextmenu .highcharts-menu{background:#0D1117 !important;color:#C9D1CF !important;box-shadow:none !important;}
	.highcharts-contextmenu .highcharts-menu .highcharts-menu-item{color:#C9D1CF !important;}	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
	


