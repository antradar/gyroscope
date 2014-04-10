<?
include 'lb.php';

include 'connect.php';
include 'auth.php';
include 'settings.php';
include 'xss.php';

xsscheck();	

include 'evict.php';
evict_check();

login(true); //silent mode

$cmd=$_GET['cmd'];

include 'forminput.php';

switch($cmd){

	
//Accounts
  case 'showaccount': include 'icl/showaccount.inc.php'; showaccount(); break;
  case 'setaccount': include 'icl/setaccountpass.inc.php'; setaccountpass(); break;
	
//Reports & Audit
  case 'slv0': include 'icl/listreports.inc.php'; listreports(); break;
  case 'rptactionlog': include 'icl/rptactionlog.inc.php'; rptactionlog(); break;  
  
//Codegen
  case 'codegen_makeform': include 'help/codegen_makeform.inc.php'; codegen_makeform(); break;
  case 'codegen_makecode': include 'help/codegen_makecode.inc.php'; codegen_makecode(); break;  
  
  

  case 'resetsandbox': include 'icl/resetsandbox.inc.php'; resetsandbox(); break;
  case 'pkd': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
  case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
  
  case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
  case 'updategyroscope': include 'icl/updater.inc.php'; updategyroscope(); break;
  case 'showhelp': include 'icl/showhelp.inc.php'; showhelp(); break;
    
  default: echo 'unspecified interface:'.$cmd;
}
