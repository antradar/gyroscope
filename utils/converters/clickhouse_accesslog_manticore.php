<?php
/*
index accesslog_rt{
    type = rt
    
	rt_attr_uint = logdate
	rt_attr_string = ip
	rt_attr_string = method
	rt_attr_string = baseurl
	rt_attr_string = gsfunc
	rt_attr_uint = httpstatus
	rt_attr_uint = netsize
	rt_attr_uint = nettime
	rt_attr_uint = srvtime
	rt_attr_string = osname
	rt_attr_string = osver
	rt_attr_string = uaname
	rt_attr_string = uaver
	rt_attr_string = xcountry
	rt_attr_string = xprov
	rt_attr_string = city
	rt_attr_uint = gsid
	rt_attr_uint = userid
	rt_attr_string = logsitename

	path = /var/lib/manticore/data/accesslog_rt

}
*/

include '../../www_pub/app/connect.php'; //adjust this when applicable
include '../progressbar.php';

$query="select count(*) as c, min(logid) as min from accesslog";
$rs=vsql_prep($query,$vdb);
$myrow=vsql_fetch_assoc($rs);

$c=$myrow['c'];
$min=$myrow['min'];

echo "Migrating $c records\r\n";

$chunksize=10000;
$processed=0;
$lastid=$min-1;
if ($lastid<0) $lastid=0;

echo progressbar(0,$c); //initial view

while ($processed<$c){
	$a=$lastid;
	$b=$lastid+$chunksize;
	
	//echo "[$a,$b)\r\n";
	$query="select * from accesslog where logid>$a and logid<=$b";
	$rs=vsql_prep($query,$vdb);
	$n=vsql_affected_rows($vdb,$rs);
	
	if ($n==0){
		$lastid=$b;
		continue;
	}
	
	$subdelta=0;
	
	while ($myrow=vsql_fetch_assoc($rs)){
		$logid=$myrow['logid'];
		
		insert_record($myrow);
		
		$lastid=$alogid;
		$subdelta++; //for a more fine-grained progress bar
	}//myrow
	
	
	$processed+=$n;
	$dprocessed=$processed; if ($dprocessed>$c) $dprocessed=$c;
	echo progressbar($dprocessed,$c);
	

}

echo "\r\nDone.\r\n";


////////////////////////////

function insert_record($myrow){
	global $manticore;
	
	$logdate=intval($myrow['logdate']);
	$ip=addslashes($myrow['ip']);
	$method=addslashes($myrow['method']);

	$baseurl=addslashes($myrow['baseurl']);
	$gsfunc=addslashes($myrow['gsfunc']);
	$httpstatus=intval($myrow['httpstatus']);
	
	$netsize=intval($myrow['netsize']);
	$nettime=intval($myrow['nettime']);
	$srvtime=intval($myrow['srvtime']);
	
	$osname=addslashes($myrow['osname']);
	$osver=addslashes($myrow['osver']);
	$uaname=addslashes($myrow['uaname']);
	$uaver=addslashes($myrow['uaver']);
	$xcountry=addslashes($myrow['xcountry']);
	$xprov=addslashes($myrow['xprov']);
	$city=addslashes($myrow['city']);
	
	$gsid=intval($myrow['gsid']);
	$userid=intval($myrow['userid']);
	
	$logsitename=addslashes($myrow['logsitename']);
	
	$query="insert into accesslog_rt(
	logdate,ip,method,
	baseurl,gsfunc,httpstatus,
	netsize,nettime,srvtime,
	osname,osver, uaname,uaver,
	xcountry,xprov,city,
	gsid,userid,
	logsitename
	) values (
	$logdate, '$ip', '$method',
	'$baseurl','$gsfunc',$httpstatus,
	$netsize,$nettime,$srvtime,
	'$osname','$osver','$uaname','$uaver',
	'$xcountry','$xprov','$city',
	$gsid,$userid,
	'$logsitename'
	)";
	sql_query($query,$manticore);

	//sleep(1);
}