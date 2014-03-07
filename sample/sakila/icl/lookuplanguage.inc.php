<?php

function lookuplanguage(){
	$key=GETSTR('key');
	
	global $db;
	
	$query="select * from language ";
	if ($key!='') $query.=" where name like '$key%'";
	$query.=" order by name ";
	$rs=sql_query($query,$db);
?>
<div class="section">
<?	
	while ($myrow=sql_fetch_array($rs)){
		$languageid=$myrow['language_id'];
		$name=$myrow['name'];
?>
<div class="listitem"><a onclick="picklookup('<?echo $name;?>',<?echo $languageid;?>);"><?echo $name;?></a></div>
<?			
	}//while	
?>
</div>
<?	
}