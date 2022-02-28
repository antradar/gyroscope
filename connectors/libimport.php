<?php
include 'sql.php.mysql';

$db=sql_get_db('localhost','leadshark','root','mnstudio');


function listtables(){
	global $db;
	
	$query="show tables";
	$rs=sql_query($query,$db);
	$tables=array();
	while ($myrow=sql_fetch_assoc($rs)){
		foreach ($myrow as $key=>$val) array_push($tables,$val);	
	}
	
	return $tables;
		
}

function listfields($table){
	global $db;
	
	$query="describe $table";
	$rs=sql_query($query,$db);
	
	$fields=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		
		array_push($fields,$myrow);
			
	}
	
	return $fields;
}

function mongoimporttable($mdb,$table,$pri){
	global $db;
	
	$query="select * from $table";
	$rs=sql_query($query,$db);
	
	
	while ($myrow=sql_fetch_assoc($rs)){
		$obj=$mdb->$table->findOne(array($pri=>$myrow[$pri]));
		if(!isset($obj)) $mdb->$table->save($myrow);
	}
	
}

function mongoimport($dbname){
	global $db;
	$connection=new Mongo();
	$mdb=$connection->selectDB($dbname);

	$tables=listtables();
	
	foreach ($tables as $table){
		$query="describe $table";
		$rs=sql_query($query,$db);
		while ($myrow=sql_fetch_assoc($rs)){
			foreach ($myrow as $key=>$val){
				if (strtolower($val)=='pri') {
					$pri=$myrow['Field'];
					echo "[$table->$pri]\n";	
					mongoimporttable($mdb,$table,$pri);
					break;
				}	
			}	
		}
	}
}

//mongoimport('webmonocle');

$connection=new Mongo();
$mdb=$connection->selectDB('leadshark');
mongoimporttable($mdb,'clients','clientid');
