<?php

function showfunc($dbname=null, $func=null){
	if (!isset($dbname)){
		$dbname=GETSTR('dbname');
		$func=GETSTR('func');
	}
	
	global $db;
	
?>
<h2><?echo $dbname;?>.<?echo $func;?></h2>
<?	
	
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
function <?echo $dbname;?>.<?echo $func;?>(<input id="funcargs_<?echo $dbname;?>_<?echo $func;?>" style="width:300px;" value="<?echo $funcpre;?>">)<br>
<textarea class="inplong" id="funcpre_<?echo $dbname;?>_<?echo $func;?>" class="inplong" style="height:70px;">
returns <?echo $returns;?>

<?echo $ddtm;?>

<?echo $access;?>

</textarea>
<br>
<textarea class="inplong" style="height:200px;" id="func_<?echo $dbname;?>_<?echo $func;?>" onfocus="filterkeys(this);"><?echo htmlentities($functext);?></textarea>

<div class="buttonbar">
	<button onclick="updatefunc('<?echo $dbname;?>','<?echo $func;?>');">Save Changes</button>
	&nbsp;
	<button onclick="delfunc('<?echo $dbname;?>','<?echo $func;?>');">Delete</button>
</div>
<?	
}