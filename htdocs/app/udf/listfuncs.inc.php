<?php

function listfuncs(){
	global $db;
	
	$query="select database() as dbname from dual";	
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$defdbname=$myrow['dbname'];
	
?>
	<a class="func" href=# onclick="newfunc();return false;">add a new UDF</a>
<?php	
	
	$query="select db, name, definer, modified from mysql.proc where type='function' order by db='$defdbname' desc, db, name";
	$rs=sql_query($query,$db);
	$lastdbname='';
	
	while ($myrow=sql_fetch_assoc($rs)){
		$func=$myrow['name'];
		$dbname=$myrow['db'];
		
		$main=$dbname==$defdbname;
		
		if ($lastdbname!=$dbname){
?>
<div class="dbname <?php if ($main) echo 'main'?>"><?php echo $dbname;?></div>
<?php		
			$lastdbname=$dbname;

		}//lastdbname
	?>
		<a class="func" href=# onclick="showfunc('<?php echo $dbname;?>','<?php echo $func;?>');return false;"><?php echo $func;?></a> 
	<?php
	}//while	
}