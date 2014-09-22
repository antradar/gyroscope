<?php

function listfilmactors($filmid=null){
	if (!isset($filmid)) $filmid=GETVAL('filmid');
	
	global $db;

?>
<div class="stable">
<table class="subtable">
<?		
	$query="select * from film_actor,actor where film_actor.actor_id=actor.actor_id and film_id=$filmid order by first_name,last_name";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		$actorid=$myrow['actor_id'];
		$actorname=$myrow['first_name'].' '.$myrow['last_name'];
		$dbactorname=str_replace("'","\'",$actorname);
?>
<tr><td><a onclick="ajxjs(self.showactor,'actors.js');showactor(<?echo $actorid;?>,'<?echo $dbactorname;?>');"><?echo $actorname;?></a></td><td><a onclick="delfilmactor(<?echo $actorid;?>,<?echo $filmid;?>);"><img src="imgs/t.gif" class="img-del"></a></td></tr>
<?			
	}//while	
?>
<tr>
	<td valign="top">
		<input class="inpmed" id="filmactor_<?echo $filmid;?>" onfocus="lookupactor(this);" onkeyup="_lookupactor(this);">
		<span id="filmactor_<?echo $filmid;?>_val2"></span>
	</td>
	<td valign="top"><button onclick="addfilmactor(<?echo $filmid;?>);">Add</button></td>
</tr>
<tr><td colspan="2">
	<?makelookup('filmactor_'.$filmid);?>
</td></tr>
</table>
</div>
<?	
}