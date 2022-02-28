<?php
include 'lang.php';

//comment out the following lines to disable authentication
include 'auth.php';

$codepage='myservices.php';
$fastlane='phpx-services.php'; //change this name if HAProxy is set up to route by filename to a dedicated server

//define constants that are shared by both front- and back-end code
//repeat the settings in settings.tmpl.php

$userroles=array(
	'admins'=>'standard admin rights',
	'accounts'=>_tr('rights_accounts'),
	'upgrademods'=>'upgrade modules',
	'dbadmin'=>'db admin'
///userroles///
);



$user=userinfo();


$toolbaritems=array(
///icons///
);


foreach ($toolbaritems as $idx=>$item) if (!$item) unset($toolbaritems[$idx]);
