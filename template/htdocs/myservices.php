<?
include 'settings.php';

include 'connect.php';
include 'auth.php';
include 'xss.php';

xsscheck();	
login(true); //silent mode

$cmd=$_GET['cmd'];

include 'forminput.php';

switch($cmd){

//Accounts
  case 'showaccount': include 'icl/showaccount.inc.php'; showaccount(); break;
  case 'setaccount': include 'icl/setaccountpass.inc.php'; setaccountpass(); break;
	
//Entity 1
  case 'slv0': include 'icl/module1.inc.php'; showlist1(); break;
  case 'dt0': include 'icl/module1.inc.php'; showdetails1(); break;

//Entity 2
  case 'slv1': include 'icl/module2.inc.php'; showlist2(); break;
  case 'dt1': include 'icl/module2.inc.php'; showdetails2(); break;

  case 'pkd': include 'icl/lookup.inc.php'; showdatepicker(); break; //lookup
  case 'pump': include 'icl/utils.inc.php'; authpump(); break; //comment this out to disable authentication
  
  case 'wk': include 'icl/showwelcome.inc.php'; showwelcome(); break;
    
  default: echo 'unspecified interface:'.$cmd;
}

