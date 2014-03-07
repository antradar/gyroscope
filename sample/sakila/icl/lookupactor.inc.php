<?php

function lookupactor(){
	$key=GETSTR('key');
	
	global $db;
	
	$query="select * from actor ";
	if ($key!='') $query.=" where first_name like '$key%' or last_name like '$key%' or concat(first_name,' ',last_name) like '$key%' ";
	$query.=" order by first_name, last_name ";
	$rs=sql_query($query,$db);
?>
<div class="section">
<?	
	while ($myrow=sql_fetch_array($rs)){
		$actorid=$myrow['actor_id'];
		$name=$myrow['first_name'].' '.$myrow['last_name'];
		$dbname=str_replace("'","\'",$name);
?>
<div class="listitem"><a onclick="picklookup('<?echo $dbname;?>',<?echo $actorid;?>);"><?echo $name;?></a></div>
<?			
	}//while	
?>
</div>
<?	
}