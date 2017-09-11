<?php

define ('GSSERVICE',1);

include 'lb.php';

include 'connect.php';
include 'settings.php';
include 'xss.php';

xsscheck();	

include 'evict.php';
evict_check();

login(true);

$cmd=$_GET['cmd'];

include 'forminput.php';


switch($cmd){

//Settings
	case 'slv_core__settings': include 'icl/listsettings.inc.php'; listsettings(); break;

	case 'slv_core__reportsettings': include 'icl/listreportsettings.inc.php'; listreportsettings(); break;
		
	case 'showaccount': include 'icl/showaccount.inc.php'; showaccount(); break;
	case 'setaccount': include 'icl/setaccountpass.inc.php'; setaccountpass(); break;	
  
	case 'slv_core__users': include 'icl/listusers.inc.php'; listusers(); break;
	case 'showuser': include 'icl/showuser.inc.php'; showuser(); break;
	case 'newuser': include 'icl/newuser.inc.php'; newuser(); break;
	case 'adduser': include 'icl/adduser.inc.php'; adduser(); break;
	case 'deluser': include 'icl/deluser.inc.php'; deluser(); break;
	case 'updateuser': include 'icl/updateuser.inc.php'; updateuser(); break;
	case 'reauth': include 'icl/reauth.inc.php'; reauth(); break;
	
	case 'downloadgskeyfile': include 'icl/downloadgskeyfile.inc.php'; downloadgskeyfile(); break;
	
	case 'slv_core__templatetypes': include 'icl/listtemplatetypes.inc.php'; listtemplatetypes(); break;
	
	case 'rptsqlcomp': include 'icl/rptsqlcomp.inc.php'; rptsqlcomp(); break;  
	
	case 'installmods': include 'icl/installmods.inc.php'; installmods(); break;
	

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

	case 'slv_core__templates': include 'icl/listtemplates.inc.php'; listtemplates(); break;
	case 'showtemplate': include 'icl/showtemplate.inc.php'; showtemplate(); break;
	case 'newtemplate': include 'icl/newtemplate.inc.php'; newtemplate(); break;
	case 'addtemplate': include 'icl/addtemplate.inc.php'; addtemplate(); break;
	case 'deltemplate': include 'icl/deltemplate.inc.php'; deltemplate(); break;
	case 'updatetemplate': include 'icl/updatetemplate.inc.php'; updatetemplate(); break;
	case 'lookuptemplatevar': include 'icl/lookuptemplatevar.inc.php'; lookuptemplatevar(); break;
	case 'lookupstyles': include 'icl/lookupstyles.inc.php'; lookupstyles(); break;
	
	case 'listtemplatetypetemplates': include 'icl/listtemplatetypetemplates.inc.php'; listtemplatetypetemplates(); break;


//Blog Skeleton

	case 'lookupplugin': include 'icl/lookupplugin.inc.php'; lookupplugin(); break;
	case 'lookuppluginmention': include 'icl/lookuppluginmention.inc.php'; lookuppluginmention(); break;
	
	
	
//Reports & Audit
	case 'slv_core__reports': include 'icl/listreports.inc.php'; listreports(); break;
	case 'rptactionlog': include 'icl/rptactionlog.inc.php'; rptactionlog(); break;  	

			
// svn merge boundary 80dd22a0883aaa1f8cd09b09e81bdd9b - 


// svn merge boundary bed99e5db57749f375e738c1c0258047 - 

	
  
//Codegen
	case 'codegen_makeform': include 'help/codegen_makeform.inc.php'; codegen_makeform(); break;
	case 'codegen_makecode': include 'help/codegen_makecode.inc.php'; codegen_makecode(); break;  
	
	case 'pkd': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
	case 'showtimepicker'; include 'icl/lookup.inc.php'; showtimepicker(); break;
	case 'pickdatemonths': include 'icl/lookup.inc.php'; pickdatemonths(); break;
	case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
	
	case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
	case 'updategyroscope': include 'icl/updater.inc.php'; updategyroscope(); break;
	
	default: apperror('unspecified interface:'.$cmd);
}
