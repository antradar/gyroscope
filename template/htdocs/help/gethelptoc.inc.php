<?php

function gethelptoc(){
	global $db;

	$query="select helptopicid,helptopiclevel,helptopictitle from ".TABLENAME_HELPTOPICS." order by helptopicsort";
	$toc=array();
	$ts=array(); //title stack

	$lastlevel=null;

	$rs=sql_prep($query,$db);
	
	while ($myrow=sql_fetch_assoc($rs)){
		$topicid=$myrow['helptopicid'];
		$level=$myrow['helptopiclevel'];
		$title=$myrow['helptopictitle'];

		if (!isset($lastlevel)){
			array_push($ts,$title);
			$lastlevel=$level;
		}

		if ($level>$lastlevel){
			array_push($ts,$title);
			$lastlevel=$level;
		}
		
		while ($level<$lastlevel){
			array_pop($ts);
			$lastlevel--;
		}
		
		if ($level==$lastlevel){
			array_pop($ts);
			array_push($ts,$title);
		}
		
		if (!isset($toc[$topicid])) {
			$tts=$ts;
			array_pop($tts);
			$toc[$topicid]=implode(' / ',$tts);
		}
		
	}//while
	
	return $toc;
}
