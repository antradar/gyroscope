<?
include 'lang.php';

//comment out the following lines to disable authentication
include 'auth.php';

$codepage='myservices.php';
$fastlane='phpx-services.php'; //change this name if HAProxy is set up to route by filename to a dedicated server

//define constants that are shared by both front- and back-end code
//repeat the settings in settings.tmpl.php


$userroles=array(
	'admins'=>_tr('rights_standardadmin'),
	'reportsettings'=>_tr('rights_managereports'),
	'systemplateuse'=>_tr('rights_configtemplates'),
	'systemplate'=>_tr('rights_managetemplates'),
	'accounts'=>_tr('rights_accounts'),
	'creditcards'=>'manage credit cards and subscription',
	'dbadmin'=>_tr('rights_dbadmin'),
	'upgrademods'=>_tr('rights_upgrademodules')
);



$user=userinfo();


$toolbaritems=array(
'core.settings'=>array('title'=>_tr('icon_settings'),'icon'=>'img-settings','modversion'=>'91','lockdown'=>1),
	'core.users'=>array('title'=>'Users','icon'=>'','modversion'=>'78','lockdown'=>1),
	'core.reportsettings'=>array('title'=>'Report Settings','icon'=>'','modversion'=>'92','lockdown'=>1),	
	'core.templatetypes'=>array('title'=>'System Templates','icon'=>'','modversion'=>'91','lockdown'=>1),	
	'core.templates'=>array('title'=>_tr('icon_templates'),'icon'=>'','modversion'=>'91'),

'core.reports'=>array('title'=>_tr('icon_reports'),'icon'=>'img-reports','modversion'=>'92','lockdown'=>1),
);


foreach ($toolbaritems as $idx=>$item) if (!$item) unset($toolbaritems[$idx]);
