<?php

function inchelptopiclevel(){
	$helptopicid=SGET('helptopicid');
	global $db;
	
	$query="update ".TABLENAME_HELPTOPICS." set helptopiclevel=helptopiclevel+1 where helptopicid=?";
	sql_prep($query,$db,$helptopicid);

	$query="update ".TABLENAME_HELPTOPICS." set helptopiclevel=4 where helptopiclevel>4 and helptopicid=?";
	sql_prep($query,$db,$helptopicid);
		
	echo "Help level indentation level increased";
		
}