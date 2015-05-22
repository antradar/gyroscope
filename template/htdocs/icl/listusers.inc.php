<?php

function listusers(){
	global $db; 
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=$_GET['page']+0;
	
	$user=userinfo();
	if (!$user['groups']['accounts']) die('access denied');
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">
<input id="userkey" class="img-mg" onkeyup="_inline_lookupuser(this);">
	<div style="padding-top:10px;margin-bottom:10px;">
	<a class="recadder" onclick="addtab('user_new','New User','newuser');"> <img src="imgs/t.gif" class="img-addrec" width="18" height="18">add a new user</a>
	</div>
</div>

<div id="userlist">
<?		
	}

	$query="select * from users ";
	if ($key!='') $query.=" where (login like '$key%') ";
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
<a href=# onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv1&page=<?echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv1&page=<?echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?		
	}
	
	$query.=" order by virtual, login limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$userid=$myrow['userid'];
		$login=$myrow['login'];
		$virtual=$myrow['virtual'];
		
		$usertitle="$login"; //change this if needed
		
		$dbusertitle=noapos(htmlspecialchars($usertitle));
		$groupnames=$myrow['groupnames'];
		$hash=substr(md5($groupnames),0,6);
		if ($virtual) $hash='ffffff';
		
?>
<div class="listitem" style="border-left:solid 3px #<?echo $hash;?>;padding-left:5px;"><a onclick="showuser(<?echo $userid;?>,'<?echo $dbusertitle;?>');"><?echo $usertitle;?></a></div>
<?		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a>Users</a>';
ajxjs(self.showuser,'users_js.php');
</script>
<?	
	}//embed mode

}

