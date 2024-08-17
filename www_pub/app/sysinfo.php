<?php

include 'lb.php';
include 'auth.php'; 
//login(); //uncomment in production

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

$user=userinfo();

//$ciphers=openssl_get_cipher_methods();

$seg=memory_get_usage(1)/1024/256;
if ($seg<1) $seg=sprintf('%.2f',$seg);

if ($SQL_ENGINE=='MySQLi'){
	/*
	$xa=microtime(1);
	$query="select sleep(0.8)";
	$rs=@sql_query($query,$db,MYSQLI_ASYNC);
	if (!isset($rs)) die('Error connecting database');
	
	$xb=microtime(1);
	*/
	
	$bindfail=0;

	/*
	try{
	$cparams=array(null,'ss','nobody','1111');
	$func=new ReflectionFunction('mysqli_stmt_bind_param');
	$func->invokeArgs($cparams);
	} catch (Exception $e){
		$bindfail=1;
	}
	*/
		
} else {
	$xa=0;
	$xb=1000;	
}


/*
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
*/

if ($SQL_ENGINE=='MySQL'||$SQL_ENGINE=='MySQLi'){
	
	$hasspatial=0;
	ob_start();
	$query="select st_contains(polygonfromtext('polygon((-2 1,2 1,0 -3,-2 1))'),point(1, -2)) as res ";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$contains=$myrow['res'];
	echo $contains;
	$c=ob_get_clean();
	if (strpos($c,'0')!==false) $hasspatial=1;
	if (strpos($c,'sql_error')!==false) $hasspatial=0;
	
	
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
}


//echo '<pre>'; print_r($csets); echo '</pre>';


include 'encdec.php';
include 'bcrypt.php';
include 'forminput.php';

$now=time();
$enc=encstr('test'.$now,$dbsalt);
$dec=decstr($enc,$dbsalt);

$infostr='';
ob_start();
phpinfo();
$infostr=ob_get_clean();
$jitstatus='n/a';
$jitcode=2;
if (preg_match('/<td class="e">JIT\s*<\/td>\s*<td class="v">([\S\s]*?)<\/td>/',$infostr,$matches)){
	$jitstatus=trim($matches[1]);
	if (strtolower($jitstatus)=='on'||strtolower($jitstatus)=='enabled') $jitcode=1;
	else $jitcode=0;
}

$ciphers=array();
if (is_callable('mcrypt_list_algorithms')) $ciphers=mcrypt_list_algorithms();

$strong=2; $strongmsg='-';

if (is_callable('openssl_random_pseudo_bytes')){
	$ivlen=openssl_cipher_iv_length('AES-256-CBC');
	$iv=openssl_random_pseudo_bytes($ivlen,$strong);
	$strong=$strong?1:0;
	$strongmsg=null;
}

$enckey=GYROSCOPE_PROJECT.'-sysinfo';
$org=md5('test'.$now); //a test message for encryption
$orgenc=encstr($org,$enckey,1); //remote encryption
$orgdec=decstr($orgenc,$enckey,1);

$tests=array(
'Gyroscope Version'=>array('res'=>2,'message'=>'<a href=# onclick="checkver();return false;">'.GYROSCOPE_VERSION.'</a> &nbsp; <nobr><span id="gsver"></span></nobr>'),
'SQL Read Only'=>array('res'=>2,'message'=>$SQL_READONLY?'Yes':'No'),
'EncDec'=>array('res'=>2,'message'=>ENC_DEC),
'EncDec_Remote'=>array('res'=>$org==$orgdec),
'MCrypt'=>array('res'=>is_callable('mcrypt_get_iv_size')),
'Cipher'=>array('res'=>in_array('rijndael-128',$ciphers)),
'OpenSSL'=>array('res'=>is_callable('openssl_encrypt')),
'Strong Crypto'=>array('res'=>$strong,'message'=>$strongmsg),
'Salt Length'=>array('res'=>in_array(strlen($dbsalt),array(16,24,32))),
'Decryption'=>array('res'=>$dec=='test'.$now),
'BCrypt'=>array('res'=>is_callable('password_hash')&&version_compare(PHP_VERSION,'5.3.7')>=0),
'&nbsp; &nbsp; &nbsp; &nbsp; Emulated'=>array('res'=>2,'message'=>'Yes'),
'Native Redis Client'=>array('res'=>class_exists('Redis')),
'Segment size'=>array('res'=>2,'message'=>$seg),
'Number Formatter'=>array('res'=>class_exists('NumberFormatter')),
'SQL Connector'=>array('res'=>2,'message'=>$SQL_ENGINE),
'Co-SQL Connector'=>array('res'=>2,'message'=>isset($SQL_ENGINE2)?$SQL_ENGINE2:'-'),
//'MySQLi Reflect Bind'=>array('res'=>!$bindfail),
'MySQL client charset'=>array('res'=>$csets['character_set_client']=='latin1','message'=>$csets['character_set_client']),
'MySQL connection charset'=>array('res'=>$csets['character_set_connection']=='latin1','message'=>$csets['character_set_connection']),
'MySQL database charset'=>array('res'=>$csets['character_set_database']=='latin1','message'=>$csets['character_set_database']),
'MySQL results charset'=>array('res'=>$csets['character_set_results']=='latin1','message'=>$csets['character_set_results']),
'MySQL server charset'=>array('res'=>$csets['character_set_server']=='latin1','message'=>$csets['character_set_server']),
//'MySQLi Parallel Queries'=>array('res'=>($xb-$xa<0.5)&&is_callable('mysqli_poll')),
'MySQL Geospatial Queries'=>array('res'=>($hasspatial==1)),
'InnoDB trx level'=>array('res'=>$trx==2,'message'=>$trx),
'InnoDB table space'=>array('res'=>$tablespace=='ON','message'=>$tablespace),
'64-bit integer'=>array('res'=>$a==$b,'message'=>''),
'Date beyond 2038'=>array('res'=>'2412-12-12'==date('Y-n-j',13978113132),'message'=>''),
'Direct IP'=>array('res'=>$_SERVER['REMOTE_ADDR']==$_SERVER['RAW_IP'],'message'=>$_SERVER['RAW_IP'].(($_SERVER['REMOTE_ADDR']==$_SERVER['RAW_IP'])?'':' vs. '.$_SERVER['REMOTE_ADDR'])),
'Protected IP'=>array('res'=>2,'message'=>$_SERVER['O_IP']),
'Proxy via'=>array('res'=>2,'message'=>isset($_SERVER['HTTP_VIA'])?$_SERVER['HTTP_VIA']:''),
'IPv6 Socket'=>array('res'=>strpos($ip,':')!==false,'message'=>$ip),
'Server'=>array('res'=>2,'message'=>$_SERVER['SERVER_SOFTWARE']),
'PHP Version'=>array('res'=>2,'message'=>str_replace('+',' + ',phpversion())),
'&nbsp; &nbsp; &nbsp; &nbsp; JIT'=>array('res'=>$jitcode,'message'=>$jitstatus)
);

if (!isset($SQL_ENGINE2)) unset($tests['Co-SQL Connector']);

if (!is_callable('password_hash_fallbackmode')) $tests['&nbsp; &nbsp; &nbsp; &nbsp; Emulated']['message']='No';

if (!isset($_SERVER['HTTP_VIA'])) unset($tests['Proxy via']);

if (ENC_DEC!='MCrypt'){
	$tests['Cipher']['res']=2;	
	if ($tests['MCrypt']['res']==0) {
		$tests['Cipher']['message']='-';
		$tests['MCrypt']['res']=2;
		$tests['MCrypt']['message']='No';
	}
}

if ($SQL_ENGINE!='MySQLi'){
	unset($tests['MySQLi Reflect Bind']);	
}

if ($SQL_ENGINE!='MySQL'&&$SQL_ENGINE!='MySQLi'){
	unset($tests['MySQL client charset']);
	unset($tests['MySQL connection charset']);
	unset($tests['MySQL database charset']);
	unset($tests['MySQL results charset']);
	unset($tests['InnoDB trx level']);
	unset($tests['InnoDB table space']);		
}

if (!isset($user['login'])||$user['login']==''){
	$tests['MCrypt']=array('res'=>2,'message'=>'****');
	$tests['Cipher']=array('res'=>2,'message'=>'****');
	$tests['OpenSSL']=array('res'=>2,'message'=>'****');
	$tests['Strong Crypto']=array('res'=>2,'message'=>'****');
	$tests['Salt Length']=array('res'=>2,'message'=>'****');
	$tests['Decryption']=array('res'=>2,'message'=>'****');
	$tests['Native Redis Client']=array('res'=>2,'message'=>'****');
	$tests['Server']=array('res'=>2,'message'=>'****');		
	$tests['PHP Version']=array('res'=>2,'message'=>'****');
	
	unset($tests['&nbsp; &nbsp; &nbsp; &nbsp; JIT']);
}

if ($jitcode==2){
	unset($tests['&nbsp; &nbsp; &nbsp; &nbsp; JIT']);	
}


$classes=array('no','yes','');

foreach ($tests as $test=>$result){
	$res=$result['res'];
	$message=isset($result['message'])?$result['message']:'';
	if ($message=='') $message=$res?'Yes':'No';
	
	$resclass=$classes[$res];
	
?>
<div class="testrow">
	<div class="testitem"><?php echo $test;?></div>
	<div class="testresult"><span class="res_<?php echo $resclass;?>"><?php echo $message;?></span></div>
	<div class="clear"></div>
</div>
<?php		
}

?>
<div class="testrow">
	<div class="testitem">HD Sprite</div>
	<div class="testresult gsbanner">
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

<?php
	if (!isset($user['login'])||$user['login']==''){
?>
<div style="background:#ffffcc;font-size:14px;margin:10px 0;padding:10px 5px;">**** Sign in to see details.</div>
<?php		
	}
	
	if (isset($user['login'])&&$user['login']!=''){
		$res=opcache_get_status();
		$preload=isset($res['preload_statistics'])?$res['preload_statistics']:null;
		if ($preload){
			$prefuncs=isset($preload['functions'])?$preload['functions']:array();
			
			if (count($prefuncs)>0){
		?>
		<div class="testrow">
			<div class="testitem">Preloaded Functions</div>
			<div class="testresult">
				<?php echo count($prefuncs);?> &nbsp; <a href=# onclick="showhide('prefuncs');return false;">view</a>
			</div>
			<div class="clear"></div>
		</div>
		<div id="prefuncs" style="display:none;line-height:1.5em;padding:10px;padding-left:20px;font-size:14px;">
			<?php foreach ($prefuncs as $prefunc) echo $prefunc.' &nbsp; ';?>
		</div>
		<?php	
		
			}
			
			$preclasses=isset($preload['classes'])?$preload['classes']:array();
			
			if (count($preclasses)>0){
		?>
		<div class="testrow">
			<div class="testitem">Preloaded Classes</div>
			<div class="testresult">
				<?php echo count($preclasses);?> &nbsp; <a href=# onclick="showhide('preclasses');return false;">view</a>
			</div>
			<div class="clear"></div>
		</div>
		<div id="preclasses" style="display:none;line-height:1.5em;padding:10px;padding-left:20px;font-size:14px;">
			<?php foreach ($preclasses as $preclass) echo $preclass.' &nbsp; ';?>
		</div>
		<?php	
		
			}			
		
		}//preload	
	}
	
	
	if (isset($dbconfigs)&&$user['login']){
	?>
	<div style="background:#444444;color:#ffffff;padding:5px 10px;margin-top:20px;">Cluster Information</div>
	<table style="width:100%;">
	<tr><td><b>Host</b></td><td><b>Master</b></td><td><b>I/O</b></td><td><b>SQL</b></td><td><b>Delay</b></td><td><b>Link Time</b></td></tr>
	<?php	
		foreach ($dbconfigs as $dbconfig){
			$sa=microtime(1);
			$xdb=sql_get_db($dbconfig['host'],$dbconfig['db'],$dbconfig['user'],$dbconfig['pass']);
			$query="show slave status";
			$rs=sql_query($query,$xdb);
			$sb=microtime(1);			
		
			$myrow=sql_fetch_assoc($rs);
			
			$slave=isset($myrow['Slave_IO_State']);
			if (!$slave){
	?>
	<tr><td><?php echo $dbconfig['host'];?></td><td>Not a Slave</td><td></td></tr>
	<?php
				continue;	
			}
			
			
			$err=$myrow['Last_SQL_Errno'];
			$delay=$myrow['Seconds_Behind_Master'];
			$master=$myrow['Master_Host'];
			$iostatus=$myrow['Slave_IO_Running'];
			$sqlstatus=$myrow['Slave_SQL_Running'];
			
	?>
	<tr><td><?php echo $dbconfig['host'];?></td><td><?php echo $master;?></td><td><?php echo $iostatus;?></td><td><?php echo $sqlstatus;?></td><td><?php echo $delay;?></td><td><?php echo round(($sb-$sa)*1000);?>ms</td></tr>
	<?php	
				
		}
	?>
	</table>
	<?php	
	}
	
	/*
	if ($user['login']&&($SQL_ENGINE=="MySQLi"||$SQL_ENGINE=="MySQL")){
		$query="SELECT TABLE_SCHEMA, TABLE_NAME, CREATE_OPTIONS FROM INFORMATION_SCHEMA.TABLES WHERE CREATE_OPTIONS LIKE '%ENCRYPTION=\"Y\"%';";
		$rs=sql_query($query,$db);
		if (sql_affected_rows($db,$rs)){
		
?>
	<div style="background:#444444;color:#ffffff;padding:5px 10px;margin-top:20px;">Encrypted Tables</div>
<?php	
			while ($myrow=sql_fetch_assoc($rs)){
	?>
	<div style="float:left;padding:5px;margin-right:10px;white-space:nowrap;border:solid 1px #dedede;margin-top:10px;">
		<div style="font-size:13px;color:#444444;"><?php echo $myrow['TABLE_SCHEMA'];?></div>
		<div><?php echo $myrow['TABLE_NAME'];?></div>
	</div>
	<?php
			}//while	
		}
	}
	*/
?>	
	<div class="clear"></div>
</div>
<script src="nano.js"></script>
<script>
	if (typeof(document.documentElement.style.backgroundSize)=='string'){
		gid('hdbg').innerHTML='<span class="res_yes">Yes</span>';	
		if (window.devicePixelRatio>1) 	ajxcss(self.bgupgrade,'sysinfo_hd.css?hb='+hb());
		else gid('hdbg').innerHTML='<span style="color:#ffab00;">Deactivated</span>';
	}
	if (window.WebSocket) gid('wss').innerHTML='<span class="res_yes">Yes</span>';
	
	function checkver(){
		xajx('https://www.antradar.com/gsver.php?jscallback=verchecked');	
	}
	
	function verchecked(v){
		gid('gsver').innerHTML='Latest: '+v;
	}
	
</script>
</body>
</html>
