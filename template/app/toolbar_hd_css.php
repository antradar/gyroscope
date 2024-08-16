<?php
header('Content-Type: text/css');
?>
.admin-logout, .admin-settings, .admin-user, #speechstart img,
.beltprev, .beltnext, .daylightsaving, #wsswarn, #barcodewarn, #diagwarn, #imecree, #lI01, #gamepadicon, #gsnotesclip, #chatindicator, #chatindicator.offline, .img-up, .img-down,
#chaticon, #chaticon.offline,
.ico-setting, .dulltab .ico-setting, .ico-user, .dulltab .ico-user,
.ico-report, .dulltab .ico-report, .ico-homedash, .dulltab .ico-homedash,
.ico-helptopic, .dulltab .ico-helptopic,.ico-salesforce, .dulltab .ico-salesforce,
.img-addrec, .img-del, .img-save, .img-tick, .img-xls, .img-pdf, .img-speechrecog, .img-home, .img-gsclip,
.img-tracker,.img-tracker-light,.img-clock, .img-settings, .img-settings-light, .img-chats, .img-chats-light,
.img-helptopics, .img-helptopics-light,.img-salesforce, .img-salesforce-light, .img-sap, .img-sap-light,
.sectiontitle a:hover .edithover,
.img-closeall, .img-speechrecog, .img-reports, .img-reports-light, .img-xls, .img-pdf,
.img-pageleft, .img-pageright, .img-search, #tabexpander, #tabexpander.afloat
{background-image:url(imgs/toolbar_hd.gif);background-size:768px 64px;}	

.msgraph-drive,.msgraph-folder,.msgraph-file,.msgraph-site{background-size:48px 32px;}
.msgraph-drive{background-position:0 0;}
.msgraph-folder{background-position:-16px 0;}
.msgraph-file{background-position:-32px 0;}
.msgraph-site{background-position:-32px -16px;}

.hourglass{height:6px;}

/*
.listsearch_ input{display:block;}
.listsearch .searchsubmit{opacity:0;visibility:hidden;}
.listsearch{background:transparent url(imgs/bmg.gif) no-repeat 100% 5px;background-size:16px 16px;}
*/

/*
#statusinfo{background:#BBCDD5 url(imgs/gshd.png) no-repeat top right;background-size:150px 24px;}
*/

.sectionheader.open{background:#dedede url(imgs/title_open_hd.gif) no-repeat 0 50%;}
.sectionheader.close{background:#dedede url(imgs/title_close_hd.gif) no-repeat 0 50%;}
.sectionheader.open, .sectionheader.close{background-size:16px 16px;}

/*
#applogo img{visibility:hidden;}
#applogo{background:transparent url(imgs/clogo_hd.gif) no-repeat 0 0; background-size:157px 56px;}
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

	.daylightsaving, #wsswarn, #barcodewarn, #diagwarn, #sysreswarn, #imecree, #lI01, 
	#gamepadicon, #gsnotesclip, #chatindicator, #chatindicator.offline,
	.img-addrec, .img-speechrecog,
	.img-speechrecog,
	.img-pageleft, .img-pageright
	{background-image:url(imgs/sysicons_hd.gif);background-size:240px 64px;}
<?php	

}//if dark==0||dark==1


	
if ($dark==0){
?>
}
<?php	
}
