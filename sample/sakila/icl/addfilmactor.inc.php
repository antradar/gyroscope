<?php

include 'icl/listfilmactors.inc.php';

function addfilmactor(){
	$filmid=GETVAL('filmid');
	$actorid=GETVAL('actorid');
	
	global $db;
	
	$query="select * from film_actor where film_id=$filmid and actor_id=$actorid";
	$rs=sql_query($query,$db);
	
	$query="insert into film_actor (film_id,actor_id) values ($filmid,$actorid)";
	if (!$myrow=sql_fetch_array($rs)) sql_query($query,$db);
	
	listfilmactors($filmid);	
}