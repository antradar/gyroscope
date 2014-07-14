<?php

date_default_timezone_set('America/Toronto');

define ('TABLENAME_USERS','users');
define ('TABLENAME_ACTIONLOG','actionlog');


if (isset($_SERVER['HTTP_X_REAL_IP'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];
if (isset($_SERVER['HTTP_X_FORWARDED_SSL'])&&$_SERVER['HTTP_X_FORWARDED_SSL']=='on') $_SERVER['HTTPS']='on';

if (trim($_SERVER['PHP_SELF'])=='') $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];
