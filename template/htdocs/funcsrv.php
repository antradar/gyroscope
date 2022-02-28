<?php

include 'lb.php';
if ($usehttps) include 'https.php';
if (!$enableudf) die('UDF Editing is disabled');


include 'connect.php';
include 'settings.php';
include 'forminput.php';

$cmd=$_GET['cmd'];

switch ($cmd){

	case 'newfunc': include 'udf/newfunc.inc.php'; newfunc(); break;
	case 'showfunc': include 'udf/showfunc.inc.php'; showfunc(); break;
	case 'updatefunc': include 'udf/updatefunc.inc.php'; updatefunc(); break;
	case 'listfuncs': include 'udf/listfuncs.inc.php'; listfuncs(); break;
	case 'delfunc': include 'udf/delfunc.inc.php'; delfunc(); break;
	
	default: die('invalid command: '.$cmd);	
}