<?php

include 'icl/user_nav.inc.php';

function listusers(){
	
	global $db;
	global $WSS_INTERNAL_HOST;
		
	$user=userinfo();
	$myuserid=$user['userid'];
	$gsid=$user['gsid'];	
	
	$activeagents=array();
	if (class_exists('Redis')){	
	
	    global $redis;
	    $valid=0;
	    if (!isset($redis)){
		    try{
	            $redis=new Redis();
	            $redis->connect($WSS_INTERNAL_HOST,REDIS_PORT);
	            $valid=1;
            } catch (Exception $e){
	         	//echo "warn: cannot connect to Redis server";
            }
	    } else $valid=1;
	    
	    if ($valid){
		    $agentmap=json_decode($redis->get(REDIS_PREFIX.'agentmap'),1);
		    $activeagents=isset($agentmap[$gsid])?$agentmap[$gsid]:array();
	    }
	    
    }
		
	$mode=SGET('mode');
	$key=SGET('key',0);
	
	$page=isset($_GET['page'])?intval($_GET['page']):0;
	
	
	
	//vendor auth 1
			
	if (!isset($user['groups']['accounts'])) die('<div class="section">You cannot manage user accounts</div>');
	
	if ($mode!='embed'){

?>
<div class="section">
<div class="listbar">

	<form class="listsearch" onsubmit="_inline_lookupuser(gid('userkey'));return false;" style="position:relative;">
		<div class="listsearch_">
			<input onfocus="document.hotspot=this;" id="userkey" class="img-mg" onkeyup="_inline_lookupuser(this);" autocomplete="off">
			<img src="imgs/inpback.gif" class="inpback" onclick="inpbackspace('userkey');_inline_lookupuser(gid('userkey'));">
		</div>
		<input type="image" src="imgs/mg.gif" class="searchsubmit" value=".">
		<?php makehelp('userlistlookup','listviewlookup',1);?>
	</form>

	<div style="padding-top:10px;position:relative;">
		<a class="recadder" onclick="closetab('user_new');addtab('user_new','<img src=&quot;imgs/t.gif&quot; class=&quot;ico-user&quot;><?php tr('list_user_add_tab');?>','newuser');"> <img src="imgs/t.gif" class="img-addrec"><?php tr('list_user_add');?></a>
		<span class="list_to_dash" style="position:absolute;top:3px;right:5px;" onclick="quicklist_to_dash();">&#11122;</span>
	</div>
</div>

<div id="userlist">
<?php		
	}
	
	user_shownavs('userlist','slv_core__users');
	
	$sqlfilters=user_sqlfilters();
	
	$params=array($gsid);
	$basequery=" from ".TABLENAME_USERS." where ".COLNAME_GSID."=? ".$sqlfilters['clauses'];
	$params=array_merge($params,$sqlfilters['params']);	

	
	//vendor auth 2
	
	$sel='*';	
	$cquery="select count(*) as c $basequery ";
	$query="select $sel $basequery ";		
	$rs=sql_prep($cquery,$db,$params);
		
	$myrow=sql_fetch_assoc($rs);
	$count=$myrow['c'];
	
	$pagelead=0;
	$perpage=20;
	$dperpage=$perpage;
	
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$perpage*$page;
	if ($page>0) {$start-=$pagelead;$dperpage+=$pagelead;if ($start<0) $start=0;}

	$pager='';
	if ($maxpage>0){
		ob_start();
?>
<div class="listpager">
<a href=# class="hovlink" onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv_core__users&page=<?php echo $page-1;?>&mode=embed&key='+encodeHTML(gid('userkey').value)+gid('searchfilter_user').value);return false;"><img src="imgs/t.gif" class="img-pageleft">Prev</a>
&nbsp;
<a class="pageskipper" onclick="var pagenum=sprompt('Go to page:',<?php echo $page+1;?>);if (pagenum==null||parseInt(pagenum,0)!=pagenum) return false;ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv_core__users&key='+encodeHTML(gid('userkey').value)+gid('searchfilter_user').value+'&page='+(pagenum-1)+'&mode=embed');return false;"><?php echo $page+1;?></a> of <?php echo $maxpage+1;?>
&nbsp;
<a href=# class="hovlink" onclick="ajxpgn('userlist',document.appsettings.codepage+'?cmd=slv_core__users&page=<?php echo $page+1;?>&mode=embed&key='+encodeHTML(gid('userkey').value)+gid('searchfilter_user').value);return false;">Next<img src="imgs/t.gif" class="img-pageright"></a>
</div>
<?php		
		$pager=ob_get_clean();
	}
	
	echo $pager;
	
	//vendor auth 3
	
	$query.=" order by  virtualuser,active desc, userid=? desc, login limit $start,$dperpage ";	
	array_push($params,$myuserid);

	$rs=sql_prep($query,$db,$params);

	
	$pageleadidx=0;
	
	$lastmode=0;
	
	$dusermodes=array(0=>'Active Users',1=>'Deactivated Users',2=>'Virtual Accounts');
	
	while ($myrow=sql_fetch_array($rs)){
		$userid=$myrow['userid'];
		$login=$myrow['login'];
		$dispname=htmlspecialchars($myrow['dispname']);
		$virtual=$myrow['virtualuser'];
		$active=$myrow['active'];
		
		$usertitle="$login"; //change this if needed
		
		$online=in_array($userid,$activeagents);
		
		$dbusertitle=noapos(htmlspecialchars(htmlspecialchars($usertitle)));
		$groupnames=$myrow['groupnames'];
		$hash='#'.substr(md5($groupnames),0,6);
		
		$class='';
		
		$usermode=0;
		$style='';

		if (!$active) {$usermode=1;$hash='transparent';$class='recbad';}
		if ($virtual) {$usermode=2;$hash='transparent';$class='recdim';}	
		
		if ($usermode!=$lastmode){
		?>
		<div class="sectionheader">
			<?php echo $dusermodes[$usermode];?>
		</div>
		<?php
			$lastmode=$usermode;	
		}	
		
		
		if ($pagelead!=0&&$pageleadidx==$pagelead&&$page>0){
?>

<div style="font-size:8px;color:#333333;border-bottom:dotted 1px #444444;text-align:center;">PAGE <?php echo $page+1;?></div>
<?php			
		}
		
		//vendor auth 4
		
?>
<div class="listitem" style="<?php if ($pageleadidx<$pagelead&&$page>0) echo 'opacity:0.6;';?>border-left:solid 3px <?php echo $hash;?>;padding-left:5px;">
<a onclick="showuser('<?php echo $userid;?>','<?php echo $dbusertitle;?>');">
	<span class="<?php echo $class;?>"><?php echo htmlspecialchars($usertitle);?></span>
	<?php if ($online){?>
	&nbsp; <span class="labelbutton">online</span>
	<?php }?>

	<br>
	<?php echo htmlspecialchars($usertitle!=$dispname?$dispname:'');?>
</a></div>
<?php		
		$pageleadidx++;

	}//while
	
	
	echo $pager;
	
	if ($mode!='embed'){
?>
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a><?php tr('list_users');?></a>';
ajxjs(<?php jsflag('showuser');?>,'users.js');
ajxjs(<?php jsflag('setaccountpass');?>,'accounts.js');
</script>
<?php	
	}//embed mode

}

