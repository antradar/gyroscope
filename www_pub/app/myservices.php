<?php

define ('GSSERVICE',1);

include 'lb.php';

if (!isset($_SERVER['HTTPS'])||($_SERVER['HTTPS']!='on'&&$_SERVER['HTTPS']!=1)){
	$usehttps=0;	
}

include 'connect.php';
include 'settings.php';

include 'forminput.php';

include 'xss.php';

xsscheck();	

include 'evict.php';
evict_check();


$cmd=isset($_GET['cmd'])?$_GET['cmd']:'';

login(true);

include 'uiconfig.php';

//enforcing gs expiry

$gsbypass=array(
	'clogo','wk','pump','reauth',
	'slv_core__settings','slv_core__users','updategyroscope',
	'newuser','showuser','updateuser','deluser','downloadgskeyfile',
	'showcreditcards','addcreditcard','delcreditcard','setdefaultcreditcard',
	'showgssubscription');

$user=userinfo();
$gsexpiry=intval($user['gsexpiry']);
$now=time();

if ($gsexpiry!=0&&$gsexpiry<$now){
	//todo: bypass payment commands
	if (!in_array($cmd,$gsbypass)){
		header('HTTP/1.0 401 Forbidden');
		header('X-STATUS: 401');
		die();
	}
}

header(COLNAME_GSID.': '.($user['gsid'])); //uncomment for logging in nginx as $upstream_http_gsid
header('gsuid: '.($user['userid'])); //uncomment for logging in nginx as $upstream_http_gsuid
header('gsfunc: '.rawurlencode($cmd));
//header('Cache-Control: no-store'); //if an individual handler must return a cached content, add " header('Cache-Control: '); " inside the handler

if (isset($enable_gs_tracer)&&$enable_gs_tracer){
	include 'gyroscope_tracer.php';
}

$ratelimit_unit=1;
$ratelimit_units=array(
'embeduserprofileuploader'=>0, //do not block iframe sources
'kbman_embedmediauploader'=>0,
//'wk'=>3,
// 'slv_core__users'=>200,
//'clogo'=>200,
);

if (preg_match('/^embed/',$cmd)) $ratelimit_unit=0;
if (preg_match('/^pdf/',$cmd)) $ratelimit_unit=0;
if (preg_match('/^download/',$cmd)) $ratelimit_unit=0;


$gyroscope_timer_start=microtime(1);
if (isset($ratelimit_units[$cmd])) $ratelimit_unit=$ratelimit_units[$cmd];
if (is_callable('cache_ratelimit')) cache_ratelimit($ratelimit_unit,SYS_RESOURCE_CAP); //see lb.php

register_shutdown_function('gyroscope_shutdown'); //register AFTER the rate limit check

$ctx=null;

try {  //comment out in older PHP versions
	
switch($cmd){

	case 'dashvectorhelpsearch':
		if (file_exists('vectorhelp.ext.php')){
			include 'vectorhelp.ext.php';
			dashvectorhelpsearch();	
		}
	break;
	case 'vectorsearchhelp':
		if (file_exists('vectorhelp.ext.php')){
			include 'help/gethelptoc.inc.php';
			include 'vectorhelp.ext.php';
			vectorhelpsearch();	
		}
	break;
	
	//GS Replay	
	case 'gsreplay_fspreview': include 'icl/gsreplay_fspreview.inc.php'; gsreplay_fspreview(); break;
	case 'gsreplay_submit': include 'icl/gsreplay_submit.inc.php'; gsreplay_submit(); break;
	case 'gsreplay_submit_frame': include 'icl/gsreplay_submit_frame.inc.php'; gsreplay_submit_frame(); break;
	
	case 'slv_core__gsreplays': include 'icl/listgsreplays.inc.php'; listgsreplays(); break;
	case 'dash_core__gsreplays': include 'icl/dashgsreplays.inc.php'; dashgsreplays(); break;
	case 'showgsreplay': include 'icl/showgsreplay.inc.php'; showgsreplay(); break;
	case 'delgsreplay': include 'icl/delgsreplay.inc.php'; delgsreplay(); break;
	case 'updategsreplay': include 'icl/updategsreplay.inc.php'; updategsreplay(); break;
	case 'img_gsreplayframe': include 'icl/img_gsreplayframe.inc.php'; img_gsreplayframe(); break;		
	
	case 'lookupplugingsreplay': include 'icl/lookupplugingsreplay.inc.php'; lookupplugingsreplay(); break;
	case 'downloadgsreplay': include 'icl/downloadgsreplay.inc.php'; downloadgsreplay(); break;

	
//msgpipes

	case 'dashmsgpipes': include 'icl/dashmsgpipes.inc.php'; dashmsgpipes(); break;
	case 'addmsgpipe': include 'icl/addmsgpipe.inc.php'; addmsgpipe(); break;
	case 'delmsgpipe': include 'icl/delmsgpipe.inc.php'; delmsgpipe(); break;
	case 'addmsgpipeuser': include 'icl/addmsgpipeuser.inc.php'; addmsgpipeuser(); break;
	case 'delmsgpipeuser': include 'icl/delmsgpipeuser.inc.php'; delmsgpipeuser(); break;
	
//db query log

	case 'gsdb_showquerysummary': include 'icl/gsdb_showquerysummary.inc.php'; gsdb_showquerysummary(); break;
	case 'gsdb_showcmdqueries': include 'icl/gsdb_showcmdqueries.inc.php'; gsdb_showcmdqueries(); break;
	
// SAP Explorer

	case 'slv_codegen__sap': include 'icl/sap_listentitygroups.inc.php'; sap_listentitygroups(); break;
	case 'slv_codegen__sapentitysets': include 'icl/sap_listentitysets.inc.php'; sap_listentitysets(); break;
	case 'slv_codegen__sapentities': include 'icl/sap_listentities.inc.php'; sap_listentities(); break;
	case 'sap_showentity': include 'icl/sap_showentity.inc.php'; sap_showentity(); break;
	case 'sap_showrel': include 'icl/sap_showrel.inc.php'; sap_showrel(); break;
	
// toolbar_position:left wrappers
	case 'listwelcome': include 'icl/listwelcome.inc.php'; listwelcome(); break;
	case 'dash_default': include 'icl/dash_default.inc.php'; dash_default(); break;
	
	case 'lookupall': include 'icl/lookupall.inc.php'; lookupall(); break;
	
		
// Chat - gsb place holder

	case 'slv_codegen__chats': die('<div class="section">Commercial License Required</div>'); break;
	case 'setcanchat': die('commercial license required'); break;
	
//AI Bot Chats

	case 'slv_codegen__botchats': include 'icl/listbotchats.inc.php'; listbotchats(); break;
	case 'dash_codegen__botchats': include 'icl/dashbotchats.inc.php'; dashbotchats(); break;
	
	case 'dashbotchatmodels': include 'icl/botchat_dashmodels.inc.php'; botchat_dashmodels(); break;
	case 'botchat_setdefmodel': include 'icl/botchat_setdefmodel.inc.php'; botchat_setdefmodel(); break;
	
	case 'showbotchat': include 'icl/showbotchat.inc.php'; showbotchat(); break;
	case 'newbotchat': include 'icl/newbotchat.inc.php'; newbotchat(); break;
	case 'addbotchat': include 'icl/addbotchat.inc.php'; addbotchat(); break;
	case 'delbotchat': include 'icl/delbotchat.inc.php'; delbotchat(); break;
	case 'updatebotchat': include 'icl/updatebotchat.inc.php'; updatebotchat(); break;
	case 'updatebotchat_rectitle': include 'icl/updatebotchat_rectitle.inc.php'; updatebotchat_rectitle(); break;

	case 'botchat_resolve': include 'icl/botchat_resolve.inc.php'; botchat_resolve(); break;
	case 'addbotchatmsg': include 'icl/addbotchatmsg.inc.php'; addbotchatmsg(); break;
	case 'lookupbotchatfunc': include 'icl/lookupbotchatfunc.inc.php'; lookupbotchatfunc(); break;
	
	case 'lookupbotchat_sidereel_showsidereel': include 'icl/lookupbotchat_sidereel_showsidereel.inc.php'; lookupbotchat_sidereel_showsidereel(); break;
	case 'botchat_sidereel_showroute': include 'icl/botchat_sidereel_showroute.inc.php'; botchat_sidereel_showroute(); break;
	case 'botchat_sidereel_convert': include 'icl/botchat_sidereel_convert.inc.php'; botchat_sidereel_convert(); break;
	case 'botchat_sidereel_execute': include 'icl/botchat_sidereel_execute.inc.php'; botchat_sidereel_execute(); break;
		
// AI Knowledge Base

	case 'slv_core__kbman': include 'icl/kbman_listtopics.inc.php'; kbman_listtopics(); break;
		case 'dash_core__kbman': include 'icl/kbman_dashtopics.inc.php'; kbman_dashtopics(); break;
	case 'slv_core__kbmanrecs': include 'icl/kbman_listrecs.inc.php'; kbman_listrecs(); break;
		case 'dash_core__kbmanrecs': include 'icl/kbman_dashrecs.inc.php'; kbman_dashrecs(); break;
		
	case 'kbman_lookuptopicrec': include 'icl/kbman_lookuptopicrec.inc.php'; kbman_lookuptopicrec(); break;
	
	case 'kbman_summarize': include 'icl/kbman_summarize.inc.php'; kbman_summarize(); break;
	
	case 'kbman_newrec': include 'icl/kbman_newrec.inc.php'; kbman_newrec(); break;
	case 'kbman_addrec': include 'icl/kbman_addrec.inc.php'; kbman_addrec(); break;
	case 'kbman_delrec': include 'icl/kbman_delrec.inc.php'; kbman_delrec(); break;
	case 'kbman_showrec': include 'icl/kbman_showrec.inc.php'; kbman_showrec(); break;
	case 'kbman_updaterec': include 'icl/kbman_updaterec.inc.php'; kbman_updaterec(); break;
	
	case 'lookupkbmanrec': include 'icl/kbman_lookuprec.inc.php'; kbman_lookuprec(); break;
	case 'lookupkbmanchannel': include 'icl/lookupkbmanchannel.inc.php'; lookupkbmanchannel(); break;
	case 'kbman_addrecchannels': include 'icl/kbman_addrecchannels.inc.php'; kbman_addrecchannels(); break;
	case 'kbman_delrecchannel': include 'icl/kbman_delrecchannel.inc.php'; kbman_delrecchannel(); break;
	case 'kbman_adduserchannels': include 'icl/kbman_adduserchannels.inc.php'; kbman_adduserchannels(); break;
	case 'kbman_deluserchannel': include 'icl/kbman_deluserchannel.inc.php'; kbman_deluserchannel(); break;
	
	case 'kbman_addrelrec': include 'icl/kbman_addrelrec.inc.php'; kbman_addrelrec(); break;
	case 'kbman_delrelrec': include 'icl/kbman_delrelrec.inc.php'; kbman_delrelrec(); break;
	
	case 'kbman_showmedialibrary': include 'icl/kbman_showmedialibrary.inc.php'; kbman_showmedialibrary(); break;
	case 'kbman_embedmediauploader': include 'icl/kbman_embedmediauploader.inc.php'; kbman_embedmediauploader(); break;
	case 'kbman_delmedia': include 'icl/kbman_delmedia.inc.php'; kbman_delmedia(); break;
	case 'kbman_renamemedia': include 'icl/kbman_renamemedia.inc.php'; kbman_renamemedia(); break;
	case 'kbman_lookupimageoption': include 'icl/kbman_lookupimageoption.inc.php'; kbman_lookupimageoption(); break;
	case 'img_kbman_media': include 'icl/img_kbman_media.inc.php'; img_kbman_media(); break;
	
//Yubi Key / NFC / Fingerprint

	case 'addyubikey': include 'icl/addyubikey.inc.php'; addyubikey(); break;
	case 'testyubikey': include 'icl/testyubikey.inc.php'; testyubikey(); break;
	case 'delyubikey': include 'icl/delyubikey.inc.php'; delyubikey(); break;
	case 'updateyubikeyname': include 'icl/updateyubikeyname.inc.php'; updateyubikeyname(); break;
	case 'setyubikeypassless': include 'icl/setyubikeypassless.inc.php'; setyubikeypassless(); break;
	
	case 'showwidedemo': include 'icl/showwidedemo.inc.php'; showwidedemo(); break;

//Settings
	case 'clogo': include 'icl/clogo.inc.php'; clogo(); break;
	
	case 'setmyquicklist': include 'icl/setmyquicklist.inc.php'; setmyquicklist(); break;
	
	case 'slv_core__settings': include 'icl/listsettings.inc.php'; listsettings(); break;
	case 'dash_core__settings': include 'icl/dashsettings.inc.php'; dashsettings(); break;

	case 'slv_core__reportsettings': include 'icl/listreportsettings.inc.php'; listreportsettings(); break;
		
	case 'showaccount': include 'icl/showaccount.inc.php'; showaccount(); break;
	case 'setaccount': include 'icl/setaccountpass.inc.php'; setaccountpass(); break;
	
	case 'embeduserprofileuploader': include 'icl/embeduserprofileuploader.inc.php'; embeduserprofileuploader(); break;
	case 'imguserprofile': include 'icl/imguserprofile.inc.php'; imguserprofile(); break;
	case 'removeuserprofilepic': include 'icl/removeuserprofilepic.inc.php'; removeuserprofilepic(); break;
	case 'showuserprofile': include 'icl/showuserprofile.inc.php'; showuserprofile(); break;
	
	case 'imgqrcode': include 'icl/imgqrcode.inc.php'; imgqrcode(); break;
	case 'showgaqr': include 'icl/showgaqr.inc.php'; showgaqr(); break;
	case 'testgapin': include 'icl/testgapin.inc.php'; testgapin(); break;
	case 'resetgakey': include 'icl/resetgakey.inc.php'; resetgakey(); break;
  
	case 'slv_core__users': include 'icl/listusers.inc.php'; listusers(); break;
	case 'dash_core__users': include 'icl/dashusers.inc.php'; dashusers(); break;
	
	case 'showuser': include 'icl/showuser.inc.php'; showuser(); break;
	case 'newuser': include 'icl/newuser.inc.php'; newuser(); break;
	case 'adduser': include 'icl/adduser.inc.php'; adduser(); break;
	case 'deluser': include 'icl/deluser.inc.php'; deluser(); break;
	case 'updateuser': include 'icl/updateuser.inc.php'; updateuser(); break;
	case 'lookupuser': include 'icl/lookupuser.inc.php'; lookupuser(); break;
	case 'reauth': include 'icl/reauth.inc.php'; reauth(); break;
	
	case 'checkpass': include 'icl/checkpass.inc.php'; checkpass(); break;
	
	case 'downloadgskeyfile': include 'icl/downloadgskeyfile.inc.php'; downloadgskeyfile(); break;
	
	case 'slv_core__templatetypes': include 'icl/listtemplatetypes.inc.php'; listtemplatetypes(); break;
	case 'dash_core__templatetypes': include 'icl/dashtemplatetypes.inc.php'; dashtemplatetypes(); break;
	case 'autopicktemplate': include 'icl/autopicktemplate.inc.php'; autopicktemplate(); break;
	
	case 'rptsqlcomp': include 'icl/rptsqlcomp.inc.php'; rptsqlcomp(); break;  
	
	case 'rpttrace': include 'icl/needbingo.inc.php'; needbingo('Activity Summary'); break;
	case 'rptserverlog': include 'icl/needbingo.inc.php'; needbingo('Server Access Log'); break;
	case 'rptmxevents': include 'icl/needbingo.inc.php'; needbingo('Mail Server Log'); break;
	case 'showchatsettings': include 'icl/needbingo.inc.php'; needbingo('Chat Settings'); break;
	case 'lookupchatcustomer': include 'icl/stub.inc.php'; stub('lookupchatcustomer'); break;
	
	case 'installmods': include 'icl/installmods.inc.php'; installmods(); break;
	
	case 'showcreditcards': include 'icl/showcreditcards.inc.php'; showcreditcards(); break;
	case 'addcreditcard': include 'icl/addcreditcard.inc.php'; addcreditcard(); break;
	case 'delcreditcard': include 'icl/delcreditcard.inc.php'; delcreditcard(); break;
	case 'setdefaultcreditcard': include 'icl/setdefaultcreditcard.inc.php'; setdefaultcreditcard(); break;
	
	case 'showgssubscription': include 'icl/showgssubscription.inc.php'; showgssubscription(); break;
	
//Report Settings

	case 'slv_core__reportsettings': include 'icl/listreportsettings.inc.php'; listreportsettings(); break;
	case 'dash_core__reportsettings': include 'icl/dashreportsettings.inc.php'; dashreportsettings(); break;
	
	case 'rptcale': include 'icl/rptcale.inc.php'; rptcale(); break;
	case 'scal_rptcale_data': include 'icl/scal_rptcale_data.inc.php'; scal_rptcale_data(); break;
	
	case 'showreportsetting': include 'icl/showreportsetting.inc.php'; showreportsetting(); break;
	case 'newreportsetting': include 'icl/newreportsetting.inc.php'; newreportsetting(); break;
	case 'addreportsetting': include 'icl/addreportsetting.inc.php'; addreportsetting(); break;
	case 'delreportsetting': include 'icl/delreportsetting.inc.php'; delreportsetting(); break;
	case 'updatereportsetting': include 'icl/updatereportsetting.inc.php'; updatereportsetting(); break;
	

//Template Classes

	case 'slv_core__templatetypes': include 'icl/listtemplatetypes.inc.php'; listtemplatetypes(); break;
	case 'showtemplatetype': include 'icl/showtemplatetype.inc.php'; showtemplatetype(); break;
	case 'newtemplatetype': include 'icl/newtemplatetype.inc.php'; newtemplatetype(); break;
	case 'addtemplatetype': include 'icl/addtemplatetype.inc.php'; addtemplatetype(); break;
	case 'deltemplatetype': include 'icl/deltemplatetype.inc.php'; deltemplatetype(); break;
	case 'updatetemplatetype': include 'icl/updatetemplatetype.inc.php'; updatetemplatetype(); break;
	case 'updatetemplatetype_rectitle': include 'icl/updatetemplatetype_rectitle.inc.php'; updatetemplatetype_rectitle(); break;	
	case 'lookuptemplate': include 'icl/lookuptemplate.inc.php'; lookuptemplate(); break;
	case 'addtemplatevar': include 'icl/addtemplatevar.inc.php'; addtemplatevar(); break;
	case 'deltemplatevar': include 'icl/deltemplatevar.inc.php'; deltemplatevar(); break;
	case 'mceeditsource': include 'icl/mceeditsource.inc.php'; mceeditsource(); break;
	case 'batchsavetemplatevars': include 'icl/batchsavetemplatevars.inc.php'; batchsavetemplatevars(); break;
	
	
//Templates

	case 'showtemplate': include 'icl/showtemplate.inc.php'; showtemplate(); break;
	case 'newtemplate': include 'icl/newtemplate.inc.php'; newtemplate(); break;
	case 'addtemplate': include 'icl/addtemplate.inc.php'; addtemplate(); break;
	case 'deltemplate': include 'icl/deltemplate.inc.php'; deltemplate(); break;
	case 'updatetemplate': include 'icl/updatetemplate.inc.php'; updatetemplate(); break;
	case 'updatetemplate_rectitle': include 'icl/updatetemplate_rectitle.inc.php'; updatetemplate_rectitle(); break;
	case 'lookuptemplatevar': include 'icl/lookuptemplatevar.inc.php'; lookuptemplatevar(); break;
	case 'lookuptemplatevartoc': include 'icl/lookuptemplatevartoc.inc.php'; lookuptemplatevartoc(); break;
	case 'lookupstyles': include 'icl/lookupstyles.inc.php'; lookupstyles(); break;
	
	case 'listtemplatetypetemplates': include 'icl/listtemplatetypetemplates.inc.php'; listtemplatetypetemplates(); break;

	case 'imecree': include 'icl/imecree.inc.php'; imecree(); break;


//Blog Skeleton

	case 'lookupplugin': include 'icl/lookupplugin.inc.php'; lookupplugin(); break;
	case 'lookuppluginmention': include 'icl/lookuppluginmention.inc.php'; lookuppluginmention(); break;
	
	
	
//Reports & Audit
	case 'slv_core__reports': include 'icl/listreports.inc.php'; listreports(); break;
	case 'dash_core__reports': include 'icl/dashreports.inc.php'; dashreports(); break;
	
	case 'rptactionlog': include 'icl/rptactionlog.inc.php'; rptactionlog(); break;  
	case 'rptfaultlog': include 'icl/rptfaultlog.inc.php'; rptfaultlog(); break;  
	
	case 'ackhelpspot': include 'icl/ackhelpspot.inc.php'; ackhelpspot(); break;
	case 'resethelpspots': include 'icl/resethelpspots.inc.php'; resethelpspots(); break;

/*
// not available in Community Edition

//MS Graph

	case 'showmsfiles': include 'icl/showmsfiles.inc.php'; showmsfiles(); break;
	case 'ajxlistmsfiles': include 'icl/ajxlistmsfiles.inc.php'; ajxlistmsfiles(); break;
	case 'downloadmsfile': include 'icl/downloadmsfile.inc.php'; downloadmsfile(); break;
	case 'setmsgraphanchor': include 'icl/setmsgraphanchor.inc.php'; setmsgraphanchor(); break;
	case 'msgraphdisconnect': include 'icl/msgraphdisconnect.inc.php'; msgraphdisconnect(); break;
*/

	
//GSX Demo

	//case 'gsx_hello': include 'icl/gsx_hello.inc.php'; gsx_hello(); break; //uncomment this to see gsx in action
	
// svn merge boundary 80dd22a0883aaa1f8cd09b09e81bdd9b - 


// svn merge boundary bed99e5db57749f375e738c1c0258047 - 

	
  
//Codegen
	case 'codegen_makeform': include 'help/codegen_makeform.inc.php'; codegen_makeform(); break;
	case 'codegen_makecode': include 'help/codegen_makecode.inc.php'; codegen_makecode(); break;  
	
	case 'pkd': case 'showdatepicker': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
	case 'showtimepicker'; include 'icl/lookup.inc.php'; showtimepicker(); break;
	case 'pickdatemonths': include 'icl/lookup.inc.php'; pickdatemonths(); break;
	case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
	
	case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
	case 'addhomedashreport': include 'icl/addhomedashreport.inc.php'; addhomedashreport(); break;
	case 'delhomedashreport': include 'icl/delhomedashreport.inc.php'; delhomedashreport(); break;
	case 'listhomedashreports': include 'icl/listhomedashreports.inc.php'; listhomedashreports(); break;
	case 'sharehomedashreport': include 'icl/sharehomedashreport.inc.php'; sharehomedashreport(); break;
		
	case 'updategyroscope': include 'icl/updater.inc.php'; updategyroscope(); break;

//Help

	case 'slv_core__helptopics': include 'icl/listhelptopics.inc.php'; listhelptopics(); break;
	case 'dash_core__helptopics': include 'icl/dashhelptopics.inc.php'; dashhelptopics(); break;
	case 'edithelptopic': include 'icl/edithelptopic.inc.php'; edithelptopic(); break;
	case 'showhelptopic': include 'icl/showhelptopic.inc.php'; showhelptopic(); break;
	case 'safe_showhelptopic': include 'icl/safe_showhelptopic.inc.php'; safe_showhelptopic(); break;
	case 'newhelptopic': include 'icl/newhelptopic.inc.php'; newhelptopic(); break;
	case 'addhelptopic': include 'icl/addhelptopic.inc.php'; addhelptopic(); break;
	case 'delhelptopic': include 'icl/delhelptopic.inc.php'; delhelptopic(); break;
	case 'updatehelptopic': include 'icl/updatehelptopic.inc.php'; updatehelptopic(); break;
	case 'inchelptopiclevel': include 'icl/inchelptopiclevel.inc.php'; inchelptopiclevel(); break;
	case 'dechelptopiclevel': include 'icl/dechelptopiclevel.inc.php'; dechelptopiclevel(); break;
	case 'swaphelptopicpos': include 'icl/swaphelptopicpos.inc.php'; swaphelptopicpos(); break;

	
	default: 
	header('gsfunc: !invalid');
	$dcmd=preg_replace('/[^A-Za-z0-9-_]/','',$cmd);
	$dcmd=preg_replace('/(\w)/',"#$1",$dcmd);
	$cmd='';
	apperror('unspecified interface:'.$dcmd,null,null,$ctx);	
}

} catch (FaultException $e){ //comment out in older PHP versions
	logfault($e,true);		
} catch (Exception $e){
	logfault($e);
}

if (isset($enable_gs_tracer)&&$enable_gs_tracer){
	gyroscope_trace_dump();
}

function gyroscope_shutdown(){
	global $ratelimit_unit;
	global $cmd;
	global $gyroscope_timer_start;
	
	$ta=$gyroscope_timer_start;
	$tb=microtime(1);
	
	if (!isset($ratelimit_unit)) $ratelimit_unit=1;
	if (is_callable('cache_ratelimit_release')) {
		
		if ($cmd!=''){
			$memx=intval(memory_get_usage(1)/1024/1024);
			$time=round(($tb-$ta)*1000);
			$data=getrusage();
			$cputime=($data["ru_utime.tv_sec"] * 1000 + intval($data["ru_utime.tv_usec"] / 1000)) +
           		($data["ru_stime.tv_sec"] * 1000 + intval($data["ru_stime.tv_usec"] / 1000));
           
			cache_inc_entity_ver('metric_hit_'.$cmd);
			cache_inc_entity_ver('metric_memx_'.$cmd,$memx);
			cache_inc_entity_ver('metric_time_'.$cmd,$time);
			cache_inc_entity_ver('metric_cputime_'.$cmd,$cputime);
			
		}
		
		
		cache_ratelimit_release($ratelimit_unit);		
	}
}


