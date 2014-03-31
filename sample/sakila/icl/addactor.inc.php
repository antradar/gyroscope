<?php

function addactor(){
	$fname=GETSTR('fname');
	$lname=GETSTR('lname');
	
	global $db;
	
	$query="insert into actor(first_name,last_name) values ('$fname','$lname')";
	$rs=sql_query($query,$db);
	
	
	$actorid=sql_insert_id($db,$rs);
	
	logaction("created actor $fname $lname",array('fname'=>$fname,'lname'=>$lname,'actorid'=>$actorid));
		
	echo $actorid;die();
		
}