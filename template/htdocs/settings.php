<?
include 'lang.php';

//comment out the following lines to disable authentication
include 'auth.php';

$codepage='myservices.php';
$fastlane='phpx-services.php'; //change this name if HAProxy is set up to route by filename to a dedicated server

//define constants that are shared by both front- and back-end code
//repeat the settings in settings.tmpl.php


$userroles=array(
	'admins'=>'standard admin rights',
	'reportsettings'=>'manage report settings',
	'systemplateuse'=>'configure system templates',
	'systemplate'=>'create/modify system templates',
	'accounts'=>_tr('rights_accounts'),
	'dbadmin'=>'db admin',	//uncomment this to reveal the dbadmin option for SQL Comp tool
	'upgrademods'=>'upgrade modules'
);



$user=userinfo();


$toolbaritems=array(
'core.settings'=>array('title'=>'Settings','icon'=>'img-settings','modversion'=>'91','lockdown'=>1),
	'core.users'=>array('title'=>'Users','icon'=>'','modversion'=>'78','lockdown'=>1),
	'core.reportsettings'=>array('title'=>'Report Settings','icon'=>'','modversion'=>'92','lockdown'=>1),	
	'core.templatetypes'=>array('title'=>'System Templates','icon'=>'','modversion'=>'91','lockdown'=>1),	
	'core.templates'=>array('title'=>_tr('icon_templates'),'icon'=>'','modversion'=>'91'),

'core.reports'=>array('title'=>'Reports','icon'=>'img-reports','modversion'=>'92','lockdown'=>1),
);


foreach ($toolbaritems as $idx=>$item) if (!$item) unset($toolbaritems[$idx]);
