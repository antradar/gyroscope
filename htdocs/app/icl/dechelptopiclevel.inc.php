<?php

function dechelptopiclevel(){
	$helptopicid=SGET('helptopicid');
	global $db;
	
	$query="update ".TABLENAME_HELPTOPICS." set helptopiclevel=helptopiclevel-1 where helptopicid=?";
	sql_prep($query,$db,$helptopicid);
		
	echo "Help level indentation level decreased";
		
}