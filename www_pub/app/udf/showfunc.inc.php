<?php

function showfunc($dbname=null, $func=null){
	if (!isset($dbname)){
		$dbname=GETSTR('dbname');
		$func=GETSTR('func');
	}
	
	global $db;
	
?>
<h2><?php echo $dbname;?>.<?php echo $func;?></h2>
<?php	
	
	$query="select * from mysql.proc where db='$dbname' and name='$func' and type='function' ";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) die('no such function');
	
	$functext=$myrow['body'];
	$funcpre=$myrow['param_list'];
	$returns=$myrow['returns'];
	$returns=preg_replace('/charset (\S+)/i','',$returns);
	
	$access=str_replace('_',' ',strtolower($myrow['sql_data_access']));
	$dtm=strtolower($myrow['is_deterministic']);
	$ddtm=$dtm=='yes'?'deterministic':'not deterministic';
	
	
	
?>
function <?php echo $dbname;?>.<?php echo $func;?>(<input id="funcargs_<?php echo $dbname;?>_<?php echo $func;?>" style="width:300px;" value="<?php echo $funcpre;?>">)<br>
<textarea class="inplong" id="funcpre_<?php echo $dbname;?>_<?php echo $func;?>" class="inplong" style="height:70px;">
returns <?php echo $returns;?>

<?php echo $ddtm;?>

<?php echo $access;?>

</textarea>
<br>
<textarea class="inplong" style="height:200px;" id="func_<?php echo $dbname;?>_<?php echo $func;?>" onfocus="filterkeys(this);"><?php echo htmlentities($functext);?></textarea>

<div class="buttonbar">
	<button onclick="updatefunc('<?php echo $dbname;?>','<?php echo $func;?>','<?php emitgskey('updatefunc');?>');">Save Changes</button>
	&nbsp;
	<button onclick="delfunc('<?php echo $dbname;?>','<?php echo $func;?>','<?php emitgskey('delfunc');?>');">Delete</button>
</div>
<?php	
}