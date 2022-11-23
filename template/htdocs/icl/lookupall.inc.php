<?php

function lookupall(){
	global $db;
	
	$key=SGET('key',0);
	
	$dkey=$key;
	if (strlen($dkey)>20) $dkey=substr($key,0,17).'...';
	
	$query="select 1 as rectype,userid as recid,dispname as rectitle from users where (login like ? or dispname like ?) limit 10";
	$rs=sql_prep($query,$db,array("%$key%","%$key%"));
	$count=sql_affected_rows($db,$rs);

	if ($count==0){
?>
<div class="infobox">No results found for "<?php echo htmlspecialchars($dkey);?>".</div>
<?php

	}
		
	while ($myrow=sql_fetch_assoc($rs)){
		$rectype=$myrow['rectype'];
		$recid=$myrow['recid'];
		$rectitle=$myrow['rectitle'];
		
		$dtitle=noapos(htmlspecialchars(htmlspecialchars($rectitle)));
		
		//js:clearmainsearch can be used to reset the search term
	?>
	<div class="mainsearchitem">
		<a onclick="ajxjs(self.showuser,'users.js');showuser(<?php echo $recid;?>,'<?php echo $dtitle;?>');"><?php echo htmlspecialchars($rectitle);?></a>
	</div>
	<?php	
	}//while

	//implement your own search logic, but be careful of naively searching in all the tables
	
	//use a parallel querying service (e.g. an API in Go), or a full text search engine such as Spinx.
		
}