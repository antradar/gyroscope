<?php

function newfunc(){
	global $db;
	
	$query="select database() as dbname from dual";	
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$defdbname=$myrow['dbname'];
		
	$dbnames=array();
	
	$query="show databases";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		if ($myrow[0]=='information_schema') continue;
		if ($myrow[0]=='mysql') continue;
		if ($myrow[0]=='performance_schema') continue;
		array_push($dbnames,$myrow[0]);	
	}
?>
	<h2>Add a new UDF</h2>
	
	<div style="font-family:console,monospace;line-height:1.8em;font-size:12px;">
		create function <select id="dbname_new">
		<?php
			foreach ($dbnames as $dbname){
		?>
			<option value="<?php echo $dbname;?>" <?php if ($defdbname==$dbname) echo 'selected';?> ><?php echo $dbname;?></option>
		<?php
			}
		?>
		</select>.<input id="funcname_new"> (<input id="funcargs_new" style="width:300px;" value="param1_ int">)<br>
<textarea id="funcpre_new" class="inplong" style="height:70px;">
returns text
not deterministic
reads sql data
</textarea>
<br>
<textarea id="functext_new" class="inplong" style="height:200px;" onfocus="filterkeys(this);">
begin


end
</textarea>

		
	</div>	
	
	<div class="buttonbar">
		<button onclick="addfunc('<?php emitgskey('updatefunc');?>');">Create Function</button>
	</div>
<?php	
}