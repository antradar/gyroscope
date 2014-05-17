<?php

include 'icl/listfilmactors.inc.php';

function delfilmactor(){
	$filmid=GETVAL('filmid');
	$actorid=GETVAL('actorid');
	
	global $db;
	
	$query="select title from film where film_id=$filmid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);
	$title=$myrow['title'];
	
	$query="select * from actor where actor_id=$actorid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);
	$name=$myrow['first_name'].' '.$myrow['last_name'];
	
	
	$query="delete from film_actor where film_id=$filmid and actor_id=$actorid";
	sql_query($query,$db);

	logaction("",array(),array('rectype'=>'actorfilms','recid'=>$actorid));
		
	logaction("removed <u>$name</u> from <u>$title</u>",array('actorid'=>$actorid,'filmid'=>$filmid,'title'=>$title,'name'=>$name),
		array('rectype'=>'filmactors','recid'=>$filmid)
	);
		
	
	listfilmactors($filmid);	
}