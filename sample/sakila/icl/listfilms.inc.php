<?php

function listfilms(){
	global $db;
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=$_GET['page']+0;
	
	if ($mode!='embed'){

?>
<div class="section">
<div style="margin:0;padding:0;font-size:12px;padding:5px 0;">
<input id="filmkey" class="img-mg" onkeyup="_inline_lookupfilm(this);">
</div>
<div id="filmlist">
<?		
	}

	$query="select * from film ";
	if ($key!='') $query.=" where title like '%$key%' ";
	$rs=sql_query($query,$db);
	$count=sql_affected_rows($db,$rs);
	
	$perpage=20;
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$perpage*$page;

	if ($maxpage>0){
?>
<div style="font-size:12px;padding:10px 0;">
<?echo $page+1;?> of <?echo $maxpage+1;?>
&nbsp;
<a href=# onclick="ajxpgn('filmlist',document.appsettings.codepage+'?cmd=slv2&page=<?echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('filmlist',document.appsettings.codepage+'?cmd=slv2&page=<?echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?		
	}
	
	$query.=" order by title limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$filmid=$myrow['film_id'];
		$title=htmlspecialchars($myrow['title']);
		$dbtitle=str_replace("'","\'",$title);
?>
<div class="listitem"><a onclick="showfilm(<?echo $filmid;?>,'<?echo $dbtitle;?>');"><?echo $title;?></a></div>
<?		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a>Films</a>';
ajxjs(self.showfilm,'films.js');
</script>
<?	
	}//embed mode

}
