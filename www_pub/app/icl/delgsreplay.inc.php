<?php

function delgsreplay(){
	global $db;
	$gsreplayid=GETVAL('gsreplayid');
	gsguard($gsreplayid,'gsreplays','gsreplayid');

	$query="delete gsreplays.*, gsreplayframes.* from gsreplays left join gsreplayframes on gsreplays.gsreplayid=gsreplayframes.gsreplayid where gsreplays.gsreplayid=?";
	sql_prep($query,$db,array($gsreplayid));
			
}