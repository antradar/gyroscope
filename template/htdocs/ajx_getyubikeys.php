<?php
include 'lb.php';
include 'connect.php';
include 'forminput.php';

$login=SGET('login');
if ($login==''){header('HTTP/1.0 403');die('.');}

$query="select attid from ".TABLENAME_YUBIKEYS.",".TABLENAME_USERS." where ".TABLENAME_YUBIKEYS.".userid=".TABLENAME_USERS.".userid and useyubi=1 and login=?";
$rs=sql_prep($query,$db,$login);
$attids=array();
while ($myrow=sql_fetch_assoc($rs)) array_push($attids,$myrow['attid']);
echo implode(',',$attids); 