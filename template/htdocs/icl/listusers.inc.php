<?php

function listusers(){
	
	global $db; 
	
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=isset($_GET['page'])?$_GET['page']+0:0;
	
	$user=userinfo();
	$myuserid=$user['userid']+0;
		
	if (!isset($user['groups']['accounts'])) die('<div class="section">You cannot manage user accounts</div>');
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">

	<form class="listsearch" onsubmit="_inline_lookupuser(gid('userkey'));return false;">
	<div class="listsearch_">
		<input id="userkey" class="img-mg" onkeyup="_inline_lookupuser(this);">
	</div>
	<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
	</form>


	<div style="padding-top:10px;">
	<a class="recadder" onclick="addtab('user_new','<?tr('list_user_add_tab');?>','newuser');"> <img src="imgs/t.gif" class="img-addrec"><?tr('list_user_add');?></a>
	</div>
</div>

<div id="userlist">
<?		
	}

	$query="select * from users ";
	if ($key!='') $query.=" where (login like '$key%' or dispname like '%$key%') ";
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
<div class="listpager">
<?echo $page+1;?> of <?echo $maxpage+1;?>
&nbsp;
<a href=# onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv_core__users&page=<?echo $page-1;?>&mode=embed');return false;">&laquo; Prev</a>
|
<a href=# onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv_core__users&page=<?echo $page+1;?>&mode=embed');return false;">Next &raquo;</a>
</div>
<?		
	}
	
	$query.=" order by userid=$myuserid desc, virtualuser, login limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$userid=$myrow['userid'];
		$login=$myrow['login'];
		$dispname=noapos(htmlspecialchars($myrow['dispname']));
		$virtual=$myrow['virtualuser'];
		
		$usertitle="$login"; //change this if needed
		
		$dbusertitle=noapos(htmlspecialchars($usertitle));
		$groupnames=$myrow['groupnames'];
		$hash=substr(md5($groupnames),0,6);
		if ($virtual) $hash='ffffff';
		
?>
<div class="listitem" style="border-left:solid 3px #<?echo $hash;?>;padding-left:5px;"><a onclick="showuser(<?echo $userid;?>,'<?echo $dbusertitle;?>');"><?echo $usertitle;?><br><?echo $usertitle!=$dispname?$dispname:'';?></a></div>
<?		
	}//while
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a><?tr('list_users');?></a>';
ajxjs(self.showuser,'users_js.php');
</script>
<?	
	}//embed mode

}

