<?php

include 'icl/showfilm.inc.php';

function updatefilm(){
	$filmid=GETVAL('filmid');
	$title=GETSTR('title');
	$year=GETVAL('year');
	$languageid=GETVAL('languageid');
	
	global $db;
	global $HTTP_RAW_POST_DATA; //access to raw post data
	
	$desc=$HTTP_RAW_POST_DATA;
	$desc=str_replace("\'","'",$desc); //remove extra slashes
	$desc=str_replace("'","\'",$desc); //re-escape single quotes
	
	$query="update film set title='$title', release_year='$year', description='$desc' ";
	if ($languageid) $query.=" ,language_id=$languageid ";
	$query.=" where film_id=$filmid";
	sql_query($query,$db);
	
	logaction("updated <u>$title</u>",array('filmid'=>$filmid,'lang'=>$languageid,'year'=>$year,'title'=>$title));
	
	//piggyback the film content
	showfilm($filmid);	
}