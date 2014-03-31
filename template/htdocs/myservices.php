<?
include 'lb.php';
include 'settings.php';

include 'connect.php';
include 'auth.php';
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
  
//Entity 1
  case 'slv1': include 'icl/module1.inc.php'; showlist1(); break;
  case 'dt1': include 'icl/module1.inc.php'; showdetails1(); break;

//Entity 2
  case 'slv2': include 'icl/module2.inc.php'; showlist2(); break;
  case 'dt2': include 'icl/module2.inc.php'; showdetails2(); break;

  case 'pkd': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
  case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
  
  case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
  case 'updategyroscope': include 'icl/updater.inc.php'; updategyroscope(); break;
  case 'showhelp': include 'icl/showhelp.inc.php'; showhelp(); break;
    
  default: echo 'unspecified interface:'.$cmd;
}
