<?php

define ('GSSERVICE',1);

include 'lb.php';

if (!isset($_SERVER['HTTPS'])||($_SERVER['HTTPS']!='on'&&$_SERVER['HTTPS']!=1)){
	$usehttps=0;	
}

include 'connect.php';
include 'settings.php';
include 'xss.php';

xsscheck();	

include 'evict.php';
evict_check();


$cmd=$_GET['cmd'];

login(true);

include 'forminput.php';


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

try {  //comment out in older PHP versions
	
switch($cmd){
			
// Chat - gsb place holder

	case 'slv_codegen__chats': die('<div class="section">Commercial License Required</div>'); break;
	case 'setcanchat': die('commercial license required'); break;
	
//Yubi Key / NFC / Fingerprint

	case 'addyubikey': include 'icl/addyubikey.inc.php'; addyubikey(); break;
	case 'testyubikey': include 'icl/testyubikey.inc.php'; testyubikey(); break;
	case 'delyubikey': include 'icl/delyubikey.inc.php'; delyubikey(); break;
	case 'updateyubikeyname': include 'icl/updateyubikeyname.inc.php'; updateyubikeyname(); break;
	case 'setyubikeypassless': include 'icl/setyubikeypassless.inc.php'; setyubikeypassless(); break;
	
	case 'showwidedemo': include 'icl/showwidedemo.inc.php'; showwidedemo(); break;

//Settings
	case 'clogo': include 'icl/clogo.inc.php'; clogo(); break;
	case 'slv_core__settings': include 'icl/listsettings.inc.php'; listsettings(); break;

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
	case 'lookuptemplatevar': include 'icl/lookuptemplatevar.inc.php'; lookuptemplatevar(); break;
	case 'lookupstyles': include 'icl/lookupstyles.inc.php'; lookupstyles(); break;
	
	case 'listtemplatetypetemplates': include 'icl/listtemplatetypetemplates.inc.php'; listtemplatetypetemplates(); break;

	case 'imecree': include 'icl/imecree.inc.php'; imecree(); break;


//Blog Skeleton

	case 'lookupplugin': include 'icl/lookupplugin.inc.php'; lookupplugin(); break;
	case 'lookuppluginmention': include 'icl/lookuppluginmention.inc.php'; lookuppluginmention(); break;
	
	
	
//Reports & Audit
	case 'slv_core__reports': include 'icl/listreports.inc.php'; listreports(); break;
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

	
	default: apperror('unspecified interface:'.preg_replace('/[^A-Za-z0-9-_]/','',$cmd));
}

} catch (FaultException $e){ //comment out in older PHP versions
	logfault($e,true);		
} catch (Exception $e){
	logfault($e);
}
