<?php

$uiconfig=array(
	'toolbar_position'=>'top', //default: top; options: top, left; when set to left, the tab list takes over the toolbar and shares the space with a master search box
	'closeall_button'=>'before', //default: place CloseAll button before all the tabs; "after" places the CloseAll button after the Home tab.
	'enable_master_search'=>false, //default: false; when enabled, the tab list takes over the tool bar and share the space with a master searchbox
	'force_inline_lookup'=>false, //default: false; use side lookup panel when possible
	'singletab'=>false, //default: false; simulates a single page app on a desktop
	'mobile'=>array(
		'singletab'=>false, //default false: simulates single page app on mobile
		'toolbar_position'=>'top', //default: top; top or bottom
		'back_bar'=>true, //default true
		'list_bar'=>true, //default true
		'reload_bar'=>true, //default true
		'power'=>true, //default true; power button sign out
	)
);

if ($uiconfig['toolbar_position']=='left') {
	$uiconfig['force_inline_lookup']=true;
	$uiconfig['closeall_button']='after';
}
