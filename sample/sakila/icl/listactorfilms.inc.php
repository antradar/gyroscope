<?php

function listactorfilms($actorid=null){
	if (!isset($actorid)) $actorid=GETVAL('actorid');
	
	global $db;
?>
<table class="subtable">
<?	
	$query="select * from film_actor,film where film_actor.film_id=film.film_id and film_actor.actor_id=$actorid";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		$filmid=$myrow['film_id'];
		$title=$myrow['title'];
		$dbtitle=str_replace("'","\'",$title);
		$year=$myrow['release_year'];
?>
<tr><td style="padding-right:10px;"><?echo $year;?></td><td><a onclick="ajxjs(self.showfilm,'films.js');showfilm(<?echo $filmid;?>,'<?echo $dbtitle;?>');"><?echo $title;?></a></td></tr>
<?			
	}
?>
</table>
<?		
}