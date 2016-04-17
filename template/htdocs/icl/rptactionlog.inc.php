<?php

function rptactionlog(){
	global $db;
	global $codepage;

	$now=time();
	
	$day=date('j',$now);
	$mon=date('n',$now);
	$year=date('Y',$now);

	global $paytypes;
	global $paymethods;

	//override date stamp
	$ds=explode('-',GETSTR('date'));
	if (count($ds)==3){
		$day=$ds[2];
		$mon=$ds[1];
		$year=$ds[0];
		$now=mktime(10,10,10,$mon,$day,$year);	
	}

	$key=GETSTR('key');
	$opairs=GETSTR('pairs');
	$pairs=explode(';',$opairs);

	$start=mktime(0,0,0,$mon,$day,$year);
	$end=mktime(23,59,59,$mon,$day,$year);
	
	// $prevday=date('Y-n-j',$start-3600);
	// $nextday=date('Y-n-j',$end+3600);

	$query="select * from ".TABLENAME_ACTIONLOG." left join ".TABLENAME_USERS." on ".TABLENAME_ACTIONLOG.".userid=".TABLENAME_USERS.".userid ";

	if ($key!=''||$opairs!='') {
		$query.=" where alogid!=0 and logmessage!='' ";
		if ($key!='') $query.=" and (logmessage like '%$key%' or login like '$key%' or rawobj like '%$key%' or logname like '%$key%') ";
		foreach ($pairs as $pair){
			$parts=explode('=',$pair);
			$k=trim($parts[0]);
			$v=trim($parts[1]);
			if ($k==''||$v=='') continue;
			$query.=" and rawobj like '%\"$k\":\"$v\"%' ";
		
		}

	}  else {
		$query.=" where logmessage!='' and logdate>='$start' and logdate<='$end' ";
		
		$q="select logdate from ".TABLENAME_ACTIONLOG." where logmessage!='' and logdate<'$start' order by logdate desc limit 1";
		$rs=sql_query($q,$db);
		if ($myrow=sql_fetch_array($rs)) $prevday=date('Y-n-j',$myrow['logdate']);

		$q="select logdate from ".TABLENAME_ACTIONLOG." where logmessage!='' and logdate>'$end' order by logdate limit 1";
		$rs=sql_query($q,$db);
		if ($myrow=sql_fetch_array($rs)) $nextday=date('Y-n-j',$myrow['logdate']);
				
	}

	$query.=" order by logdate desc";

	$rs=sql_query($query,$db);

?>
<div class="section">
<form onsubmit="reloadtab('actionlog',null,'rptactionlog&key='+encodeHTML(gid('actionlog_key').value)+'&pairs='+encodeHTML(gid('actionlog_pairs').value),null,null,{persist:true});return false;">
Search: <input id="actionlog_key" placeholder="Keyword in Action" value="<?echo stripslashes($key);?>"> 
	<input id="actionlog_pairs" placeholder="Advanced Pattern" value="<?echo stripslashes($opairs);?>"> <input type="submit" value="Go">
</form>

<div style="padding:20px 30px;font-size:16px;<?if ($key!=''||$opairs!='') echo 'display:none;';?>">
<?if ($prevday){?>
<a onclick="reloadtab('actionlog',null,'rptactionlog&date=<?echo $prevday;?>',null,null,{persist:true});"><img class="img-calel" width="5" height="12" src="imgs/t.gif"></a> 
<?}?>
&nbsp; &nbsp; <span style="font-size:18px;"><?echo date('M j, Y',$now);?></span>
&nbsp; &nbsp;
<?if ($nextday){?>
<a onclick="reloadtab('actionlog',null,'rptactionlog&date=<?echo $nextday;?>',null,null,{persist:true});"><img class="img-caler" width="5" height="12" src="imgs/t.gif"></a>
<?}?>
</div>

<div class="stable">
<table cellpadding="4">
<tr>
	<td style="padding-right:20px;"><b>Time</b></td><td><b>User</b></td><td><b>Action</b></td><td><b>Extra</b></td>
</tr>
<?
	while ($myrow=sql_fetch_array($rs)){
		$username=$myrow['login'];
		if ($username=='') $username='<span style="color:#ee6666;">'.$myrow['logname'].'</span>';
		$logdate=$myrow['logdate'];
		$dlogdate=date('H:i:s',$logdate);
		if ($key!=''||$opairs!='') $dlogdate=date('Y-n-j H:i:s',$logdate);
		$logmessage=$myrow['logmessage'];
		$extra='';
		$obj=json_decode($myrow['rawobj'],1);
		foreach ($obj as $k=>$v) $extra.="; $k=$v";
		$extra=trim($extra,'; ');
?>
<td valign="top"><?echo $dlogdate;?></td>
<td valign="top"><?echo $username;?></td><td><?echo $logmessage;?></td>
<td valign="top"><?echo $extra;?></td>
</tr>
<?
	
	}//while
?>
</table>
</div>

</div>
<?
	
}