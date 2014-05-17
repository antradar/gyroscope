<?php

include 'icl/listfilmactors.inc.php';

function addfilmactor(){
	$filmid=GETVAL('filmid');
	$actorid=GETVAL('actorid');
	
	global $db;
	
	$query="select * from film_actor where film_id=$filmid and actor_id=$actorid";
	$rs=sql_query($query,$db);
	
	$query="insert into film_actor (film_id,actor_id) values ($filmid,$actorid)";
	if (!$myrow=sql_fetch_array($rs)) {
		sql_query($query,$db);

		$query="select title from film where film_id=$filmid";
		$rs=sql_query($query,$db);
		$myrow=sql_fetch_array($rs);
		$title=$myrow['title'];
		
		$query="select * from actor where actor_id=$actorid";
		$rs=sql_query($query,$db);
		$myrow=sql_fetch_array($rs);
		$name=$myrow['first_name'].' '.$myrow['last_name'];

		logaction("",array(),array('rectype'=>'actorfilms','recid'=>$actorid));
				
		logaction("added <u>$name</u> to <u>$title</u>",array('filmid'=>$filmid,'actorid'=>$actorid,'title'=>$title,'name'=>$name),
			array('rectype'=>'filmactors','recid'=>$filmid)
		);

					
	}
	
	
	listfilmactors($filmid);	
}