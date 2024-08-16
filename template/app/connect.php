<?php

include_once "sql.php";
include_once "vsql.php";

// uncomment both $db lines to activate Gyroscope
// instead of using "127.0.0.1", use "localdb" and add "127.0.0.1 localdb" to /etc/hosts

if (defined('GSSERVICE')) {
	$db=sql_get_db('p:127.0.0.1','gyrostart','root','mnstudio','db');
} else {
	$db=sql_get_db('127.0.0.1','gyrostart','root','mnstudio','db');
}

$vdb=vsql_get_db('127.0.0.1:8123','gyrostart','default','mnstudio','vdb'); //uncomment to enable clickhouse connection

//$db=sql_get_db("gyroscope.sqlite3"); // for embedded deployments

$smsuser=''; //leave blank if not applicable
$smskey=''; //use your own SMS Gateway Account Key

define ('PASSWORD_COST',12); //use a benchmark tool to determine a suitable value

/*

$dbconfigs=array(
	array('host'=>'##vpn_ip_addr##','db'=>'##db_name##','user'=>'##mirror_user##','pass'=>'##mirror_pass##'),
);

*/


