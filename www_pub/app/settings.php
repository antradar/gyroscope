<?php

include 'lang.php';

//comment out the following lines to disable authentication
include 'auth.php';

$codepage='myservices.php';
$binpages=array(
	1=>'myservices.gsb', //bingo bridge to Go
	2=>'myservices.pyb', //python
	3=>'myservices.njs', //nodejs
);
$fastlane='phpx-services.php'; //change this name if HAProxy is set up to route by filename to a dedicated server

//define constants that are shared by both front- and back-end code
//repeat the settings in settings.tmpl.php


$userroles=array(
	'admins'=>_tr('rights_standardadmin'),
	'reportsettings'=>_tr('rights_managereports'),
	'faultlog'=>'view fault log',	
	'systemplate'=>_tr('rights_managetemplates'),
	'devreports'=>' '._tr('rights_implementreports'),
	'systemplateuse'=>_tr('rights_configtemplates'),
	'accounts'=>_tr('rights_accounts'),
	'creditcards'=>'manage credit cards and subscription',
	'dbadmin'=>_tr('rights_dbadmin'),
	//'msdrive'=>'access all Microsoft drives',
	//'upgrademods'=>_tr('rights_upgrademodules'),
	'helpedit'=>'edit help topics',
	'chats'=>'respond to support chats',
	'chatsettings'=>' manage chat settings',
	'sharedashreports'=>'share custom reports on the home tab',
	'botchat'=>'use AI chatbot',
	'kbman'=>' manage AI knowledge base',
	'sapadmin'=>'view SAP data',
	'msgpipe'=>'provision notification lists',
	'msgpipeuse'=>' modify notification list recipients',
);

//a user can grant the following rights only if the user also has the right themselves
$userrolelocks=array('devreports','accounts','dbadmin','creditcards','systemplate','msdrive','helpedit','chatsettings','chats','sharedashreports','faultlog','sapadmin','msgpipe','msgpipeuse');

//to quickly force every role to be inherited, uncomment the following:

//$userrolelocks=array_keys($userroles);


$user=userinfo();


$toolbaritems=array(

'codegen.sap'=>array('title'=>'SAP','icon'=>'img-sap','modversion'=>'91','lockdown'=>1),
	'codegen.sapentitysets'=>array('title'=>'SAP Entity Sets','icon'=>'','modversion'=>'91','lockdown'=>1),
	'codegen.sapentities'=>array('title'=>'SAP Entities','icon'=>'','modversion'=>'91','lockdown'=>1),

'lv.welcome'=>array('title'=>'Main Menu','icon'=>''),
'core.settings'=>array('title'=>_tr('icon_settings'),'icon'=>'img-settings','modversion'=>'91','lockdown'=>1),
	'core.users'=>array('title'=>'Users','icon'=>'','modversion'=>'78','lockdown'=>1),
	'core.reportsettings'=>array('title'=>'Report Settings','icon'=>'','modversion'=>'92','lockdown'=>1),	
	'core.templatetypes'=>array('title'=>'System Templates','icon'=>'','modversion'=>'91','lockdown'=>1),	
	'core.templates'=>array('title'=>_tr('icon_templates'),'icon'=>'','modversion'=>'91'),
	'core.gsreplays'=>array('title'=>'Replay Clips'),
	'core.kbman'=>array('title'=>'AI Knowledge Base'),
	'core.kbmanrecs'=>array('title'=>'AI KB Items'),

	
'core.reports'=>array('title'=>_tr('icon_reports'),'icon'=>'img-reports','modversion'=>'92','lockdown'=>1),
'core.helptopics'=>array('title'=>_tr('icon_helptopics'),'icon'=>'img-helptopics','modversion'=>1),
'codegen.botchats'=>array('title'=>'AI Chat','icon'=>'img-botchats','modversion'=>1),
//'codegen.chats'=>array('title'=>'Chats','icon'=>'img-chats','modversion'=>1,'bingo'=>1),

);


if (!isset($user['groups']['sapadmin'])){
	unset($toolbaritems['codegen.sap']);	
	unset($toolbaritems['codegen.sapentitysets']);	
	unset($toolbaritems['codegen.sapentities']);	
}

if (!isset($user['groups']['botchat'])){
	unset($toolbaritems['codegen.botchats']);	
}


foreach ($toolbaritems as $idx=>$item) if (!$item) unset($toolbaritems[$idx]);
