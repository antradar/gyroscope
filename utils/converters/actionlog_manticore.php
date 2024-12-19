<?php

include '../../www_pub/app/connect.php'; //adjust this when applicable
include '../progressbar.php';

$query="select count(*) as c, min(alogid) as min from actionlog";
$rs=sql_prep($query,$db);
$myrow=sql_fetch_assoc($rs);

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
	$query="select * from actionlog where alogid>$a and alogid<=$b";
	$rs=sql_prep($query,$db);
	$n=sql_affected_rows($db,$rs);
	
	if ($n==0){
		$lastid=$b;
		continue;
	}
	
	$subdelta=0;
	
	while ($myrow=sql_fetch_assoc($rs)){
		$alogid=$myrow['alogid'];
		
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
	
	$alogid=$myrow['alogid'];
	$gsid=$myrow['gsid'];
	$userid=$myrow['userid'];
	$logdate=$myrow['logdate'];
	$rectype=addslashes($myrow['rectype']);
	$recid=intval($myrow['recid']);
	$bulldozed=intval($myrow['bulldozed']??0);
	$rawobj=addslashes($myrow['rawobj']);
	
	$logmessage=addslashes($myrow['logmessage']);
	$logname=addslashes($myrow['logname']);
	
	$query="insert into actionlog_rt(alogid,gsid,userid,logdate,rectype,recid,bulldozed,logname,logmessage,rawobj) values (
	$alogid,$gsid,$userid,$logdate,'$rectype',$recid,$bulldozed,'$logname','$logmessage','$rawobj')";
	sql_query($query,$manticore);

	//sleep(1);
}