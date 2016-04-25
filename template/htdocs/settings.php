<?
include 'lang.php';

//comment out the following lines to disable authentication
include 'auth.php';

$codepage='myservices.php';
$fastlane='phpx-services.php'; //change this name if HAProxy is set up to route by filename to a dedicated server

//define constants that are shared by both front- and back-end code

$viewcount=3;

$userroles=array(
	'admins'=>'standard admin privileges',
	'accounts'=>'manage user accounts'
);

// smb 80dd22a0 - 


// smb bed99e5d - 


// smb 182eb2eb - 


// smb 4d373b24 -


$user=userinfo();


$toolbaritems=array(
	array('title'=>_tr('icon_reports'),'viewindex'=>0,'icon'=>'img-reports'),


/*
	//uncommet to test belt slider
	

	array('title'=>'Reports 2','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 3','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 4','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 5','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 6','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 7','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 8','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 9','viewindex'=>0,'icon'=>'img-reports'),
	array('title'=>'Reports 10','viewindex'=>0,'icon'=>'img-reports'),
*/
		
// svn merge boundary 80dd22a0883aaa1f8cd09b09e81bdd9b - 


// svn merge boundary bed99e5db57749f375e738c1c0258047 - 


// svn merge boundary 182eb2eb0c3b7d16cf92c0972fe64bcc - 


// svn merge boundary 4d373b247a04253ee05a972964f7a7f3 -
	
	
	//array('type'=>'break'), // divider
	
	//array('title'=>'List 1','viewindex'=>1,'icon'=>'img-big1','noiphone'=>1), //hide in mobile view
	//array('title'=>'Demo','viewindex'=>1,'icon'=>'img-big1','action'=>"alert('custom action');"), //custom action
	//array('title'=>'Demo','viewindex'=>null,'icon'=>'img-big1','action'=>"alert('custom action');"), //custom action without list view
	//array('type'=>'custom','desktop'=>'D','iphone'=>'<div class="menuitem">M</div>'), //custom icon
);