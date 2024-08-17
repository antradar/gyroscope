<?php

function gstruncate($gsid){
	global $db;

	$gsid=intval($gsid);
	
	$query="delete templates.* from templatetypes,templates where templatetypes.templatetypeid=templates.templatetypeid and gsid=?";
	sql_prep($query,$db,$gsid);
	
	$query="delete templatevars.* from templatetypes,templatevars where templatetypes.templatetypeid=templatevars.templatetypeid and gsid=?";
	sql_prep($query,$db,$gsid);
	
	$query="delete from templatetypes where gsid=?";
	sql_prep($query,$db,$gsid);

	$query="delete from reports where gsid=?";
	sql_prep($query,$db,$gsid);

	$query="delete from actionlog where gsid=?";
	sql_prep($query,$db,$gsid);

	$query="delete userhelpspots.* from users,userhelpspots where users.userid=userhelpspots.userid and gsid=?";
	sql_prep($query,$db,$gsid);

	$query="delete from users where gsid=?";
	sql_prep($query,$db,$gsid);

	$query="delete from gss where gsid=?";
	sql_prep($query,$db,$gsid); //comment out to keep the gs container

}

function gsclone($src,$dst){
	global $db;

	//copy reports
	$query="select reportid from reports where gsid=?";
	$rs=sql_prep($query,$db,$src);
	while ($myrow=sql_fetch_assoc($rs)){
		$reportid=$myrow['reportid'];
		$newid=sql_copy_from_query("select * from reports where reportid=$reportid",$db,array('reportid','gsid','gyrosys'),'reports');
		$query="update reports set gsid=? where reportid=?";
		sql_prep($query,$db,array($dst,$newid));
	}
		
	$templatetypes=getbasetemplates($src);
	
	foreach ($templatetypes as $templatetype){
		$templatetypename=$templatetype['name'];
		$templatetypekey=$templatetype['key'];
		$query="insert into templatetypes(gsid,templatetypename,templatetypekey) values (?,?,?)";
		$rs=sql_prep($query,$db,array($dst,$templatetypename,$templatetypekey));
		$templatetypeid=sql_insert_id($db,$rs);
		
		if (!$templatetypeid) continue;
		
		foreach ($templatetype['templates'] as $template){
			$templatename=$template['name'];
			$templatetext=$template['text'];
			
			$query="insert into templates(templatetypeid,templatename,templatetext) values (?,?,?)";
			$rs=sql_prep($query,$db,array($templatetypeid,$templatename,$templatetext));
			$templateid=sql_insert_id($db,$rs);						
		}//foreach template
		
		$query="update templatetypes set activetemplateid=? where templatetypeid=?";
		sql_prep($query,$db,array($templateid,$templatetypeid));

		foreach ($templatetype['vars'] as $var){
			$varname=$var['name'];
			$vardesc=$var['desc'];
			
			$query="insert into templatevars(templatetypeid,templatevarname,templatevardesc) values (?,?,?)";
			sql_prep($query,$db,array($templatetypeid,$varname,$vardesc));	
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
	where gsid=?
	";
	
	$rs=sql_prep($query,$db,$gsid);
	while ($myrow=sql_fetch_assoc($rs)){
		$templatetypeid=$myrow['templatetypeid'];
		$templatetypename=$myrow['templatetypename'];
		$templatetypekey=$myrow['templatetypekey'];
		$templatename=$myrow['templatename'];
		$templatetext=$myrow['templatetext'];
		$templatevarname=$myrow['templatevarname'];
		$templatevardesc=$myrow['templatevardesc'];
		$activetemplateid=$myrow['activetemplateid'];
		if (!$activetemplateid) $activetemplateid='null';
		$templateid=$myrow['templateid'];
		$templatevarid=$myrow['templatevarid'];
		
		if (!is_array($templatetypes[$templatetypeid])) $templatetypes[$templatetypeid]=array('key'=>$templatetypekey,'activeid'=>$activetemplateid,'name'=>$templatetypename,'templates'=>array(),'vars'=>array());
		
		if ($templatevarid) $templatetypes[$templatetypeid]['vars'][$templatevarid]=array('name'=>$templatevarname,'desc'=>$templatevardesc);
		if ($templateid) $templatetypes[$templatetypeid]['templates'][$templateid]=array('name'=>$templatename,'text'=>$templatetext);
			
	}
	
	return $templatetypes;
	
}

// clone level-1 chain
function depclone($masterkey,$clonefrom,$cloneto,$subtable,$subkey){
	global $db;
	$query="select * from $subtable where $masterkey=$clonefrom";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_assoc($rs)){
		$fromid=$myrow[$subkey];
		$toid=sql_copy_from_query("select * from $subtable where $subkey=$fromid",$db,array($subkey,$masterkey),$subtable);
		$query="update $subtable set $masterkey=$cloneto where $subkey=$toid";
		sql_query($query,$db);
	}
}
