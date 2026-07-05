<?php

$uiconfig=array(
	'toolbar_position'=>'left', //default: top; options: top, left; when set to left, the tab list takes over the toolbar and shares the space with a master search box
	'vertical_nav_persist'=>true,
	'settings_position'=>'left', //default: menu; options: menu, top, left; left only available when vertical_nav_persist is true
	'account_position'=>'left', //default: top; options: top, left; available when vertical_nav_persist is true
	'tabs_stretch'=>false, //default: false, only relevant the toolbar is positioned left
	'closeall_button'=>'before', //default: place CloseAll button before all the tabs; "after" places the CloseAll button after the Home tab.
	'enable_master_search'=>true, //default: false; when enabled, the tab list takes over the tool bar and share the space with a master searchbox
	'force_inline_lookup'=>false, //default: false; use side lookup panel when possible
	'singletab'=>false, //default: false; simulates a single page app on a desktop
	'statusbar_hide'=>true, //default: false; when true, hide the status bar and use sptr as meta-key indicator
	'corner_botchat'=>true, //default: true; show persistent botchat corner button when gsai is available
	'corner_extlivechat'=>false, //default: false; show persistent extlivechat corner button
	'mobile'=>array(
		'singletab'=>false, //default false: simulates single page app on mobile
		'toolbar_position'=>'top', //default: top; top or bottom
		'back_bar'=>true, //default true
		'list_bar'=>true, //default true
		'reload_bar'=>true, //default true
		'power'=>true, //default true; power button sign out
		'corner_botchat'=>true, //default: true; show persistent botchat corner button on mobile
		'corner_extlivechat'=>false, //default: false; show persistent extlivechat corner button on mobile
		'enable_master_search'=>true, //default: false; show search in portrait toolbar
	),
	'mobile_landscape'=>array(
		'toolbar_position'=>'left', //default: top; top, bottom, or left; when top, dashboard icon is omitted from toolbar
		'vertical_nav_persist'=>true, //default: false; when toolbar_position is left, whether the nav sidebar persists alongside content
		'enable_master_search'=>true, //default: false; show search in landscape toolbar
		'settings_position'=>'left', //default: empty = inherit from top-level; options: menu, top, left; left only available when vertical_nav_persist is true
		'account_position'=>'left', //default: empty = inherit from top-level; options: top, left; left only available when vertical_nav_persist is true
		'corner_botchat'=>true, //default: true; show persistent botchat corner button on mobile landscape
		'corner_extlivechat'=>false, //default: false; show persistent extlivechat corner button on mobile landscape
	)
);

if ($uiconfig['toolbar_position']=='left') {
	//$uiconfig['force_inline_lookup']=true;
	$uiconfig['closeall_button']='after';
}


if ($uiconfig['toolbar_position']=='top') {
	$uiconfig['vertical_nav_persist']=false;
	$uiconfig['tabs_stretch']=false;
}

if ($uiconfig['mobile_landscape']['toolbar_position']!='left') {
	$uiconfig['mobile_landscape']['vertical_nav_persist']=false;
}

