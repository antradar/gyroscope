<?php

function GETVAL($key){ $val=trim($_GET[$key]); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val;}
function QETVAL($key){ $val=trim($_POST[$key]); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val;}
function noapos($val,$trimnl=1){$val=addslashes($val); if ($trimnl) $val=str_replace(array("\n","\r","\r\n"),' ',$val); return $val;}
function GETSTR($key,$trim=1){$val=$_GET[$key];if ($trim) $val=trim($val);return noapos($val,0);}
function QETSTR($key,$trim=1){$val=$_POST[$key];if ($trim) $val=trim($val);return noapos($val,0);}

function GETCUR($key){$val=trim($_GET[$key]); $val=str_replace(_tr('currency_separator_thousands'),'',$val); $val=str_replace(_tr('currency_separator_decimal'),'.',$val); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val; }
function QETCUR($key){$val=trim($_POST[$key]); $val=str_replace(_tr('currency_separator_thousands'),'',$val); $val=str_replace(_tr('currency_separator_decimal'),'.',$val); if (!is_numeric($val)) apperror('apperror:invalid parameter '.$key); return $val; }

function SGET($key,$trim=1){$val=isset($_GET[$key])?$_GET[$key]:'';if ($trim) $val=trim($val);return $val;}
function SQET($key,$trim=1){$val=isset($_POST[$key])?$_POST[$key]:'';if ($trim) $val=trim($val);return $val;}

function hspc($str){return htmlspecialchars($str,ENT_SUBSTITUTE|ENT_COMPAT);}

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
	if (date('Y-n-j',$stamp)!=(intval($parts[0])).'-'.(intval($parts[1])).'-'.(intval($parts[2])) ) apperror('Invalid date');
	
	return $stamp;	
}

function apperror($str,$msg=null,$clientfunc=null){		
	header('apperror: '.tabtitle($str));
	if (isset($clientfunc)) header('ERRFUNC: '.$clientfunc);
	if (isset($msg)) {
		echo $msg;die(); //display custom message in the error body
	} else die('apperror - '.$str); //display default message as error body
}

function tabtitle($str) {return rawurlencode($str);}

function makelookup($id,$fullscale=0){
?>
<div class="minilookup" id="<?php echo $id;?>_lookup"><a id="<?php echo $id;?>_lookup_closer" class="labelbutton closer" onclick="gid('<?php echo $id;?>_lookup').style.display='none';"><?php tr('lookup_closer')?></a>
<div id="<?php echo $id;?>_lookup_view" class="lookupview"<?php if ($fullscale) echo ' style="height:auto;overflow:normal;"';?>></div></div>
<?php	
}

function cancelpickup($id){
?>
<a class="labelbutton" href=# onclick="cancelpickup('<?php echo $id;?>');return false;"><?php tr('pickup_edit');?></a>
<?php	
}

function makechangebar($key,$action,$autosavetimeout=null){
?>
<div id="changebar_<?php echo $key;?>" class="changebar">
<div class="changebar_anchor">
<div class="changebar_view">
<div class="changebar_content">
	<acronym title="Ctrl/CMD + S"><button class="changebar_button" onclick="<?php echo $action;?>" id="changebar_button_<?php echo $key;?>">Save Changes</button></acronym>
	<span id="autosavercountdown_<?php echo $key;?>" class="autosaver"></span>
	<?php if (isset($autosavetimeout)) {?><input type="hidden" id="autosavetimeout_<?php echo $key;?>" value="<?php echo trim($autosavetimeout);?>"><?php } ?>
</div>
</div>
<div class="changebar_shadow">
	<button>&nbsp;</button>
</div>
</div>
</div>
<?php	
}

function makesavebar($key,$title=null){
	if (!isset($title)) $title=_tr('record_updated');
?>
<div id="savebar_<?php echo $key;?>" class="savebar">
<div class="savebar_anchor">
<div class="savebar_view">
<div class="savebar_content">
	<?php echo $title;?>
</div>
</div>
</div>
</div>
<?php	
}

function makehelp($id,$text,$once=0,$dx=0){
	global $db;
	global $helpspots; //defined in dict.[lang].php
	global $userhelpspots;
	
	
	$topic='';
	if ($once){
		if (!isset($helpspots[$text])) {
			echo 'invalid helpspot configuration';
			return;	
		}
		
		if (!isset($userhelpsposts)){
			$user=userinfo();
			$userid=$user['userid'];
			$userhelpspots=cache_get(TABLENAME_GSS.'userhelpspots_'.$userid);
			if (!is_array($userhelpspots)){
				
				$userhelpspots=array();
				$query="select * from ".TABLENAME_USERHELPSPOTS." where userid=?";
				$rs=sql_prep($query,$db,$userid);
				while ($myrow=sql_fetch_assoc($rs)) array_push($userhelpspots,$myrow['helptopic']);
			
				cache_set('userhelpspots_'.$userid,$userhelpspots,3600*24*7);
			}
		}
		
		$topic=$text;
		$text=$helpspots[$text];
		
		if (in_array($topic,$userhelpspots)) return;
		
	}//once
?>
	<span id="phelpspot_<?php echo $id;?>" helptopic="<?php echo $topic;?>" class="phelpspot<?php if (!$once) echo '_static';?>">
		<?php if ($once){?>
		<span id="helpanchor_<?php echo $id;?>" style="cursor:pointer;" onclick="showhelpspot('<?php echo $id;?>',<?php echo $once;?>);">
			<span class="helpdot"></span>
			<span class="helppulse"></span>
		</span>
		<?php } else {?>
		<img id="helpanchor_<?php echo $id;?>" class="helpanchor_static" onclick="showhelpspot('<?php echo $id;?>',<?php echo $once;?>);" src="imgs/dot.gif">		
		<?php }?>	
		
		<div id="helpspot_<?php echo $id;?>" class="helpspot<?php if (!$once) echo '_static';?>" style="<?php if ($once) echo 'left:'.(15+$dx).'px;'; else echo 'right:-80px;';?>;">
			<?php echo $text;?>
			<?php if ($once){?>
			<div class="helpack">
				<button onclick="hidehelpspot('<?php echo $id;?>','<?php echo $topic;?>',<?php echo $once;?>,'<?php emitgskey('ackhelpspot_'.$topic);?>');">Got it!</button>
			</div>
			<?php }?>
		</div>
	</span>
<?php
}

function diffdbchanges($before,$after,$masks=null,$omits=null){

	$dbchanges=array();
	
	foreach ($before as $k=>$v){
		if (is_array($omits)&&in_array($k,$omits)) continue;
		$dk=$k;
		$dv=$v.' -> '.$after[$dk];
		if (is_array($masks)&&in_array($k,$masks)) $dv=' *changed*';
		if ($v!=$after[$k]) $dbchanges[$dk]=$dv;
	}
	
	return $dbchanges;
}


function streamaction($wssid,$rectype,$recid,$gsid,$userid,$extra=null){
	global $WSS_INTERNAL_HOST;
	
	if (class_exists('Redis')){	
	
	    global $redis;
	    $valid=0;
	    if (!isset($redis)){
		    try{
	            $redis=new Redis();
	            $redis->connect($WSS_INTERNAL_HOST,6379);
	            $valid=1;
            } catch (Exception $e){
	         	echo "warn: cannot connect to Redis server";
            }
	    } else $valid=1;
	    	    
	    if (!$valid) return;

	    $obj=array(
	            'wssid'=>$wssid,
	            'rectype'=>$rectype,
	            'recid'=>$recid,
	            'gsid'=>$gsid,
	            'userid'=>$userid,
	            'extra'=>$extra
	    );
	    $redis->lpush('actions',json_encode($obj));
		
		return;
	}
	
	global $WSS_INTERNAL_KEY; //defined in lb.php
	global $WSS_INTERNAL_HOST;
	global $WSS_INTERNAL_PORT;
	global $usewss; //defined in lb.php
	global $wssecret;
	
	if (!$usewss) return;
	
	if ($WSS_INTERNAL_KEY==''||$WSS_INTERNAL_HOST=='') return;

	$relay=array(
			'sid'=>$wssid,
			'userid'=>$userid,
			'gsid'=>$gsid,
			'type'=>'update',
			'rectype'=>$rectype,
			'recid'=>$recid	
	);
	
	$wsskey=md5($wssecret.$gsid.date('Y-n-j-H')).'-'.$gsid;	
	$auth=sha1($WSS_INTERNAL_KEY.implode('-',$relay).date('Y-n-j-H'));
			
	$head = "GET /?WSS".$wsskey."= HTTP/1.1"."\r\n".
	            "Upgrade: WebSocket"."\r\n".
	            "Connection: Upgrade"."\r\n".
	            "Origin: http://localhost/\r\n".
	            "Host: ".$WSS_INTERNAL_HOST."\r\n\r\n";
    
	//WebSocket handshake
	$sock = @fsockopen($WSS_INTERNAL_HOST, $WSS_INTERNAL_PORT, $errno, $errstr, 0.1);
	if (!$sock) return;
	fwrite($sock, $head );// or die('error:'.$errno.':'.$errstr);
	$headers = fread($sock, 2000);	
	$req=array(
		'type'=>'relay',
		'auth'=>$auth,
		'relay'=>$relay	
	);
	
	$data=json_encode($req);
	fwrite($sock, $data ) or die('error:'.$errno.':'.$errstr);
	
	fclose($sock);		
}

function logaction($message,$rawobj=null,$syncobj=null,$gsid=0){
	global $WSS_INTERNAL_KEY; //defined in lb.php
		
	if (is_callable('userinfo')) {
		$user=userinfo();
		$userid=$user['userid'];
		$gsid=$user['gsid'];
		$logname=$user['login'];
		//$logname=str_replace("'",'',$logname);
	} else {
		$userid=0; $logname='';
	}

	global $db;
	$wssid=intval($_GET['wssid_']);

	if (!isset($rawobj)) $rawobj=array();
	//$message=noapos($message);

	$cobj=array();
	foreach ($rawobj as $k=>$v){
		if (is_array($v)) continue;
		$v=noapos($v);
		$v=str_replace('"','&quot;',$v);
		$cobj[$k]=$v;
	}
	
	$obj=json_encode($cobj);
	//$obj=str_replace("\\'","'",$obj);

	$now=time();
	
	if (isset($syncobj)){
		$sid=$wssid;
		$rectype=$syncobj['rectype'];
		$recid=$syncobj['recid'];
		if ($WSS_INTERNAL_KEY==''){ //save the message regardless of whether a main message is present
			$query="insert into ".TABLENAME_ACTIONLOG." (userid,".COLNAME_GSID.",logname,logdate,logmessage,rawobj,sid,rectype,recid) values (?,?,?,?,?,?,?,?,?)";
			sql_prep($query,$db,array($userid,$gsid,$logname,$now,$message,$obj,$sid,$rectype,$recid));
		} else {
			if ($message!=''){//stream directly, but still log the main message
				$query="insert into ".TABLENAME_ACTIONLOG." (userid,".COLNAME_GSID.",logname,logdate,logmessage,rawobj,wssdone) values (?,?,?,?,?,?,?)";
				sql_prep($query,$db,array($userid,$gsid,$logname,$now,$message,$obj,1));
			}	
			streamaction($sid,$rectype,$recid,$gsid,$userid);
			
		}
	} else {
		$query="insert into ".TABLENAME_ACTIONLOG." (userid,".COLNAME_GSID.",logname,logdate,logmessage,rawobj) values (?,?,?,?,?,?)";
		sql_prep($query,$db,array($userid,$gsid,$logname,$now,$message,$obj));
	}
	
}

function phoneformat($phone){
	$nphone=preg_replace('/[^\d]/','',trim($phone));
	if (strlen($nphone)!=10) return $phone;
	
	$areacode=substr($nphone,0,3);
	$parta=substr($nphone,3,3);
	$partb=substr($nphone,6,4);
	
	return "($areacode) $parta-$partb";
	
}

function timeformat($sec){
	$sec_num = intval($sec);
	$hours = floor($sec_num / 3600);
	$minutes = floor(($sec_num - ($hours * 3600)) / 60);
	$seconds = $sec_num - ($hours * 3600) - ($minutes * 60);
		
	if ($hours   < 10) $hours = "0".$hours;
	if ($minutes < 10) $minutes = "0".$minutes;
	if ($seconds < 10) $seconds = "0".$seconds;
	$time  = "$hours:$minutes:$seconds";
	return $time;	
}

function currency_format($val,$digits=2,$bracket=0,$omitzero=0){
	$separator_decimal=_tr('currency_separator_decimal');
	$separator_thousands=_tr('currency_separator_thousands');

	$inverted=0;	
	if ($bracket||$omitzero){
		$val=round($val,2);
		if ($val==0&&$omitzero) return '';
		if ($val<0&&$bracket) {$val=$val*-1;$inverted=1;}	
	}
	
	$num=number_format($val,$digits,$separator_decimal,$separator_thousands);
	if ($inverted) $num="($num)";
	
	return $num;
	
}

function dumpgsdbprofile($sort=0){

	global $gsdbprofile;
	if (!isset($gsdbprofile)) return;
	
	if ($sort){
	uasort($gsdbprofile,function($a,$b){
		$ta=$a['time']; $tb=$b['time'];
		if ($ta===$tb) return 0; if ($ta>$tb) return -1; else return 1;
	});		
	}
	
	echo '<pre>'; print_r($gsdbprofile); echo '</pre>';	
}

