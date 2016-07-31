<?php

include 'icl/showtemplate.inc.php';

function updatetemplate(){
	$templateid=GETVAL('templateid');	
	$templatename=QETSTR('templatename');
	$templatetext=QETSTR('templatetext');


	global $db;

	$query="update templates set templatename='$templatename',templatetext='$templatetext' where templateid=$templateid";
	sql_query($query,$db);

	logaction("updated Template #$templateid <u>$templatename</u>",
		array('templateid'=>$templateid,'templatename'=>"$templatename"),
		array('rectype'=>'template','recid'=>$templateid));

	showtemplate($templateid);
}
