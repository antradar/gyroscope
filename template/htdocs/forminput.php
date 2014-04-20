<?php

function GETVAL($key){ $val=$_GET[$key]; if (!is_numeric($val)) die('invalid parameter'); return $val;}
function noapos($val){$val=str_replace("\\'","'",$val);$val=str_replace("\'","'",$val);$val=str_replace("'","\'",$val); return $val;}
function GETSTR($key){ $val=decode_unicode_url($_GET[$key]); return noapos($val); }

function decode_unicode_url($str){
	$str=utf8_encode($str);
	//$str=htmlentities($str); //French accent fix
	
	$res = '';
	
	$i = 0; $max=strlen($str)-6;
	
	while ($i<=$max){
		$c=$str[$i];
		if ($c=='%'&&$str[$i + 1]=='u'){
			$v=hexdec(substr($str,$i+2,4));
			$i+=6;
			
			if ($v<0x0080) $c=chr($v); //1 byte
			else if ($v<0x0800) $c=chr((($v&0x07c0)>>6)|0xc0).chr(($v&0x3f)|0x80); // 2 bytes: 110xxxxx 10xxxxxx
			else $c=chr((($v&0xf000)>>12)|0xe0).chr((($v&0x0fc0)>>6)|0x80).chr(($v&0x3f)|0x80); // 3 bytes: 1110xxxx 10xxxxxx 10xxxxxx
		} else $i++;
		
		$res.=$c;
	}//while
	
	return $res . substr($str, $i);
}

function date2stamp($date,$hour=1,$min=1,$sec=1){
	$parts=explode('-',$date);
	return mktime($hour,$min,$sec,$parts[1],$parts[2],$parts[0]);	
}


function makelookup($id,$fullscale=0){
?>
<div class="minilookup" id="<?echo $id;?>_lookup"><a id="<?echo $id;?>_lookup_closer" class="labelbutton closer" onclick="gid('<?echo $id;?>_lookup').style.display='none';">close</a>
<div id="<?echo $id;?>_lookup_view" class="lookupview"<?if ($fullscale) echo ' style="height:auto;overflow:normal;"';?>></div></div>
<?	
}

function cancelpickup($id){
?>
<a class="labelbutton" onclick="cancelpickup('<?echo $id;?>');">edit</a>
<?	
}

function logaction($message,$rawobj=null){
	$user=userinfo();
	$userid=$user['userid']+0;
	global $db;

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

	$query="insert into actionlog(userid,logdate,logmessage,rawobj) values ($userid,'$now','$message','$obj')";
	sql_query($query,$db);
}

