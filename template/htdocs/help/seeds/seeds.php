<?php

$codegen_seeds=array(
	'listview'=>array('name'=>'Base Record','desc'=>'A base record can be directly created by the user','icon'=>'base'),
	'subrecord'=>array('name'=>'Sub Record','desc'=>'A sub record has a detail view and a side list that is subordinate to a base record;<br>it can only be created within the context of a base record','icon'=>'sub'),
	'directlist'=>array('name'=>'Direct List','desc'=>'A direct list allows 1-N editing within a master view<br>without opening another tab or bridging to another base- or sub-record','icon'=>'direct'),
	'quicklist'=>array('name'=>'Quick List','desc'=>'A client-side list editor for new records before the creation of a record holder','icon'=>'direct'),
	'bridgelist'=>array('name'=>'Record Bridge','desc'=>'A record bridge connects the side list in a base record to another base record.<br>Record Bridge is an advanced case of Direct List','icon'=>'bridge'),
	'lookup'=>array('name'=>'Lookup List','desc'=>'Any field in a record can be linked to another entity.<br>The Lookup List ensures the proper ID resolution.','icon'=>'lookup'),
	'report'=>array('name'=>'Date-Range Report','desc'=>'','icon'=>''),
	'break1'=>array('type'=>'break'),
	'profile'=>array('name'=>'1-1 Image Uploader','desc'=>'','icon'=>''),
	
	'albums'=>array('name'=>'1-N Image Uploader','desc'=>'','icon'=>'','package'=>1,
		'items'=>array(
			'album'=>array('name'=>'Single 1-N Image Uploader','desc'=>'','icon'=>''),
			'albumm'=>array('name'=>'Multiple 1-N Image Uploader','desc'=>'','icon'=>'')
		)
	),
	
	'uploader'=>array('name'=>'1-N File Uploader','desc'=>'A file uploader uses a data table to assign unique IDs to each upload;<br>The files will be renamed to have a generic extension.','icon'=>'upload'),
	'sproutuploader'=>array('name'=>'Sprout Video Uploader','upload to Sprout video with form post fallback and WSS notification'),
	'break2'=>array('type'=>'break'),
	'tinymces'=>array('name'=>'Rich Text Editor','icon'=>'','package'=>1,
		'items'=>array(
			'tinymce'=>array('name'=>'Rich Text Editor','desc'=>'Main editor','icon'=>''),
			'smartobjplugin'=>array('name'=>'Smart Object - Plugins','desc'=>'Plugin lists for RTE','icon'=>''),
			'smartobjmention'=>array('name'=>'Smart Object - Mentions','desc'=>'Reference links for RTE','icon'=>'')
		)
	),
	'sortlists'=>array('name'=>'Drag & Drop Sort List','icon'=>'','package'=>1,
		'items'=>array(
			'sortlist'=>array('name'=>'List View','desc'=>'','icon'=>''),
			'dirsortlist'=>array('name'=>'Direct List (1-N)','desc'=>'','icon'=>''),
		)
	),
	
	'fnav'=>array('name'=>'Faceted Navigation','icon'=>'','package'=>1,
		'items'=>array(
			'gnav'=>array('name'=>'Frontend - Standard','desc'=>'Classic front-end faceted navigation','icon'=>''),
			//'gnavi'=>array('name'=>'Frontend - Multi-core','desc'=>'High-performance front-end navigation; specific server hardware and setup required','icon'=>''),
			'navfilter'=>array('name'=>'Gyroscope - Standard','desc'=>'Faceted navigation for list view. Make a standard list view first','icon'=>''),
			'navfilterch'=>array('name'=>'Gyroscope - ClickHouse','desc'=>'Faceted navigation for list view, optimized for ClickHouse','icon'=>''),
			'navfiltergo'=>array('name'=>'Gyroscope Go - Standard','desc'=>'Go version of the standard Faceted Nav','icon'=>''),
			//'navfilteri'=>array('name'=>'Gyroscope - Multi-core','desc'=>'Faceted navigation for list view using parallel queries','icon'=>'')
		)
	),
	'asyncd'=>array('name'=>'AsyncD','desc'=>'The Distributed Asynchronous Data Processor forks a long-running process in the background while updating the web frontend its completion process.','icon'=>''),
	'digisign'=>array('name'=>'Digital Signing','desc'=>'','icon'=>''),
	'portal'=>array('name'=>'Vendor Portal','desc'=>'Multi-tenant Entity-based Portal','icon'=>''),
	'vendorauth'=>array('name'=>'Vendor Authentication','desc'=>'Entity-specific user groups','icon'=>'')

);