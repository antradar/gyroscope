<?php
header('Content-Type: text/css');
?>
body{background:#f2f2f2;margin:0;padding:0;}
body,textarea,td, .img-mg{font-family:verdana, tahoma, arial;font-size:13px;-webkit-text-size-adjust:none;}

.img-mg,.sectiontitle,
.listitem,.inplong, .inp, .inpmed, .inpshort, .inpxshort{font-family:Arial,serif;}

.list_to_dash, .dash_to_list{display:none !important;}

.wideview{}
.wideviewmenu_{}
.wideviewmenu{display:none;}
.wideviewmenu.transpose{display:block;padding:10px;}
.wideview .qnav_{display:block;}

a{cursor:pointer;}
acronym{text-decoration:none;border:none;}

.inpback{height:14px;position:absolute;top:5px;right:24px;cursor:pointer;}
.kpwlink{display:none;}

.medialibtile{float:left;width:24%;margin-right:1%;position:relative;overflow:hidden;margin-bottom:10px;}

#mmastersearch{display:none;position:absolute;top:0;left:0;width:100%;height:50px;background:#484848;}
.mastersearchshell{padding:10px;padding-left:40px;}
#msearchcloser{position:absolute;top:15px;left:10px;}
#mastersearch{font-size:15px;border:solid 1px #5F5C5C;border-radius:6px;line-height:28px;height:30px;box-sizing:border-box;color:#ffffff;outline:0;background:#0D1117 url(../imgs/mg_dark.png) no-repeat 6px 50%;width:100%;padding-left:36px;}
#mastersearch.expanded{background:#ffffff url(../imgs/mg_light.png) no-repeat 6px 50%;color:#000000;}

#mainsearchview_{width:100%;position:fixed;top:45px;left:0;z-index:2010;display:none;opacity:0;transition:opacity 100ms;}
#mainsearchview{margin-left:44px;margin-right:14px;background:#ffffff;padding:10px;border:solid 1px #dedede;box-shadow:0 2px 4px #000000;max-height:200px;overflow:auto;border-radius:0 0 5px 5px;}

button, .button{white-space:nowrap;margin-top:5px;color:#ffffff;background:#187CA6;padding:5px 15px;border-radius:3px;border:none;cursor:pointer;box-shadow:0px 1px 2px #c9c9c9;-webkit-appearance: none;}
button:focus, .button:focus{background:#29ABE1;}
button.warn, .button.warn{background:#881818;}
button.disabled, .button.disabled{background:#dedede;box-shadow:none;}
button.trivial{background:none;color:#666666;box-shadow:none;font-weight:bold;padding-left:0;}
button.trivial:active{box-shadow:none;}
button.disabled:active, .button.disabled:active{box-shadow:none;}
button.trivial{background:none;color:#666666;box-shadow:none;font-weight:bold;padding-left:0;}
button.trivial:active{box-shadow:none;}

.button, .button:hover, .button:visited, .button:link{text-decoration:none;}

button:hover, .button:hover{background:#29ABE1;}
button.warn:hover, .button.warn:hover{background:#971B2F;}
button.disabled:hover, .button.disabled:hover{background:#dedede;}
button.trivial:hover, .button.trivial:hover{background:none;}

.phelpspot button, .phelpspot .button{border:solid 1px #999999;}

#lvviews, #tabviews{background:#ffffff;}

.welcometile img{width:32px;height:32px;}
.recadder img{width:18px;height:18px;}

.changebar{display:none;z-index:1100;}
.changebar_anchor{position:relative;z-index:1000;top:-5px;}
.changebar_view{position:fixed;background:rgba(255,220,193,0.9);width:100%;}
.changebar_content{padding:0 235px 5px 5px;text-align:center;}
.changebar_shadow{visibility:hidden;display:none;}
.changebar_content button{font-size:12px;padding:3px 6px;}

.changebar_button{background:#9D620D;}
.changebar_button:hover{background:#EEA226;}

.autosaver{color:#444444;}
.autosavekiller{display:inline-block;background:#ab0200;padding:0 4px;vertical-align:middle;border-radius:20px;color:#ffffff;font-weight:bold;margin-left:5px;}

.savebar{display:none;z-index:1200;}
.savebar_anchor{position:relative;z-index:1100;top:-5px;}
.savebar_view{position:fixed;background:#CEFFC7;width:100%;}
.savebar_content{padding:10px 235px 10px 5px;text-align:center;}


/* Tabs */
.tabclose, .noclose{display:block;float:left;height:24px;width:30px;cursor:pointer;}
.noclose{width:8px;}
.activetab a, .dulltab a{float:left;}
.tt{min-width:30px;padding-top:5px;font-size:12px;margin-left:5px;padding-left:3px;padding-right:3px;white-space:nowrap;max-width:70px;overflow:hidden;}
.activetab .tabclose{background:transparent url(msprite.png) no-repeat -369px -130px;}
.dulltab .tabclose{background:transparent url(msprite.png) no-repeat -333px -130px;}
.activetab, .dulltab {font-weight:bold;display:block;float:left;height:24px;margin-right:4px;}
.activetab{color:#000000;background:transparent url(msprite.png) no-repeat 0 -32px;}
.dulltab{color:#000000;background:transparent url(msprite.png) no-repeat 0 -62px;}

.activetab .noclose{background:transparent url(msprite.png) no-repeat -283px -130px;}
.dulltab .noclose{background:transparent url(msprite.png) no-repeat -266px -130px;}

.section{padding:10px;}
.section.hasqnav{padding-left:35px;}

.qnav_{position:fixed;left:0;top:0px;height:100%;overflow:hidden;}
.qnav{padding-top:160px;}

.qnavitem{clear:both;float:left;font-weight:bold;margin-bottom:10px;font-size:12px;background:#848cf7;color:#ffffff;padding:2px 5px;border-radius:0 5px 5px 0;box-shadow:0 2px 4px #999999;display:block;overflow:hidden;white-space:nowrap;}
.qnavitem:hover{font-weight:normal;}
.qnavitem b{display:none;font-weight:normal;}
.qnavitem:hover b{display:inline;}

.enl_date{border-bottom:solid 1px #804000;color:#201000;}
.enl_client{border-bottom:solid 1px #400080;color:#100020;}

.form div div{
height:22px;
}

.safetable td,.safetable th{float:left;display:block;margin-right:2px;width:120px;overflow:hidden;}
.safetable tr{clear:both;border-bottom:solid 1px #dedede;display:block;}

#fsmask{z-index:3001;position:fixed;top:0;left:0;width:100%;height:100%;background:#000000;opacity:0.4;filter:alpha(opacity=40);display:none;}
#fstitlebar{z-index:3002;position:fixed;top:10px;left:10px;width:100%;height:30px;background:#555555;border-radius:3px 3px 0 0;display:none;}
#fstitle{color:#ffffff;padding:5px 10px;font-size:15px;font-weight:bold;}
#fsclose{display:block;width:24px;height:18px;position:absolute;top:6px;right:6px;background:#971B2F;border:solid 1px #888888;}
#fsclose:hover{border-color:#ffffff;}
#fsclose .img-closeall{margin:4px 6px;}
#fsview{z-index:3002;position:fixed;top:40px;left:10px;width:100%;background:#ffffff;display:none;overflow:auto;}

.lksel{position:absolute;top:5px;right:5px;}
#lkv{position:fixed;width:210px;top:95px;left:-230px;border-radius:4px 4px 0 0;}
#lkvtitle{background:transparent url(msprite.png) no-repeat 0 -130px;height:24px;position:relative;}
	#lkvtitle a{font-weight:bold;font-size:14px;color:white;margin-left:30px;margin-right:30px;padding-top:6px;display:block;}
	#lkvx{position:absolute;top:0;right:0;cursor:pointer;background:transparent url(msprite.png) no-repeat -298px -130px;}	
	#lkvc{border-right:solid 1px #dedede;overflow:auto;background-color:#ffffff;}

#lkv.dismounted{z-index:3010;border:none;}
#lkv.dismounted #lkvtitle{background:#187CA6;border-radius:4px 4px 0 0;overflow:hidden;}
#lkv.dismounted #lkvx{background:transparent url(msprite.png) no-repeat -296px -130px;}
#lkv.dismounted #lkvc{border:solid 1px #666666;border-top:none;box-shadow:0 2px 4px #000000;}
	
.warnbox{padding:10px;margin-bottom:10px;background:#ffdede;line-height:1.5em;}
.infobox{padding:10px 0;font-size:13px;font-style:italic;color:#666666;line-height:1.5em;}
	
#toolicons{height:40px;text-align:right;}

#toollist{height:35px;}
#backlist{top:40px;}
#toolbg{height:40px;}
#backlistshadow{height:43px;}
.img-calel{background:transparent url(msprite.png) no-repeat -69px -114px;width:5px;height:12px;} /* 5x12 */
.img-caler{background:transparent url(msprite.png) no-repeat -92px -114px;width:5px;height:12px;}

.listsearch{padding:0;margin:0;position:relative;border:solid 1px #dedede;}
.listsearch_{padding-right:20px;}
.img-mg{height:24px;line-height:18px;border:none;width:95%;padding-left:5px;}
.img-mg:active, .img-mg:focus{outline:0;}
.mediaimg-mg{border-color:#cccccc;background:#f2f2f2 url(../imgs/sprite.png) no-repeat 100% -330px;padding-left:5px;padding-right:25px;margin-bottom:8px;height:32px;line-height:30px;vertical-align:middle;width:50%;} /* 12x12 +14 */

.searchsubmit{position:absolute;top:4px;right:2px;border:none;display:block;}

.recadder{color:#187CA6;margin-bottom:5px;font-size:14px;}
.recadder:hover{color:#29ABE1;}
.recadder img{vertical-align:middle;margin-right:4px;}
.img-help{background:transparent url(msprite.png) no-repeat -265px -8px;width:12px;height:12px;}
.img-print{border:none;background:transparent url(msprite.png) no-repeat 0 -112px;width:16px;height:16px;vertical-align:middle;} /* 16x16 */

.img-exit{background:transparent url(msprite.png) no-repeat -198px -7px;} /* 16x16 */

.hourglass{margin:10px;}
#statusinfo .hourglass{margin:0;}

#backlist{height:43px;background:transparent url(hbg.png) repeat-x top left;}
#backlistbutton{display:block;padding-left:5px;padding-top:6px;}

#tabtitles{background:#F2F2F2 url(tbbg.gif) repeat top left;}

#tooltitle a{font-weight:bold;font-size:13px;color:white;margin-left:10px;padding-top:3px;display:block;white-space:nowrap;cursor:default;}
.sectiontitle{font-weight:bold;font-size:14px;margin-bottom:10px;}
.sectiontitle a .edithover{display:inline-block;width:16px;height:16px;margin-left:0;background:transparent url(../imgs/toolbar.gif) no-repeat -704px 0;}


input{border:solid 1px #666666;}
.sectionheader{font-size:12px;font-weight:bold;background-color:#dedede;margin-top:12px;margin-bottom:10px;padding:3px 10px;}
#statusbar a{font-size:12px;display:block;margin-top:2px;margin-left:2px;}
.minicaletitle{
position:relative;
text-align:center;
padding-left:20px;
padding-right:20px;
width:135px;
margin-top:5px;
margin-bottom:5px;
}
.minicaletitle span{
}
.minicaleprev{
position:absolute;
display:block;
top:0px;
left:5px;
}
.minicalenext{
position:absolute;
display:block;
top:0px;
right:5px;
}

.iconbuttons img{
border:none;
}

.iconbuttons a{
display:block;
float:left;
width:32px;
height:32px;
margin-right:15px;
}

.iconbuttons a:hover{
margin-right:13px;
border:solid 1px #664444;
}
#logoutlink{
position:absolute;right:20px;top:14px;
}

#logoutlink a{
text-decoration:none;
}

#logoutlink a:hover{
color:#ff2222;
}

.welcometile{margin-bottom:15px;}

.sortlistitem{margin-bottom:5px;border-left:solid 10px #D8D8D8;padding:12px 10px;border-right:solid 10px #D8D8D8;}
.sortlistitem.src{
   border-color:#D1E3E8;
  -webkit-animation: dragpulse 2s ease-out;
  -moz-animation: dragpulse 2s ease-out;
  animation: dragpulse 2s ease-out;
  -webkit-animation-iteration-count: infinite;
  -moz-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
}
.sortlistitem.dst{
  border-color:#D1E3E8;
  -webkit-animation: dragpulse 2s ease-out;
  -moz-animation: dragpulse 2s ease-out;
  animation: dragpulse 2s ease-out;
  -webkit-animation-iteration-count: infinite;
  -moz-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
}

@keyframes dragpulse {
 0% {border-color:#6BBBE8;}
 25% {border-color:#D1E3E8;}
 75% {border-color:#6BBBE8;}
}

@-moz-keyframes dragpulse {
 0% {border-color:#6BBBE8;}
 25% {border-color:#D1E3E8;}
 75% {border-color:#6BBBE8;}
}

@-webkit-keyframes "dragpulse" {
 0% {border-color:#6BBBE8;}
 25% {border-color:#D1E3E8;}
 75% {border-color:#6BBBE8;}
}

.sortlistitem_{padding:14px 10px;background:#F1F2F4;}
.sortlistitemdelete{margin-bottom:5px;padding: 14px 10px 13px 10px;margin-left:-4px;background:#F1F2F4;}

.litm{border-bottom:solid 1px #A6A8AA;background:transparent url(ra.png) no-repeat right center;}
.litm a{display:block}

.labelbutton{font-size:11px;background-color:#444444;padding:1px 4px;border-radius:2px;color:#ffffff;white-space:nowrap;}
.labelbutton:hover{background:#000040;}
.labelbutton:focus{background:#ab0200;}

.sectionheader .labelbutton{font-weight:normal;}
.labelbutton, .labelbutton:visited, .labelbutton:hover, .labelbutton:link{text-decoration:none;}

.minilookup{width:200px;font-size:11px;display:none;position:relative;padding-top:16px;}
.lookupview{height:200px;width:200px;overflow:auto;border:solid 1px #666666;box-shadow:2px 2px 4px #666666;}
.lookupview #timepicker{height:200px;overflow:auto;}
.minilookup .closer{position:absolute;top:3px;right:20px;}
.minilookup .listitem {background:none;}
.minilookup .listitem a{padding:3px 0;margin:0;font-size:11px;display:inline;}

.clear{clear:both;}
.col{width:300px;margin-right:20px;float:left;}
.majorcol, .minorcol, .rmajorcol, .rminorcol{float:left;}
.majorcol{width:580px;}
.minorcol{width:380px;margin-right:20px;}
.rmajorcol{width:580px;margin-right:20px;}
.rminorcol{width:380px;}

.formlabel{color:#444444;padding-right:5px;margin-bottom:3px;padding-top:5px;}
.inplong, .inp, .inpmed, .inpshort, .inpxshort{padding-left:2px;line-height:28px;height:30px;-webkit-appearance: none;border-radius:0;}
.inplong{width:200px;}
.inp{width:180px;}
.inpmed{width:120px;}
.inpshort{width:80px;}
.inpxshort{width:40px;}

.inplong.num, .inp.num, .inpmed.num, .inpshort.num, .inpxshort.num{padding-left:0;padding-right:2px;text-align:right;}
.inputrow{margin-bottom:10px;}

textarea.inplong, textarea.inp, textarea.inpmed{height:150px;}
select.inp, select.inplong, select.inpmed, select.inpshort, select.inpxshort{width:auto;}

.subtable .inplong, .subtable .inp, .subtable .inpmed, .subtable .inpshort, .subtable .inpxshort{margin-top:2px;}
.subtable button{white-space:nowrap;}

.listbar{font-size:12px;padding:5px 0;}

.mtback_dark{display:none;}

input:disabled{color:#000000;-webkit-appearance: none;}

.stable{overflow:auto;}
.stable td{padding:0 10px;font-size:11px;}

.stable::-webkit-scrollbar {height:12px;}
 
.stable::-webkit-scrollbar-track {
	border-radius: 2px;
	background:#fcfcfc url('darrow.png') no-repeat center center;
	background-size:16px 10px;
}
 
.stable::-webkit-scrollbar-thumb { background-color:rgba(200,200,200,0.2); border-radius:2px;}

.reloader{text-align:center;padding:8px 10px;background:#dedede;color:#444444;margin-bottom:5px;}
.reloader a{display:block;}

.listsearch_ input{display:block;}
.listsearch .searchsubmit{opacity:0.1;}
.listsearch{background:transparent url(../imgs/bmg.gif) no-repeat 100% 5px;background-size:16px 16px;}

@media screen and (max-width:1330px){
	.col{width:49%;margin-right:1%;}
	.minorcol{width:38%;margin-right:2%;}
	.majorcol{width:60%;}
	.rmajorcol{width:60%;margin-right:2%;}
	.rminorcol{width:40%;}	
}

@media screen and (max-width:990px){
	.col, .majorcol, .minorcol, .rmajorcol, .rminorcol{width:auto;float:none;margin-bottom:10px;}	
}

@media screen and (max-width:940px){
	.medialibtile{width:24%;}
}

@media screen and (max-width:720px){
	.medialibtile{width:32%;}
}

@media screen and (max-width:600px){
	.changebar_content{padding:5px;padding-top:0;}
}

@media screen and (max-width:480px){
	.mceToolbar .mce_image, .mceToolbar .mceSeparator, .mceToolbar .mce_code, 
	.mceToolbar .mce_link, .mceToolbar .mce_unlink{display:none !important;}
	.medialibtile{width:49%;}
	.mediaimg-mg{width:80%;}
}

@media screen and (max-width:370px){
	.mceToolbar .mceOpen, .mceToolbar .mceTitle, .mceToolbar .mce_blockquote, 
	.mceToolbar .mce_indent, .mceToolbar .mce_outdent, .mceToolbar .mce_forecolor, .mceToolbar .mce_backcolor{display:none !important;}
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

	table{color:#C9D1D9;}
	.reloader{background:#353A2C;color:#EAE661;border-bottom:solid 1px #6B7247;border-top:solid 1px #6B7247;}
	#backlist{background-image:url(dhbg.png);}
	.mtback_light{display:none;}
	.mtback_dark{display:inline;}

	#mainsearchview{background:#2F3235;border-color:#3A3A3A;color:#dedede;}
	.mainsearchitem a{border-color:#666666;}
	.mainsearchitem a:hover{background:#3A3A3A;}	
		
	.activetab .tabclose,
	.dulltab .tabclose,
	.activetab, .dulltab,
	.activetab .noclose, .dulltab .noclose,
	#lkvtitle, #lkvx
	{background-image:url(dmsprite.png)}
	
	.listsearch{background-image:url(../imgs/dbmg.gif);}
	
	#lkvc{border-right:solid 1px #2F5464;}

	
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
