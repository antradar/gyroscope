<?php
include_once 'icl/reauth.inc.php';

function authpump($ctx=null){
	reauth($ctx);
	$hb=SGET('hb',1,$ctx);
	$ret=preg_replace('/[^\d]/','',$hb);
	if (strlen($ret)>40) $ret=substr($ret,0,40);
	echo $ret;
	gs_die($ctx);
}
