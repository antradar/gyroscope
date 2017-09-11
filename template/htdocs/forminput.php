<?php

function GETVAL($key){ $val=trim($_GET[$key]); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val;}
function QETVAL($key){ $val=trim($_POST[$key]); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val;}
function noapos($val){return addslashes($val);}
function GETSTR($key,$trim=1){$val=$_GET[$key];if ($trim) $val=trim($val);return noapos($val);}
function QETSTR($key,$trim=1){$val=$_POST[$key];if ($trim) $val=trim($val);return noapos($val);}


function tzconvert($stamp,$src,$dst){
	
	$tz=date_default_timezone_get();
	
		date_default_timezone_set($src);
		$y=date('Y',$stamp);
		$n=date('n',$stamp);
		$j=date('j',$stamp);
		$h=date('H',$stamp);
		$i=date('i',$stamp);
		$s=date('s',$stamp);
				
		date_default_timezone_set($dst);
		$nstamp=mktime($h,$i,$s,$n,$j,$y);
			
	date_default_timezone_set($tz);
	return $nstamp;	
}

function date2stamp($date,$hour=0,$min=0,$sec=0){
	$parts=explode('-',trim($date));
	if (count($parts)!=3) return null;
	$stamp=mktime($hour,$min,$sec,$parts[1],$parts[2],$parts[0]);
	if (date('Y-n-j',$stamp)!=($parts[0]+0).'-'.($parts[1]+0).'-'.($parts[2]+0) ) apperror('Invalid date');
	
	return $stamp;	
}

function apperror($str,$msg=null,$func=null){if (!isset($msg)) $msg=$str;header('apperror: '.tabtitle($str));if (isset($func)) header('ERRFUNC: '.$func);die('apperror - '.$msg);}

function tabtitle($str) {return rawurlencode($str);}

function makelookup($id,$fullscale=0){
?>
<div class="minilookup" id="<?echo $id;?>_lookup"><a id="<?echo $id;?>_lookup_closer" class="labelbutton closer" onclick="gid('<?echo $id;?>_lookup').style.display='none';"><?tr('lookup_closer')?></a>
<div id="<?echo $id;?>_lookup_view" class="lookupview"<?if ($fullscale) echo ' style="height:auto;overflow:normal;"';?>></div></div>
<?	
}

function cancelpickup($id){
?>
<a class="labelbutton" href=# onclick="cancelpickup('<?echo $id;?>');return false;"><?tr('pickup_edit');?></a>
<?	
}

function makechangebar($key,$action){
?>
<div id="changebar_<?echo $key;?>" class="changebar">
<div class="changebar_anchor">
<div class="changebar_view">
<div class="changebar_content">
	<button onclick="<?echo $action;?>">Save Changes</button>
</div>
</div>
<div class="changebar_shadow">
	<button>&nbsp;</button>
</div>
</div>
</div>
<?	
}

function logaction($message,$rawobj=null,$syncobj=null){
	if (is_callable('userinfo')) {
		$user=userinfo();
		$userid=$user['userid']+0;
		$logname=$user['login'];
		$logname=str_replace("'",'',$logname);
	} else {
		$userid=0; $logname='';
	}

	global $db;
	$wssid=isset($_GET['wssid_'])?($_GET['wssid_']+0):0;

	if (!isset($rawobj)) $rawobj=array();
	$message=noapos($message);

	$cobj=array();
	foreach ($rawobj as $k=>$v){
		if (is_array($v)) continue;
		$v=noapos($v);
		$v=str_replace('"','&quot;',$v);
		$cobj[$k]=$v;
	}
	
	$obj=json_encode($cobj);
	$obj=str_replace("\\'","'",$obj);

	$now=time();

	$query="insert into ".TABLENAME_ACTIONLOG."(userid,logname,logdate,logmessage,rawobj) values ($userid,'$logname','$now','$message','$obj')";
	
	if ($syncobj!=''){
		$sid=$wssid;
		$rectype=$syncobj['rectype'];
		$recid=$syncobj['recid']+0;
		$query="insert into ".TABLENAME_ACTIONLOG."(userid,logname,logdate,logmessage,rawobj,sid,rectype,recid) values ($userid,'$logname','$now','$message','$obj',$sid,'$rectype',$recid)";
	}
	sql_query($query,$db);
}

function timeformat($sec){
	$sec_num = $sec+0;
	$hours = floor($sec_num / 3600);
	$minutes = floor(($sec_num - ($hours * 3600)) / 60);
	$seconds = $sec_num - ($hours * 3600) - ($minutes * 60);
		
	if ($hours   < 10) $hours = "0".$hours;
	if ($minutes < 10) $minutes = "0".$minutes;
	if ($seconds < 10) $seconds = "0".$seconds;
	$time  = "$hours:$minutes:$seconds";
	return $time;	
}
