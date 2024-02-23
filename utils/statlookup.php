<?php
include 'connect.php';

function statlookup(){
	global $vdb;
	global $db;

	$cmds=array();
	$slowuserids=array();

	$cutoff=time()-3600*24*14;

	$query="select userid,gsfunc,srvtime,logdate from accesslog where logdate>$cutoff and gsfunc like 'lookup%' order by logid limit 100000";
	$rs=vsql_prep($query,$vdb);
	while ($myrow=vsql_fetch_assoc($rs)){
		$userid=$myrow['userid'];
		$cmd=$myrow['gsfunc'];
		$srvtime=$myrow['srvtime'];
		$logdate=$myrow['logdate'];

		if (!isset($cmds[$cmd])) $cmds[$cmd]=array('total'=>0,'count'=>0,'users'=>array());
		$cmds[$cmd]['total']+=$srvtime;
		$cmds[$cmd]['count']++;

		if (!isset($cmds[$cmd]['users'][$userid])) {
			$cmds[$cmd]['users'][$userid]=array('name'=>'?','last'=>null,'diffsum'=>0,'diffcount'=>0);
			//$userids[$userid]=$userid;
		}

		$diff=0;
		$last=$cmds[$cmd]['users'][$userid]['last'];
		if (!isset($last)) $last=$logdate;
		if ($logdate-$last<4){
			if ($logdate>$last){
				$diff=$logdate-$last;
				$cmds[$cmd]['users'][$userid]['diffsum']+=$diff;
				$cmds[$cmd]['users'][$userid]['diffcount']+=1;
			}
			$last=$logdate;
		} else {
			$last=null;
		}

		$cmds[$cmd]['users'][$userid]['last']=$last;

	}//while

	foreach ($cmds as $idx=>$cmd){
		$cmds[$idx]['avg']=round($cmd['total']/$cmd['count'],2);
		foreach ($cmd['users'] as $uid=>$user){
			if ($user['diffcount']>0){
				$uavg=round($user['diffsum']/$user['diffcount'],3);
				$cmds[$idx]['users'][$uid]['diffavg']=$uavg;
				if ($uavg>2.1&&is_numeric($uid)) $slowuserids[$uid]=$uid;
			}
		}

	}//foreach cmd 2nd pass

	//print_r($cmds);

	$slowusers=array();

	if (count($slowuserids)>0){
		echo "\r\n[Slow Typers]\r\n================\r\n";
		$strslowuserids=implode(',',$slowuserids);
		$query="select users.gsid,gsname,userid,dispname from users,gss where users.gsid=gss.gsid and userid in ($strslowuserids) order by gsname,gsid,dispname,userid ";
		$rs=sql_prep($query,$db);
		while ($myrow=sql_fetch_assoc($rs)) {
			$slowusers[$myrow['userid']]=$myrow;
			echo $myrow['gsname'].':    '.$myrow['dispname'] .' ('.$myrow['userid'].")\r\n";
		}

		//print_r($slowusers);

	}


	$slowcmds=array();
	
	$cutoff2=time()-3600*24*60;
	

	$query="select gsfunc,avg(srvtime) as avg from accesslog where logdate>$cutoff2 and gsfunc like 'lookup%' and srvtime>200 and httpstatus='200' group by gsfunc order by avg";
	$rs=vsql_prep($query,$vdb);
	$count=vsql_affected_rows($vdb,$rs);
	if ($count>0) {
		echo "\r\n[Slow Functions]\r\n====================\r\n";
		while ($myrow=vsql_fetch_assoc($rs)){
			$cmd=$myrow['gsfunc'];
			$avg=$myrow['avg'];
			$rec=round(200+80*log($avg-200));
			$rec=ceil($rec/50)*50;
			echo $cmd.": avg ".$avg." recommended: $rec\r\n";
		}//while
		echo "\r\n";
	}


}

statlookup();
