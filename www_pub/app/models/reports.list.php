<?php

function reports_list($ctx=null, $gsid,$key,$lang,$page,$syslevel,$soundex=0){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	
	if ($page<0) $page=0;
	
	$ver=cache_get_entity_ver('reports_list_'.$gsid);
	$ckey='reports_list_'.$ver.'_'.$gsid.'_'.strtolower($key).'_'.$lang.'_'.$page.'_'.$syslevel.'_'.$soundex;
	
	$res=cache_get($ckey);
	if (!$res){
		$params=array($gsid,$syslevel);
		$query="select * from ".TABLENAME_REPORTS." where (gsid=? or gsid=?) ";
		if (TABLENAME_GSS!='gss') $query="select * from ".TABLENAME_REPORTS." where (".COLNAME_GSID."=? or ".COLNAME_GSID."=?)";
		
		$sxsearch='';
		if ($soundex&&$key!='') $sxsearch=" or concat(soundex(reportname_$lang),'') like concat(soundex(?),'%') ";
		
		if ($key!='') {
			$query.=" and (lower(reportname_$lang) like lower(?) or lower(reportgroup_$lang) like lower(?) $sxsearch) ";
			array_push($params,"%$key%","%$key%");
			if ($sxsearch){
				array_push($params,$key);	
			}
		}
		$cquery="select count(*) as c from ($query) t";
		$rs=sql_prep($cquery,$db,$params);
		$myrow=sql_fetch_assoc($rs);
		
		$count=$myrow['c'];
		
		$perpage=20;
		$maxpage=ceil($count/$perpage)-1;
		if ($maxpage<0) $maxpage=0;
		if ($page>$maxpage) $page=$maxpage;
		$start=$perpage*$page;
	
		$query.=" order by reportgroup_$lang, reportname_$lang limit $start,$perpage";	
		$rs=sql_prep($query,$db,$params);
			
		$recs=array();
		while ($myrow=sql_fetch_assoc($rs)){
			array_push($recs,$myrow);
		}//while
		
		$res=array(
			'page'=>$page,
			'maxpage'=>$maxpage,
			'count'=>$count,
			'recs'=>$recs,
			'ver'=>$ver,
			'cached'=>0
		);
		
		cache_set($ckey,$res,3600);
	} else {
		$res['cached']=1;	
	}
	
	
	return $res;
	
			
}