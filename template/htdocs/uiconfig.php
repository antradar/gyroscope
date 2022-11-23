<?php

$uiconfig=array(
	'toolbar_position'=>'top', //default: top; options: top, left; when set to left, the tab list takes over the toolbar and shares the space with a master search box
	'closeall_button'=>'before', //default: place CloseAll button before all the tabs; "after" places the CloseAll button after the Home tab.
	'enable_master_search'=>false, //default: false; when enabled, the tab list takes over the tool bar and share the space with a master searchbox
	'force_inline_lookup'=>false, //default: false; use side lookup panel when possible
);

if ($uiconfig['toolbar_position']=='left') {
	$uiconfig['force_inline_lookup']=true;
	$uiconfig['closeall_button']='after';
}
