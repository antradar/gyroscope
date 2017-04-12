<?php

include 'lb.php';
$ip=$_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['REMOTE_ADDR6'])) $ip=$_SERVER['REMOTE_ADDR6'];
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta id="viewport" name="viewport" content="width=device-width" />
	<title>Gyroscope Fitness Test</title>
	<link rel="stylesheet" href="sysinfo_sd.css" type="text/css" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="canvas">
<?php

$a=12147483648;
$b=$a|0;
include 'connect.php';
include 'auth.php';


$xa=microtime(1);
$query="select sleep(0.8)";
$rs=@sql_query($query,$db,MYSQLI_ASYNC);
if (!isset($rs)) die('Fatal Error: MySQLi Async mode not enabled');

$xb=microtime(1);

if ($SQL_ENGINE=="MySQLi"&&is_callable('mysqli_reap_async_query')){
	$processed = 0;
	do {
	    $links = $errors = $reject = array();
	    $links[] = $errors[] = $reject[] = $db;
	    if (!mysqli_poll($links, $errors, $reject, 1)) {
	        continue;
	    }
	    foreach ($links as $link) {
	        if ($result = $link->reap_async_query()) {
	            if (is_object($result)) mysqli_free_result($result);
	        } else die(sprintf("MySQLi Error: %s", mysqli_error($link)));
	        $processed++;
	    }
	} while ($processed < 1);
}

$query="show variables like '%character%'";
$rs=sql_query($query,$db);

$csets=array();

while ($myrow=sql_fetch_assoc($rs)){
	$csets[$myrow['Variable_name']]=$myrow['Value'];
}

$trx=null;

$query="show variables like '%at_trx_commit%'";
$rs=sql_query($query,$db);
if ($myrow=sql_fetch_assoc($rs)) $trx=$myrow['Value'];

$tablespace='';

$query="show variables like '%innodb_file_per_table%'";
$rs=sql_query($query,$db);
if ($myrow=sql_fetch_assoc($rs)) $tablespace=$myrow['Value'];


//echo '<pre>'; print_r($csets); echo '</pre>';

$seg=memory_get_usage(1)/1024/256;
if ($seg<1) $seg=sprintf('%.2f',$seg);

include 'forminput.php';

$now=time();
$enc=encstr('test'.$now,$dbsalt);
$dec=decstr($enc,$dbsalt);

$ciphers=array();
if (is_callable('mcrypt_list_algorithms')) $ciphers=mcrypt_list_algorithms();

$tests=array(
'Gyroscope Version'=>array('res'=>2,'message'=>GYROSCOPE_VERSION),
'MCrypt'=>array('res'=>is_callable('mcrypt_get_iv_size')),
'Cipher'=>array('res'=>in_array('rijndael-256',$ciphers)),
'Salt Length'=>array('res'=>in_array(strlen($dbsalt),array(16,24,32))),
'Decryption'=>array('res'=>$dec=='test'.$now),
'Segment size'=>array('res'=>2,'message'=>$seg),
'SQL Connector'=>array('res'=>2,'message'=>$SQL_ENGINE),
'MySQL client charset'=>array('res'=>$csets['character_set_client']=='latin1','message'=>$csets['character_set_client']),
'MySQL connection charset'=>array('res'=>$csets['character_set_connection']=='latin1','message'=>$csets['character_set_connection']),
'MySQL database charset'=>array('res'=>$csets['character_set_database']=='latin1','message'=>$csets['character_set_database']),
'MySQL results charset'=>array('res'=>$csets['character_set_results']=='latin1','message'=>$csets['character_set_results']),
'MySQL server charset'=>array('res'=>$csets['character_set_server']=='latin1','message'=>$csets['character_set_server']),
'MySQLi Parallel Queries'=>array('res'=>($xb-$xa<0.5)&&is_callable('mysqli_poll')),
'InnoDB trx level'=>array('res'=>$trx==2,'message'=>$trx),
'InnoDB table space'=>array('res'=>$tablespace=='ON','message'=>$tablespace),
'64-bit integer'=>array('res'=>$a==$b,'message'=>''),
'Date beyond 2038'=>array('res'=>'2412-12-12'==date('Y-n-j',13978113132),'message'=>''),
'IPv6 Socket'=>array('res'=>strpos($ip,':')!==false,'message'=>$ip),
'Server'=>array('res'=>2,'message'=>$_SERVER['SERVER_SOFTWARE'])
);

$classes=array('no','yes','');

foreach ($tests as $test=>$result){
	$res=$result['res'];
	$message=isset($result['message'])?$result['message']:'';
	if ($message=='') $message=$res?'Yes':'No';
	
	$resclass=$classes[$res];
	
?>
<div class="testrow">
	<div class="testitem"><?echo $test;?></div>
	<div class="testresult"><span class="res_<?echo $resclass;?>"><?echo $message;?></span></div>
	<div class="clear"></div>
</div>
<?		
}

?>
<div class="testrow">
	<div class="testitem">HD Sprite</div>
	<div class="testresult">
		<div id="gyroscope"></div>
		<span id="hdbg"><span class="res_no">No</span></span>
	</div>
	<div class="clear"></div>
</div>

<div class="testrow">
	<div class="testitem">WebSocket</div>
	<div class="testresult">
		<span id="wss"><span class="res_no">No</span></span>
	</div>
	<div class="clear"></div>
</div>


</div>
<script src="nano.js"></script>
<script>
	if (typeof(document.documentElement.style.backgroundSize)=='string'){
		gid('hdbg').innerHTML='<span class="res_yes">Yes</span>';	
		if (window.devicePixelRatio>1) 	ajxcss(self.bgupgrade,'sysinfo_hd.css?hb='+hb());
		else gid('hdbg').innerHTML='<span style="color:#ffab00;">Deactivated</span>';
	}
	if (window.WebSocket) gid('wss').innerHTML='<span class="res_yes">Yes</span>';
</script>
</body>
</html>
