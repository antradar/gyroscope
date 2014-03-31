<?
//define constants that are shared by both front- and back-end code

$codepage='myservices.php';
$viewcount=3;

$toolbaritems=array(
	array('title'=>'List 1','viewindex'=>1,'icon'=>'img-big1'),
	array('title'=>'List 2','viewindex'=>2,'icon'=>'img-big2'),
	array('title'=>'Reports','viewindex'=>0,'icon'=>'img-reports'),
	
	//array('type'=>'break'), // divider
	
	//array('title'=>'List 1','viewindex'=>1,'icon'=>'img-big1','noiphone'=>1), //hide in mobile view
	//array('title'=>'Demo','viewindex'=>1,'icon'=>'img-big1','action'=>"alert('custom action');"), //custom action
	//array('title'=>'Demo','viewindex'=>null,'icon'=>'img-big1','action'=>"alert('custom action');"), //custom action without list view
	//array('type'=>'custom','desktop'=>'D','iphone'=>'<div class="menuitem">M</div>'), //custom icon
);