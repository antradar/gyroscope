<?php

function delfilm(){
	$filmid=GETVAL('filmid');
	
	global $db;
	
	//record deletion is a delicate matter; referential integrity must be guarded at all times
	
	$query="delete from film_actor where film_id=$filmid";
	sql_query($query,$db);
	
	$query="delete from film_category where film_id=$filmid";
	sql_query($query,$db);
	
	$query="delete rental from inventory,rental where inventory.inventory_id=rental.inventory_id and inventory.film_id=$filmid";
	sql_query($query,$db);
	
	$query="delete from inventory where film_id=$filmid";
	sql_query($query,$db);
	
	$query="delete from film where film_id=$filmid";
	sql_query($query,$db);
	
	echo "deleted Film #$filmid";
	
	logaction("deleted Film #$filmid",array('filmid'=>$filmid));
}