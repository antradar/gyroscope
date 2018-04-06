<?php

function gstruncate($gsid){
	global $db;
	
	$query="delete templates.* from templatetypes,templates where templatetypes.templatetypeid=templates.templatetypeid and gsid=$gsid";
	sql_query($query,$db);
	
	$query="delete templatevars.* from templatetypes,templatevars where templatetypes.templatetypeid=templatevars.templatetypeid and gsid=$gsid";
	sql_query($query,$db);
	
	$query="delete from templatetypes where gsid=$gsid";
	sql_query($query,$db);
}

function gsclone($src,$dst){
	global $db;
	
	$templatetypes=getbasetemplates($src);
	//todo: copy reports
	
	foreach ($templatetypes as $templatetype){
		$templatetypename=$templatetype['name'];
		$templatetypekey=$templatetype['key'];
		$query="insert into templatetypes(gsid,templatetypename,templatetypekey) values ($dst,'$templatetypename','$templatetypekey')";
		$rs=sql_query($query,$db);
		$templatetypeid=sql_insert_id($db,$rs)+0;
		
		if (!$templatetypeid) continue;
		
		foreach ($templatetype['templates'] as $template){
			$templatename=$template['name'];
			$templatetext=$template['text'];
			
			$query="insert into templates(templatetypeid,templatename,templatetext) values ($templatetypeid,'$templatename','$templatetext')";
			$rs=sql_query($query,$db);
			$templateid=sql_insert_id($db,$rs)+0;						
		}//foreach template
		
		$query="update templatetypes set activetemplateid=$templateid where templatetypeid=$templatetypeid";
		sql_query($query,$db);

		foreach ($templatetype['vars'] as $var){
			$varname=$var['name'];
			$vardesc=$var['desc'];
			
			$query="insert into templatevars(templatetypeid,templatevarname,templatevardesc) values ($templatetypeid,'$varname','$vardesc')";
			sql_query($query,$db);	
		}
		
	}//foreach templatetype
	
	//todo: write reports
	
}

function getbasetemplates($gsid){
	global $db;
	
	$templatetypes=array();
	
	$query="select templatetypes.*,templatename,templatetext,templatevarname,templatevardesc,templateid,templatevarid,templatetypekey from templatetypes
	left join templates on templatetypes.templatetypeid=templates.templatetypeid
	left join templatevars on templatetypes.templatetypeid=templatevars.templatetypeid
	where gsid=$gsid
	";
	
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_assoc($rs)){
		$templatetypeid=$myrow['templatetypeid'];
		$templatetypename=noapos($myrow['templatetypename']);
		$templatetypekey=noapos($myrow['templatetypekey']);
		$templatename=noapos($myrow['templatename']);
		$templatetext=noapos($myrow['templatetext']);
		$templatevarname=noapos($myrow['templatevarname']);
		$templatevardesc=noapos($myrow['templatevardesc']);
		$activetemplateid=$myrow['activetemplateid'];
		if (!$activetemplateid) $activetemplateid='null';
		$templateid=$myrow['templateid']+0;
		$templatevarid=$myrow['templatevarid']+0;
		
		if (!is_array($templatetypes[$templatetypeid])) $templatetypes[$templatetypeid]=array('key'=>$templatetypekey,'activeid'=>$activetemplateid,'name'=>$templatetypename,'templates'=>array(),'vars'=>array());
		
		if ($templatevarid) $templatetypes[$templatetypeid]['vars'][$templatevarid]=array('name'=>$templatevarname,'desc'=>$templatevardesc);
		if ($templateid) $templatetypes[$templatetypeid]['templates'][$templateid]=array('name'=>$templatename,'text'=>$templatetext);
			
	}
	
	return $templatetypes;
	
}

