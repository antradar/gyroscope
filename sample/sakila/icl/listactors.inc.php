<?php

function listactors(){
	global $db;
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=$_GET['page']+0;

	if ($mode!='embed'){

?>
<div class="section">

	<div class="listbar">
		<input id="actorkey" class="img-mg" onkeyup="_inline_lookupactor(this);">
	</div>
	
	<div class="listbar">
		<a onclick="addtab('actor_new','New Actor','newactor');"><img src="imgs/t.gif" class="img-addrec" width="12" height="12"> add a new actor</a>
	</div>
	
<div id="actorlist">
<?		
	}

	$query="select * from actor ";
	if ($key!='') $query.=" where (first_name like '$key%' or last_name like '$key%' or concat(first_name,' ',last_name) like '$key%' ) ";
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
<a href=# onclick="ajxpgn('actorlist',document.appsettings.codepage+'?cmd=slv1&page=<?echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('actorlist',document.appsettings.codepage+'?cmd=slv1&page=<?echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?		
	}
	
	$query.=" order by first_name,last_name limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$actorid=$myrow['actor_id'];
		$fname=htmlspecialchars($myrow['first_name']);
		$lname=htmlspecialchars($myrow['last_name']);
		$actorname="$fname $lname";
		$dbactorname=str_replace("'","\'",$actorname);
?>
<div class="listitem"><a onclick="showactor(<?echo $actorid;?>,'<?echo $dbactorname;?>');"><?echo $actorname;?></a></div>
<?		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a>Actors</a>';
ajxjs(self.showactor,'actors.js');
</script>
<?	
	}//embed mode

}
