<?php

function listfilminventory($filmid=null){
	if (!isset($filmid)) $filmid=GETVAL('filmid');
	
	global $db;
?>
<table class="subtable">
<?	
	$query="select count(inventory_id) as c, store_id from inventory where film_id=$filmid group by store_id";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		$storeid=$myrow['store_id'];
		$c=$myrow['c'];
?>
<tr><td>Store #<?echo $storeid;?></td><td> &nbsp; <?echo $c;?></td></tr>
<?			
	}//while	
?>
</table>
<?	
}