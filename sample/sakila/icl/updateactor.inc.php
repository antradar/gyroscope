<?php

include 'icl/showactor.inc.php';

function updateactor(){
	$actorid=GETVAL('actorid'); //assert that the actorid is present and numeric
	$fname=GETSTR('fname');
	$lname=GETSTR('lname');
	
	global $db; //access to the database
	
	$query="update actor set first_name='$fname', last_name='$lname' where actor_id=$actorid";
	sql_query($query,$db);
	
	//piggyback the content of the new actor; to be displayed in the same tab container
	showactor($actorid);
	
	logaction("updated actor $fname $lname",array('actorid'=>$actorid,'fname'=>$fname,'lname'=>$lname),array('rectype'=>'actor','recid'=>$actorid));
	
}