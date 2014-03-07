<?php

include 'icl/listfilmactors.inc.php';

function delfilmactor(){
	$filmid=GETVAL('filmid');
	$actorid=GETVAL('actorid');
	
	global $db;
	
	$query="delete from film_actor where film_id=$filmid and actor_id=$actorid";
	sql_query($query,$db);
	
	listfilmactors($filmid);	
}