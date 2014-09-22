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
  	
//Actors
  case 'slv1': include 'icl/listactors.inc.php'; listactors(); break;
  case 'showactor': include 'icl/showactor.inc.php'; showactor(); break;
  case 'newactor': include 'icl/newactor.inc.php'; newactor(); break;
  case 'addactor': include 'icl/addactor.inc.php'; addactor(); break;
  case 'updateactor': include 'icl/updateactor.inc.php'; updateactor(); break;

//Films
  case 'slv2': include 'icl/listfilms.inc.php'; listfilms(); break;
  case 'showfilm': include 'icl/showfilm.inc.php'; showfilm(); break;
  case 'listactorfilms': include 'icl/listactorfilms.inc.php'; listactorfilms(); break;
  case 'addfilmactor': include 'icl/addfilmactor.inc.php'; addfilmactor(); break;
  case 'delfilmactor': include 'icl/delfilmactor.inc.php'; delfilmactor(); break;
  case 'updatefilm': include 'icl/updatefilm.inc.php'; updatefilm(); break;
  case 'delfilm': include 'icl/delfilm.inc.php'; delfilm(); break;

  
//Lookups
  case 'lookuplanguage': include 'icl/lookuplanguage.inc.php'; lookuplanguage(); break;
  case 'lookupactor': include 'icl/lookupactor.inc.php'; lookupactor(); break;
    
  case 'pkd': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
  case 'showtimepicker'; include 'icl/lookup.inc.php'; showtimepicker(); break;
  case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
  
  case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
  case 'updategyroscope': include 'icl/updater.inc.php'; updategyroscope(); break;
  case 'showhelp': include 'icl/showhelp.inc.php'; showhelp(); break;
    
  default: echo 'unspecified interface:'.$cmd;
}

