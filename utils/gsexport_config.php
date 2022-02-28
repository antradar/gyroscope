<?php

$tables=array(
	
	'gss'=>array(
		'type'=>'root',
		'files'=>array(
			'logo'=>'../protected/clogos/#.gif'
		),
		
	),
	

	'users'=>array(
		'type'=>'base', //if a table has a direct gsid field, it's a base; otherwise it's an 1-N
		'mnt'=>'users',
		'mntid'=>'userid',
	),
	
	

	'templatetypes'=>array(
		'type'=>'base',
		'mnt'=>'templatetypes',
		'mntid'=>'templatetypeid',
		'defrefs'=>array(
			'activetemplateid'=>array('reftable'=>'templates','refkey'=>'templateid'),
		)	
	),

	
	'templates'=>array(
		'type'=>'1-n',
		'mnt'=>'templates', //fancy mount: 'templatetypes/#templatetypeid/templates'
		'mntid'=>'templateid',
		'chaintables'=>array('templatetypes','templates'),
		'chainlinks'=>array('templatetypeid-templatetypeid'),
		'refs'=>array(
			'templatetypeid'=>array('reftable'=>'templatetypes','refkey'=>'templatetypeid'),
		),
	),	
	

	'templatevars'=>array(
		'type'=>'1-n',
		'mnt'=>'templatevars', //fancy mount: templatetypes/#templatetypeid/templatevars
		'mntid'=>'templatevarid',
		'chaintables'=>array('templatetypes','templatevars'),
		'chainlinks'=>array('templatetypeid-templatetypeid'),
	),
	
	'reports'=>array(
		'type'=>'base',
		'mnt'=>'reports',
		'mntid'=>'reportid',
	),	

	'actionlog'=>array( //leave this to the very end
		'type'=>'base',
		'mnt'=>'actionlog',
		'mntid'=>'alogid',
		'chaintables'=>array('actionlog'),
		'chainlinks'=>array(),
		'refs'=>array(
			'userid'=>array('reftable'=>'users','refkey'=>'userid'),
			'recid'=>array('func'=>'actionlog'),
		)
		
	),


);


/////////// ID resolution functions

function resolve_actionlog($rec){
	$reftable=null;
	$rectype=$rec['rectype'];
		
	switch ($rectype){
	case '': case 'streamupdate': case 'creditcards': case 'helptopic': break; //ignore
	case 'templatetypetemplates': case 'template': $reftable='templates'; break;
	case 'reauth': case 'user': $reftable='users'; break;
	case 'report': case 'reportsetting': $reftable='reports'; break;
	default: echo "unknown rectype [$rectype]\r\n"; print_r($rec); die();	
	}
	
	return $reftable;
}

