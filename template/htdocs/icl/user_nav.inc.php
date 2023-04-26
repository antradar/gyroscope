<?php

function user_countfield($fieldname,$limit=null){
	global $db;
	
	$filters=user_makefilters();

	//if a 1-N sum is used, use *count(distinct users.userid)/count(users.userid)	
	$qfieldname=$fieldname;

	// if ($fieldname=='expression_field') $qfieldname=" some_expression as mapped_field ";
	
	$query="select count(*) as c, $qfieldname from ".TABLENAME_USERS;
			
		
	$query.=" where 1 ";
	


	$params=array();
	$sqlfilters=user_sqlfilters();
	$query.=$sqlfilters['clauses'];
	$params=array_merge($params,$sqlfilters['params']);
	
	$query.=" group by $fieldname ";
	
	if ($limit!=null) $query.=" order by c desc limit $limit ";
	else $query.=" order by $fieldname ";
				
	$rs=sql_prep($query,$db,$params);
	$counts=array();
	
	while ($myrow=sql_fetch_array($rs)){
		$c=intval($myrow['c']);
		$a=$c;//round(floatval($myrow['a'])); //if not applicable, then $a=$c;
		if ($c<=0) continue;
		$fnparts=explode('.',$fieldname);
		$fn=$fnparts[count($fnparts)-1];
		$key=$myrow[$fn];
		if (trim($key)=='') continue;
		
		if ($fieldname!='groupnames'){
			$counts[$key]=array('c'=>$c,'a'=>$a);
		} else {
			$keyparts=explode('|',$key);
			foreach ($keyparts as $key){
				if ($key=='users') continue;
				if (!isset($counts[$key])) $counts[$key]=array('c'=>0,'a'=>0);
				$counts[$key]['c']+=$c;
				$counts[$key]['a']+=$c;	
			}
		}
	}
	
	return $counts;	
	
}

function user_sqlfilters(){
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$filters=' and '.COLNAME_GSID.'=? ';
	$params=array($gsid);
	
	
	$key=GETSTR('key');
	if ($key!='') {
		$filters.=" and ( lower(login) like lower(?) or lower(dispname) like lower(?) ) ";
		array_push($params,"%$key%","%$key%");
	}
		
	if (isset($_GET['groupnames'])&&is_array($_GET['groupnames'])) {
		foreach ($_GET['groupnames'] as $multi_id=>$flag) {
			if (!isset($_GET['neg__groupnames'])||!in_array($multi_id,$_GET['neg__groupnames'])){
				$filters.=' and (groupnames like ? or groupnames like ? or groupnames like ? or groupnames like ?) ';
				array_push($params,"%|$multi_id|%","%|$multi_id","$multi_id|%",$multi_id);
			}
		}

	}	
	if (isset($_GET['neg__groupnames'])&&is_array($_GET['neg__groupnames'])) {
		foreach ($_GET['neg__groupnames'] as $neg) {
			$filters.=' and groupnames not like ?';
			array_push($params,"%|$neg|%");
		}
	}
	
	if (isset($_GET['multior_canchat'])&&$_GET['multior_canchat']!=''){
		$rawids=explode('||',$_GET['multior_canchat']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and canchat in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__canchat'])&&is_array($_GET['neg__canchat'])) {
		foreach ($_GET['neg__canchat'] as $neg) {
			$filters.=" and canchat!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['canchat'])&&$_GET['canchat']!=''&&(!isset($_GET['neg__canchat'])||!is_array($_GET['neg__canchat'])||!in_array($_GET['canchat'],$_GET['neg__canchat']))) {
		$filters.=" and canchat=? ";
		array_push($params,SGET('canchat'));
	}
	if (isset($_GET['multior_haspic'])&&$_GET['multior_haspic']!=''){
		$rawids=explode('||',$_GET['multior_haspic']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and haspic in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__haspic'])&&is_array($_GET['neg__haspic'])) {
		foreach ($_GET['neg__haspic'] as $neg) {
			$filters.=" and haspic!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['haspic'])&&$_GET['haspic']!=''&&(!isset($_GET['neg__haspic'])||!is_array($_GET['neg__haspic'])||!in_array($_GET['haspic'],$_GET['neg__haspic']))) {
		$filters.=" and haspic=? ";
		array_push($params,SGET('haspic'));
	}
	if (isset($_GET['multior_active'])&&$_GET['multior_active']!=''){
		$rawids=explode('||',$_GET['multior_active']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and active in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__active'])&&is_array($_GET['neg__active'])) {
		foreach ($_GET['neg__active'] as $neg) {
			$filters.=" and active!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['active'])&&$_GET['active']!=''&&(!isset($_GET['neg__active'])||!is_array($_GET['neg__active'])||!in_array($_GET['active'],$_GET['neg__active']))) {
		$filters.=" and active=? ";
		array_push($params,SGET('active'));
	}
	if (isset($_GET['multior_virtualuser'])&&$_GET['multior_virtualuser']!=''){
		$rawids=explode('||',$_GET['multior_virtualuser']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and virtualuser in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__virtualuser'])&&is_array($_GET['neg__virtualuser'])) {
		foreach ($_GET['neg__virtualuser'] as $neg) {
			$filters.=" and virtualuser!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['virtualuser'])&&$_GET['virtualuser']!=''&&(!isset($_GET['neg__virtualuser'])||!is_array($_GET['neg__virtualuser'])||!in_array($_GET['virtualuser'],$_GET['neg__virtualuser']))) {
		$filters.=" and virtualuser=? ";
		array_push($params,SGET('virtualuser'));
	}
	if (isset($_GET['multior_usega'])&&$_GET['multior_usega']!=''){
		$rawids=explode('||',$_GET['multior_usega']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and usega in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__usega'])&&is_array($_GET['neg__usega'])) {
		foreach ($_GET['neg__usega'] as $neg) {
			$filters.=" and usega!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['usega'])&&$_GET['usega']!=''&&(!isset($_GET['neg__usega'])||!is_array($_GET['neg__usega'])||!in_array($_GET['usega'],$_GET['neg__usega']))) {
		$filters.=" and usega=? ";
		array_push($params,SGET('usega'));
	}
	if (isset($_GET['multior_useyubi'])&&$_GET['multior_useyubi']!=''){
		$rawids=explode('||',$_GET['multior_useyubi']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and useyubi in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__useyubi'])&&is_array($_GET['neg__useyubi'])) {
		foreach ($_GET['neg__useyubi'] as $neg) {
			$filters.=" and useyubi!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['useyubi'])&&$_GET['useyubi']!=''&&(!isset($_GET['neg__useyubi'])||!is_array($_GET['neg__useyubi'])||!in_array($_GET['useyubi'],$_GET['neg__useyubi']))) {
		$filters.=" and useyubi=? ";
		array_push($params,SGET('useyubi'));
	}
	if (isset($_GET['multior_yubimode'])&&$_GET['multior_yubimode']!=''){
		$rawids=explode('||',$_GET['multior_yubimode']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and yubimode in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__yubimode'])&&is_array($_GET['neg__yubimode'])) {
		foreach ($_GET['neg__yubimode'] as $neg) {
			$filters.=" and yubimode!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['yubimode'])&&$_GET['yubimode']!=''&&(!isset($_GET['neg__yubimode'])||!is_array($_GET['neg__yubimode'])||!in_array($_GET['yubimode'],$_GET['neg__yubimode']))) {
		$filters.=" and yubimode=? ";
		array_push($params,SGET('yubimode'));
	}
	if (isset($_GET['multior_usesms'])&&$_GET['multior_usesms']!=''){
		$rawids=explode('||',$_GET['multior_usesms']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and usesms in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__usesms'])&&is_array($_GET['neg__usesms'])) {
		foreach ($_GET['neg__usesms'] as $neg) {
			$filters.=" and usesms!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['usesms'])&&$_GET['usesms']!=''&&(!isset($_GET['neg__usesms'])||!is_array($_GET['neg__usesms'])||!in_array($_GET['usesms'],$_GET['neg__usesms']))) {
		$filters.=" and usesms=? ";
		array_push($params,SGET('usesms'));
	}
	if (isset($_GET['multior_needcert'])&&$_GET['multior_needcert']!=''){
		$rawids=explode('||',$_GET['multior_needcert']);
		$ids=array();
		foreach ($rawids as $id) array_push($ids,"'".noapos($id)."'");
		$strids=implode(',',$ids);
		if (count($ids)>0){
			$filters.=" and needcert in ($strids) ";	
		}		
	}	
	if (isset($_GET['neg__needcert'])&&is_array($_GET['neg__needcert'])) {
		foreach ($_GET['neg__needcert'] as $neg) {
			$filters.=" and needcert!=? ";
			array_push($params,$neg);
		}
	}	
	if (isset($_GET['needcert'])&&$_GET['needcert']!=''&&(!isset($_GET['neg__needcert'])||!is_array($_GET['neg__needcert'])||!in_array($_GET['needcert'],$_GET['neg__needcert']))) {
		$filters.=" and needcert=? ";
		array_push($params,SGET('needcert'));
	}

				
							
							
	
	return array('clauses'=>$filters,'params'=>$params);
}

function user_makefilters(){
	$filters=array();
	$nfilters=array();
	
	$key=GETSTR('key');
	if ($key!='') {
		$filters['searchterm']=$key;
	}
	if (isset($_GET['visible'])) $filters['visible']=$_GET['visible'];

		

	if (isset($_GET['groupnames'])&&is_array($_GET['groupnames'])){
		$filters['groupnames']=array();
		foreach ($_GET['groupnames'] as $multi_id=>$flag) {
			if (!isset($_GET['neg__groupnames'])||!in_array($multi_id,$_GET['neg__groupnames'])) $filters['groupnames'][$multi_id]=1;
		}
	}
	if (isset($_GET['neg__groupnames'])&&$_GET['neg__groupnames']!='') $nfilters['groupnames']=$_GET['neg__groupnames'];
	
	if (isset($_GET['multior_canchat'])&&$_GET['multior_canchat']!='') $filters['multior_canchat']=$_GET['multior_canchat'];
	if (isset($_GET['neg__canchat'])&&is_array($_GET['neg__canchat'])) $nfilters['canchat']=$_GET['neg__canchat'];
	if (isset($_GET['canchat'])&&$_GET['canchat']!=''&&(!isset($_GET['neg__canchat'])||!is_array($_GET['neg__canchat'])||!in_array($_GET['canchat'],$_GET['neg__canchat']))) $filters['canchat']=$_GET['canchat'];

	if (isset($_GET['multior_haspic'])&&$_GET['multior_haspic']!='') $filters['multior_haspic']=$_GET['multior_haspic'];
	if (isset($_GET['neg__haspic'])&&is_array($_GET['neg__haspic'])) $nfilters['haspic']=$_GET['neg__haspic'];
	if (isset($_GET['haspic'])&&$_GET['haspic']!=''&&(!isset($_GET['neg__haspic'])||!is_array($_GET['neg__haspic'])||!in_array($_GET['haspic'],$_GET['neg__haspic']))) $filters['haspic']=$_GET['haspic'];

	if (isset($_GET['multior_active'])&&$_GET['multior_active']!='') $filters['multior_active']=$_GET['multior_active'];
	if (isset($_GET['neg__active'])&&is_array($_GET['neg__active'])) $nfilters['active']=$_GET['neg__active'];
	if (isset($_GET['active'])&&$_GET['active']!=''&&(!isset($_GET['neg__active'])||!is_array($_GET['neg__active'])||!in_array($_GET['active'],$_GET['neg__active']))) $filters['active']=$_GET['active'];

	if (isset($_GET['multior_virtualuser'])&&$_GET['multior_virtualuser']!='') $filters['multior_virtualuser']=$_GET['multior_virtualuser'];
	if (isset($_GET['neg__virtualuser'])&&is_array($_GET['neg__virtualuser'])) $nfilters['virtualuser']=$_GET['neg__virtualuser'];
	if (isset($_GET['virtualuser'])&&$_GET['virtualuser']!=''&&(!isset($_GET['neg__virtualuser'])||!is_array($_GET['neg__virtualuser'])||!in_array($_GET['virtualuser'],$_GET['neg__virtualuser']))) $filters['virtualuser']=$_GET['virtualuser'];

	if (isset($_GET['multior_usega'])&&$_GET['multior_usega']!='') $filters['multior_usega']=$_GET['multior_usega'];
	if (isset($_GET['neg__usega'])&&is_array($_GET['neg__usega'])) $nfilters['usega']=$_GET['neg__usega'];
	if (isset($_GET['usega'])&&$_GET['usega']!=''&&(!isset($_GET['neg__usega'])||!is_array($_GET['neg__usega'])||!in_array($_GET['usega'],$_GET['neg__usega']))) $filters['usega']=$_GET['usega'];

	if (isset($_GET['multior_useyubi'])&&$_GET['multior_useyubi']!='') $filters['multior_useyubi']=$_GET['multior_useyubi'];
	if (isset($_GET['neg__useyubi'])&&is_array($_GET['neg__useyubi'])) $nfilters['useyubi']=$_GET['neg__useyubi'];
	if (isset($_GET['useyubi'])&&$_GET['useyubi']!=''&&(!isset($_GET['neg__useyubi'])||!is_array($_GET['neg__useyubi'])||!in_array($_GET['useyubi'],$_GET['neg__useyubi']))) $filters['useyubi']=$_GET['useyubi'];

	if (isset($_GET['multior_yubimode'])&&$_GET['multior_yubimode']!='') $filters['multior_yubimode']=$_GET['multior_yubimode'];
	if (isset($_GET['neg__yubimode'])&&is_array($_GET['neg__yubimode'])) $nfilters['yubimode']=$_GET['neg__yubimode'];
	if (isset($_GET['yubimode'])&&$_GET['yubimode']!=''&&(!isset($_GET['neg__yubimode'])||!is_array($_GET['neg__yubimode'])||!in_array($_GET['yubimode'],$_GET['neg__yubimode']))) $filters['yubimode']=$_GET['yubimode'];

	if (isset($_GET['multior_usesms'])&&$_GET['multior_usesms']!='') $filters['multior_usesms']=$_GET['multior_usesms'];
	if (isset($_GET['neg__usesms'])&&is_array($_GET['neg__usesms'])) $nfilters['usesms']=$_GET['neg__usesms'];
	if (isset($_GET['usesms'])&&$_GET['usesms']!=''&&(!isset($_GET['neg__usesms'])||!is_array($_GET['neg__usesms'])||!in_array($_GET['usesms'],$_GET['neg__usesms']))) $filters['usesms']=$_GET['usesms'];

	if (isset($_GET['multior_needcert'])&&$_GET['multior_needcert']!='') $filters['multior_needcert']=$_GET['multior_needcert'];
	if (isset($_GET['neg__needcert'])&&is_array($_GET['neg__needcert'])) $nfilters['needcert']=$_GET['neg__needcert'];
	if (isset($_GET['needcert'])&&$_GET['needcert']!=''&&(!isset($_GET['neg__needcert'])||!is_array($_GET['neg__needcert'])||!in_array($_GET['needcert'],$_GET['neg__needcert']))) $filters['needcert']=$_GET['needcert'];
	
	
	
	
			
		
	return array('filters'=>$filters,'nfilters'=>$nfilters);
}

function user_strfilters($filters,$nfilters=null){
	
	$filter='';
	foreach ($filters as $key=>$val){
		if (is_array($val)) foreach ($val as $k=>$v) $filter.='&'.$key.'['.urlencode($k).']=1';
		else $filter.='&'.urlencode($key).'='.urlencode($val);

	}
	
	if (is_array($nfilters)){
		foreach ($nfilters as $key=>$vals) {
			foreach ($vals as $val) $filter.="&neg__${key}[]=$val";	
		}
	}
		
	return $filter;
}

function user_shownav($container, $cmd, $title,$fieldname,$multi=null,$multior=false){
	
	global $pcharts;

	$bfilters=user_makefilters();
	$basefilters=$bfilters['filters'];
	$nbasefilters=$bfilters['nfilters'];

	
	if (isset($basefilters[$fieldname])&&!$multi){
		$myfilters=$basefilters;
		unset($myfilters[$fieldname]);
		$filter=user_strfilters($myfilters,$nbasefilters);
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
<?php
	user_shownegfilters($fieldname,$container,$cmd);
?>
	<div class="navfilter">
		<a href=# onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>');return false;">[x]</a> <?php echo user_dispname($fieldname,$basefilters[$fieldname]);?>
	</div>
</div><!-- navgroupx -->
<?php		
		return;
	}

// end inline breadcrumb

	$counts=user_countfield($fieldname);
	$dcounts=$counts;

	if (count($counts)<=0) {
		if (isset($nbasefilters[$fieldname])&&count($nbasefilters[$fieldname])>0){
		?>
		<div class="navgroupx ng_<?php echo $fieldname;?>">
		<div class="navtitle"><?php echo $title;?></div>
		<?php	
			user_shownegfilters($fieldname,$container,$cmd);
		?>
		</div><!-- navgroupx -->
		<?php
		}
		return;
	}

	//add any parents of a nested dimension in the exemption list below:
	if ((!isset($multior)||!$multior)&&!$multi&&count($counts)<2) return; //comment out to show singular filters
	
	$bfilters=user_makefilters();
	$basefilters=$bfilters['filters'];
	$nbasefilters=$bfilters['nfilters'];
		
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
<div class="navtitle"><?php echo $title;?>
	<?php if ($multior){
		$mybasefilters=$basefilters;
		unset($mybasefilters[$fieldname]);
		$strbasefilter=user_strfilters($mybasefilters,$nbasefilters);		
		
	?>
	<span style="margin-left:20px;visibility:hidden;"><button id="multior_<?php echo $fieldname;?>" onclick="nav_applymultior('<?php echo $container;?>','<?php echo $fieldname;?>','userkey','<?php echo $cmd;?>','<?php echo $strbasefilter;?>');">apply filters</button></span>
	<?php }?>
</div>
<div class="navfilters" id="navfilters_<?php echo $fieldname;?>">
<?php	

	
	user_shownegfilters($fieldname,$container,$cmd);
	
	if ($multior){
		$selids=array();
		if (isset($_GET['multior_'.$fieldname])&&$_GET['multior_'.$fieldname]!=''){	
			$selids=explode('||',$_GET['multior_'.$fieldname]);
		}
	}
	
	$mymultiorfilters=$basefilters;
	if (isset($mymultiorfilters["multior_".$fieldname])) unset($mymultiorfilters["multior_".$fieldname]);
	$multiorfilters=user_strfilters($mymultiorfilters,$nbasefilters);
			
	foreach ($counts as $key=>$count){
		$dispname=user_dispname($fieldname,$key,array_keys($counts));
		$myfilters=$basefilters;
		if (isset($multi)&&$multi!=''){
			if (!isset($myfilters[$multi])) $myfilters[$multi]=array();
			$myfilters[$multi][$key]=1;
			if (isset($basefilters[$multi][$key])) unset($myfilters[$multi][$key]);
		} else $myfilters[$fieldname]=$key;
		$filters=user_strfilters($myfilters,$nbasefilters);

		$dcounts[$key]['n']=$dispname.'';
		$dcounts[$key]['f']=$filters.'';

		//echo '<pre>';print_r($filters);echo '</pre>';
		/*
		todo: use your container ids for navigation, breadcrumb and record list
		*/
		
		?>
<div class="navfilter">
		<?php
		if ($multi){
	?>		
		<input class="multiand" <?php if (isset($basefilters[$multi][$key])) echo 'checked';?> type="checkbox" href=# onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filters;?>');return false;"> 
	<?php		
		}
		
		if ($multior){
		?>
		<input <?php if (in_array($key,$selids)) echo 'checked';?> value="<?php echo $key;?>"  type="checkbox" onclick="nav_selectfilter(this,'<?php echo $container;?>','<?php echo $fieldname;?>','userkey','<?php echo $cmd;?>','<?php echo $multiorfilters;?>');"> 
		<?php		
		}		

?>
	<a href=# onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filters;?>');return false;"><?php echo htmlspecialchars($dispname);?></a> 
	<?php
	if (true||!isset($multi)||!$multi||!$basefilters[$multi][$key]){ //remove true to hide refinement count for selected multi fields
	?>
	<em>(<?php echo $count['c'];?>)</em>
	<?php }?>
	
	<?php 
	/*
	if (!isset($basefilters[$multi][$key])||!$basefilters[$multi][$key]){?>
	<a class="filterneg" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filters;?>&neg__<?php echo $fieldname;?>[]=<?php echo htmlspecialchars($key);?>');return false;">
	<acronym title="exclude from results">[-]</acronym>
	</a>
	<?php }
	*/
	?>
		
</div>
<?php
	}
	
	if (!$multi||true){ //pie chart for multi-select fields skews the data but can nevertheless be useful
		if (!isset($pcharts)) $pcharts=array();
		if (!isset($pcharts[$fieldname])) $pcharts[$fieldname]=array(
			'title'=>$title,
			'type'=>'pie',
			'fieldname'=>$fieldname,
			'counts'=>array_values($dcounts)
		);
	}		
?>
</div><!-- navfilters -->
</div><!-- navgroupx -->
<?php		
}


function user_dispname($fieldname,$key,$ids=null){
	global $db;
	global $userroles;

	//global $dimnames;
	
	if (!is_array($ids)) $ids=array($key);
	$strids=implode(',',$ids); //for creating name dictionaries

	
	switch ($fieldname){
		case 'canchat': case 'haspic': case 'active': case 'virtualuser': case 'usega': case 'useyubi': case 'usesms': case 'needcert':
			if ($key==1) return 'Yes'; else return 'No';
		break;
		case 'groupnames': return $userroles[$key]; break;
		/*
		todo: add field value to field name mapping
		*/

		/*
		case 'dim':
			if (!isset($dimnames)){
				$query="select * from dims where dimid in ($strids)";
				$rs=sql_query($query,$db);
				while ($myrow=sql_fetch_assoc($rs)) $dimnames[$myrow['dimid']]=$myrow['dimname'];
			}
			$res=$dimnames[$key];
			if (count($ids)==1) $dimnames=null;
			return $res;
		break;
		*/

		default: return $key;
	}	
}

function user_shownegfilters($fieldname,$container,$cmd){
	$bfilters=user_makefilters();
	$filters=$bfilters['filters'];
	$nfilters=$bfilters['nfilters'];
			
	if (!isset($nfilters)||!isset($nfilters[$fieldname])||count($nfilters[$fieldname])==0) return;
?>
<div class="neglabel">Excluding:</div>
<div class="neggroup">
		<?php foreach ($nfilters[$fieldname] as $item){
			$mynfilters=$nfilters;
			$key=array_search($item,$mynfilters[$fieldname]);
			if ($key!==false){
				unset($mynfilters[$fieldname][$key]);	
			}
			$strfilters=user_strfilters($filters,$mynfilters);
		?>
		<div class="negfilter"><a class="filterclear" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $strfilters;?>');return false;">[x]</a> <?php echo user_dispname($fieldname,$item);?></div>
		<?php }?>
</div>
<?php	
}

function user_showrange($container,$cmd, $title,$fieldname){
	global $db;
	global $pcharts;
		
	$bfilters=user_makefilters();
	$basefilters=$bfilters['filters'];
	$nbasefilters=$bfilters['nfilters'];
	$filters=$basefilters;
		
	$dimmode=$filters[$fieldname.'__dimmode'];
	if ($dimmode=='') $dimmode='c';
	
// start inline breadcrumb		
	if (is_numeric($filters[$fieldname.'_a'])||is_numeric($filters[$fieldname.'_b'])){
		$myfilters=$filters;
		unset($myfilters[$fieldname.'_a']);
		unset($myfilters[$fieldname.'_b']);
		$filter=user_strfilters($myfilters,$nbasefilters);		
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
	<div class="navfilter">
<a class="filterclear" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>');return false;">[x]</a>
<?php echo $filters[$fieldname.'_a'].' - '.$filters[$fieldname.'_b'];?>
	</div>
</div> 
<?php	
		$filtered=1;
	}
// end inline breadcrumb

	$sqlfilters=user_sqlfilters();	

	$params=array();
	
	$query="select min($fieldname) as a, max($fieldname) as b from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$myrow=sql_fetch_array($rs);
	
	$min=$myrow['a'];
	$max=$myrow['b'];

	if ($min==$max) return;
	
	$filter=user_strfilters($filters,$nbasefilters);
	
	$bucketsize=($max-$min)/10;

	switch ($fieldname){
	
	default:
	/*
		$ranges=array( //comment out this array to always use dynamic ranges
			array('min'=>null,'max'=>1000,'label'=>'<1k'),
			array('min'=>1000,'max'=>10000,'label'=>'1-10k'),
			array('min'=>10000,'max'=>null,'label'=>'>10k')
		);
	*/
	}
				
	$params=array();
	
	$query="select count(*) as c,min($bucketsize*floor($fieldname/$bucketsize)) as cmin, max($bucketsize*floor($fieldname/$bucketsize+1)) as cmax 
		from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
	$query.=" group by floor($fieldname/$bucketsize) ";
	
	if (!$filtered&&isset($ranges)){//no range filter applied, use custom bucket ranges if defined
		
		$query="select count(*) as c,
		case
		";
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then $va ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then $va ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then $va ";
			}
		}
		
		$query.=" end as cmin, case ";
		
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then $vb ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then $vb  ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then $vb  ";
			}
		}
		
		$query.=" end as cmax, case ";
		
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then '$label' ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then '$label' ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then '$label' ";
			}
		}
		
		$query.=" end as xlabel ";

		$query.=" from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
		$query.=" group by cmin ";
		
	}
	
	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$cs=array();
	
	while ($myrow=sql_fetch_array($rs)){
		if (!isset($myrow['cmin'])) continue;
		array_push($cs,array(
			'min'=>floatval($myrow['cmin']),
			'max'=>floatval($myrow['cmax']),
			'xlabel'=>$myrow['xlabel'],
			'count'=>intval($myrow['c']),
			'f'=>"$filter&${fieldname}_a=".$myrow['cmin'].'&'.$fieldname.'_b='.$myrow['cmax']
		));	
	}
	
	if (!isset($pcharts)) $pcharts=array();
	if (!isset($pcharts[$fieldname])) $pcharts[$fieldname]=array(
		'dimmode'=>$dimmode,
		'title'=>$title,
		'type'=>'column',
		'fieldname'=>$fieldname,
		'counts'=>array($cs)
	);	
				
	if ($filtered) return;			
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
		<input class="inpshort num" id="<?php echo $fieldname?>_a" name="<?php echo $fieldname;?>_a" value="<?php echo $min;?>" style="width:60px;"> - 
		<input class="inpshort num" id="<?php echo $fieldname?>_b" name="<?php echo $fieldname;?>_b" value="<?php echo $max;?>" style="width:60px;">
		<button onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>&<?php echo $fieldname;?>_a='+gid('<?php echo $fieldname;?>_a').value+'&<?php echo $fieldname;?>_b='+gid('<?php echo $fieldname;?>_b').value);return false;">Set</button>
</div>
<?php	
		
}

function user_showdaterange($container,$cmd, $title,$fieldname,$subdims=null){ //subdims is a key-val associative array
	global $db;
	global $pcharts;
	
	$bfilters=user_makefilters();
	$basefilters=$bfilters['filters'];
	$nbasefilters=$bfilters['nfilters'];
	$filters=$basefilters;
	$filtered=0;
	
	$dimkey=$filters[$fieldname.'__dimkey'];
	if (!in_array($dimkey,array_keys($subdims))) $dimkey='';
		
	$dimmode=$filters[$fieldname.'__dimmode'];
	if ($dimmode=='') $dimmode='c';
		
	switch ($dimmode){
		//case 'a': $tally='avg(...)'; break;
		//case 's': $tally='sum(...)'; break;
		default: $tally='count(*)';	
	}		

// start inline breadcrumb		
	if (isset($filters[$fieldname.'_a'])||isset($filters[$fieldname.'_b'])){
		$myfilters=$filters;
		unset($myfilters[$fieldname.'_a']);
		unset($myfilters[$fieldname.'_b']);
		$filter=user_strfilters($myfilters,$nbasefilters);		
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
	<div class="navfilter">
<a class="filterclear" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>');return false;">[x]</a>
<?php echo $filters[$fieldname.'_a'].' - '.$filters[$fieldname.'_b'];?>

	<?php if ($filters[$fieldname.'_a']==$filters[$fieldname.'_b']){
		$nextfilters=$myfilters;
		$nextfilters[$fieldname.'_a']=date('Y-n-j',date2stamp($filters[$fieldname.'_a'])+3600*(24+2)); //26 hours to counter daylight saving
		$nextfilters[$fieldname.'_b']=$nextfilters[$fieldname.'_a'];
		$nextfilter=user_strfilters($nextfilters,$nbasefilters);
	
		$prevfilters=$myfilters;
		$prevfilters[$fieldname.'_a']=date('Y-n-j',date2stamp($filters[$fieldname.'_a'])-3600*24);
		$prevfilters[$fieldname.'_b']=$prevfilters[$fieldname.'_a'];
		$prevfilter=user_strfilters($prevfilters,$nbasefilters);
			
		?>
			<div style="padding-top:5px;font-size:11px;text-align:center;">
				<a class="hovlink" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $prevfilter;?>');return false;">&laquo; Prev Day</a>
				&nbsp; | &nbsp;
				<a class="hovlink" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $nextfilter;?>');return false;">Next Day &raquo;</a>
			</div>
	<?php
	}
	$stampa=date2stamp($filters[$fieldname.'_a']);
	$stampb=date2stamp($filters[$fieldname.'_b']);
	$ndays=round(($stampb-$stampa)/3600/24);
	if ($ndays>=4&&$ndays<=8){
		$nextfilters=$myfilters;
		$nextfilters[$fieldname.'_a']=date('Y-n-j',$stampa+3600*24*7+3600*2);
		$nextfilters[$fieldname.'_b']=date('Y-n-j',$stampb+3600*24*7+3600*2);
		$nextfilter=user_strfilters($nextfilters,$nbasefilters);
	
		$prevfilters=$myfilters;
		$prevfilters[$fieldname.'_a']=date('Y-n-j',$stampa-3600*24*7);
		$prevfilters[$fieldname.'_b']=date('Y-n-j',$stampb-3600*24*7);
		$prevfilter=user_strfilters($prevfilters,$nbasefilters);
			
		?>
			<div style="padding-top:5px;font-size:11px;text-align:center;">
				<a class="hovlink" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $prevfilter;?>');return false;">&laquo; Prev Week</a>
				&nbsp; | &nbsp;
				<a class="hovlink" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $nextfilter;?>');return false;">Next Week &raquo;</a>
			</div>
		<?php
		
	}
	?>

	</div>
</div> 
<?php	
		$filtered=1;	
	}
// end inline breadcrumb

	$sqlfilters=user_sqlfilters();	

	$params=array();
	
	$query="select min($fieldname) as a, max($fieldname) as b from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$myrow=sql_fetch_array($rs);
	
	$min=date('Y-n-j',$myrow['a']);
	$max=date('Y-n-j',$myrow['b']);

	if ($min==$max) $filtered=1;
	
	$params=array();
	
	$minyear=date('Y',$myrow['a']); $maxyear=date('Y',$myrow['b']);
	$minmon=date('n',$myrow['a']); $maxmon=date('n',$myrow['b']);
	$minday=date('j',$myrow['a']); $maxday=date('j',$myrow['b']);
	$minhour=date('H',$myrow['a']); $maxhour=date('H',$myrow['b']);
	
	$timebucket=" year(from_unixtime($fieldname)) ";
	
	if ($minyear==$maxyear){
		$timebucket=" concat(year(from_unixtime($fieldname)),'-',month(from_unixtime($fieldname))) ";
	}
	if ($minyear==$maxyear&&$minmon==$maxmon){
		$timebucket=" date(from_unixtime($fieldname)) ";
	}
	if ($minyear==$maxyear&&$minmon==$maxmon&&$minday==$maxday){
		$timebucket=" concat(date(from_unixtime(floor($fieldname/3600)*3600)),'-',hour(from_unixtime(floor($fieldname/3600)*3600))) ";
	}
	
	$query="select $tally as c,min($fieldname) as cmin,max($fieldname) as cmax ";
	if ($dimkey!='') $query.=", $dimkey ";	
	$query.=" from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
	$query.=" group by $timebucket ";
	if ($dimkey!='') $query.=", $dimkey ";	
	$query.=" order by $fieldname ";

	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$cs=array();
	
	$filter=user_strfilters($filters,$nbasefilters);
	$dtitle=$title;

	$xkeys=array();
	$dimkeys=array();
	
	while ($myrow=sql_fetch_array($rs)){
		if (!isset($myrow['cmin'])) continue;
		
		$xlabel=date('Y',$myrow['cmin']);
		if ($minyear==$maxyear) {$xlabel=date('M',$myrow['cmin']);$dtitle=$title.' - '.date('Y',$myrow['cmin']);}
		if ($minyear==$maxyear&&$minmon==$maxmon) {$xlabel=date('j',$myrow['cmin']);$dtitle=$title.' - '.date('M Y',$myrow['cmin']);}
		if ($minyear==$maxyear&&$minmon==$maxmon&&$minday==$maxday) {$xlabel=date('ga',$myrow['cmin']);$dtitle=$title.' - '.date('M j, Y',$myrow['cmin']);}
		
		$ckey=$xlabel.'@'.$myrow[$dimkey];
		$xkeys[$xlabel]=$xlabel;
		$dimkeys[$myrow[$dimkey]]=$myrow[$dimkey];
		if (!isset($cs[$ckey])) 		
		$cs[$ckey]=array(
			'min'=>$myrow['cmin'],
			'max'=>$myrow['cmax'],
			'xlabel'=>$xlabel,
			'count'=>0,
			'f'=>"$filter&${fieldname}_a=".date('Y-n-j',$myrow['cmin']).'&'.$fieldname.'_b='.date('Y-n-j',$myrow['cmax']),
			'k'=>$dimkey!=''?$myrow[$dimkey]:'',
			'kn'=>$dimkey!=''?user_dispname($dimkey,$myrow[$dimkey]):''
		);
		
		$cs[$ckey]['count']+=round(floatval($myrow['c']),2);
	}
	
	if ($dimkey!=''){
		$ocs=$cs;
		$cs=array();
				
		foreach ($dimkeys as $dk){
			$series=array();
			foreach ($xkeys as $xkey){
				if (isset($ocs[$xkey.'@'.$dk])) array_push($series,$ocs[$xkey.'@'.$dk]);
				else array_push($series,array(
					'xlabel'=>$xkey,
					'count'=>0,
					'f'=>'',
					'k'=>'',
					'kn'=>user_dispname($dimkey,$dk)
				));
			}
			
			array_push($cs,$series);
			
		}//foreach dimkey series
				
	} else $cs=array(array_values($cs)); //a series of one element
		
	
	if (!isset($pcharts)) $pcharts=array();
	if (!isset($pcharts[$fieldname])) $pcharts[$fieldname]=array(
		'dimmode'=>$dimmode,
		'title'=>$dtitle,
		'type'=>'column',
		'fieldname'=>$fieldname,
		'counts'=>$cs
	);		
	
	
	$dimfilters=$filters;unset($dimfilters['dimkey']); $dimkeyfilter=user_strfilters($dimfilters,$nbasefilters);
	$dimfilters=$filters;unset($dimfilters['dimmode']); $dimmodefilter=user_strfilters($dimfilters,$nbasefilters);
	
	$pcharts[$fieldname]['dimkeybase']=$dimkeyfilter; //base filters with dim key removed
	$pcharts[$fieldname]['dimmodebase']=$dimmodefilter; //base filters with dim mode removed
	$pcharts[$fieldname]['dimkey']=$dimkey;
	
	if (is_array($subdims)){
		$pcharts[$fieldname]['subdims']=$subdims;	
	}		
	
	
	if ($filtered) return;	
			
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
		<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" class="inp" id="<?php echo $fieldname?>_a" name="<?php echo $fieldname;?>_a" value="<?php echo $min;?>" style="width:80px;"> - 
		<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" class="inp" id="<?php echo $fieldname?>_b" name="<?php echo $fieldname;?>_b" value="<?php echo $max;?>" style="width:80px;">
		<button onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>&<?php echo $fieldname;?>_a='+gid('<?php echo $fieldname;?>_a').value+'&<?php echo $fieldname;?>_b='+gid('<?php echo $fieldname;?>_b').value);return false;">Set</button>
</div>
<?php	
		
}

function user_showbubble($container,$cmd, $title,$fieldname,$title2,$fieldname2,$sizekey){
	global $db;
	global $pcharts;
	
	//pass in $sizekey as either avg(fieldname) or sum(fieldname)

	$bfilters=user_makefilters();
	$basefilters=$bfilters['filters'];
	$nbasefilters=$bfilters['nfilters'];
	$filters=$basefilters;
		

// start inline breadcrumb		
	if (is_numeric($filters[$fieldname.'_a'])||is_numeric($filters[$fieldname.'_b'])){
		$myfilters=$filters;
		unset($myfilters[$fieldname.'_a']);
		unset($myfilters[$fieldname.'_b']);
		unset($myfilters[$fieldname2.'_a']);
		unset($myfilters[$fieldname2.'_b']);
		$filter=user_strfilters($myfilters,$nbasefilters);		
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
	<div class="navfilter">
<a class="filterclear" onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>');return false;">[x]</a>
<?php echo $filters[$fieldname.'_a'].' - '.$filters[$fieldname.'_b'].', '.$filters[$fieldname2.'_a'].' - '.$filters[$fieldname2.'_b'];?>
	</div>
</div> 
<?php	
		$filtered=1;
	}
// end inline breadcrumb

	$sqlfilters=user_sqlfilters();	

	$params=array();
	
	$query="select min($fieldname) as a, max($fieldname) as b, min($fieldname2) as ya, max($fieldname2) as yb from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$myrow=sql_fetch_array($rs);
	
	$min=$myrow['a'];
	$max=$myrow['b'];
	
	$ymin=$myrow['ya'];
	$ymax=$myrow['yb'];

	if ($min==$max&&$ymin==$ymax) return;
	
	$filter=user_strfilters($filters,$nbasefilters);
	
	$bucketsize=($max-$min)/10;
	$bucketsize2=($ymax-$ymin)/5;

	switch ($fieldname){

	case 'demo':
		$ranges=array(
			array('min'=>null,'max'=>100000,'label'=>'A'),
			array('min'=>100000,'max'=>2000000,'label'=>'B'),
			array('min'=>2000000,'max'=>10000000,'label'=>'C'),
			array('min'=>10000000,'max'=>50000000,'label'=>'D'),
			array('min'=>50000000,'max'=>100000000,'label'=>'E'),
			array('min'=>100000000,'max'=>null,'label'=>'F')
		);	
	break;
	
	default:
	}
	
	switch ($fieldname2){

	case 'demo2':
		$yranges=array(
			array('min'=>null,'max'=>10,'label'=>'A'),
			array('min'=>10,'max'=>20,'label'=>'B'),
			array('min'=>20,'max'=>null,'label'=>'C')
		);	
	break;
	
	default:
	}	
				
	$params=array();
	
	$query="select $sizekey as c,$bucketsize*floor($fieldname/$bucketsize) as cmin, $bucketsize*floor($fieldname/$bucketsize+1) as cmax,
	
		$bucketsize2*floor($fieldname2/$bucketsize2) as ymin, $bucketsize2*floor($fieldname2/$bucketsize2+1) as ymax
	 
		from ".TABLENAME_USERS."
		 where 1 ".$sqlfilters['clauses'];
	$query.=" group by floor($fieldname/$bucketsize),floor($fieldname2/$bucketsize2) ";
	
	if (!$filtered&&(isset($ranges)||isset($yranges))){//no range filter applied, use custom bucket ranges if defined
	
		$query="select $sizekey as c,";
		if (isset($ranges)){	
		
		$query.="
		case
		";
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then $va ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then $va ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then $va ";
			}
		}
		
		$query.=" end as cmin, case ";
		
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then $vb ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then $vb  ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then $vb  ";
			}
		}
		
		$query.=" end as cmax, case ";
		
		foreach ($ranges as $range){
			$va=$range['min']; if (!is_numeric($va)) $va=$min;
			$vb=$range['max']; if (!is_numeric($vb)) $vb=$max;
			$label=$range['label'];
			if (is_numeric($range['min'])&&is_numeric($range['max'])){
				$query.=" when $fieldname>=$va and $fieldname<=$vb then '$label' ";
			} else {
				if (is_numeric($range['max'])) $query.=" when $fieldname<=$vb then '$label' ";
				if (is_numeric($range['min'])) $query.=" when $fieldname>=$va then '$label' ";
			}
		}
		
		$query.=" end as xlabel ";
		
		} else {//ranges
		$query.="$bucketsize*floor($fieldname/$bucketsize) as cmin, $bucketsize*floor($fieldname/$bucketsize+1) as cmax ";
			
		}//ranges
		
		if (!isset($yranges)){
			$query.=", $bucketsize2*floor($fieldname2/$bucketsize2) as ymin, $bucketsize2*floor($fieldname2/$bucketsize2+1) as ymax";
		} else {

			$query.="
			,case
			";
			foreach ($yranges as $range){
				$va=$range['min']; if (!is_numeric($va)) $va=$ymin;
				$vb=$range['max']; if (!is_numeric($vb)) $vb=$ymax;
				$label=$range['label'];
				if (is_numeric($range['min'])&&is_numeric($range['max'])){
					$query.=" when $fieldname2>=$va and $fieldname2<=$vb then $va ";
				} else {
					if (is_numeric($range['max'])) $query.=" when $fieldname2<=$vb then $va ";
					if (is_numeric($range['min'])) $query.=" when $fieldname2>=$va then $va ";
				}
			}
			
			$query.=" end as ymin, case ";
			
			foreach ($yranges as $range){
				$va=$range['min']; if (!is_numeric($va)) $va=$ymin;
				$vb=$range['max']; if (!is_numeric($vb)) $vb=$ymax;
				$label=$range['label'];
				if (is_numeric($range['min'])&&is_numeric($range['max'])){
					$query.=" when $fieldname2>=$va and $fieldname2<=$vb then $vb ";
				} else {
					if (is_numeric($range['max'])) $query.=" when $fieldname2<=$vb then $vb  ";
					if (is_numeric($range['min'])) $query.=" when $fieldname2>=$va then $vb  ";
				}
			}
			
			$query.=" end as ymax";			
						
		}
		

		$query.=" from ".TABLENAME_USERS." where 1 ".$sqlfilters['clauses'];
		
		if (isset($ranges)) $query.=" group by cmin ";
		else $query.=" group by floor($fieldname/$bucketsize) ";
		
		if (!isset($yranges)){
			$query.=",floor($fieldname2/$bucketsize2) ";
		} else {
			$query.=", ymin ";	
		}
		
	}
	
	$params=array_merge($params,$sqlfilters['params']);
	
	$rs=sql_prep($query,$db,$params);
	$cs=array();
	
	while ($myrow=sql_fetch_array($rs)){
		if (!isset($myrow['cmin'])) continue;
		array_push($cs,array(
			'x'=>round(floatval($myrow['cmin']),2),
			'y'=>round(floatval($myrow['ymin']),2),
			'z'=>round(floatval($myrow['c']),2),
			'xlabel'=>$myrow['xlabel'],
			'f'=>"$filter&${fieldname}_a=".$myrow['cmin'].'&'.$fieldname.'_b='.$myrow['cmax']."&${fieldname2}_a=".$myrow['ymin'].'&'.$fieldname2.'_b='.$myrow['ymax']
		));	
	}
	
	//echo '<pre>'; print_r($cs); echo '</pre>';
	
	if (!isset($pcharts)) $pcharts=array();
	if (!isset($pcharts[$fieldname])) $pcharts[$fieldname]=array(
		'title'=>$title,
		'title2'=>$title2,
		'type'=>'bubble',
		'fieldname'=>$fieldname,
		'counts'=>$cs
	);	
				
	if ($filtered) return;			
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
	<input class="inpshort num" id="<?php echo $fieldname?>_a" name="<?php echo $fieldname;?>_a" value="<?php echo $min;?>" style="width:60px;"> - 
	<input class="inpshort num" id="<?php echo $fieldname?>_b" name="<?php echo $fieldname;?>_b" value="<?php echo $max;?>" style="width:60px;">
	
	<div class="navtitle"><?php echo $title2;?></div>
	<input class="inpshort num" id="<?php echo $fieldname2?>_a" name="<?php echo $fieldname2;?>_a" value="<?php echo $ymin;?>" style="width:60px;"> - 
	<input class="inpshort num" id="<?php echo $fieldname2?>_b" name="<?php echo $fieldname2;?>_b" value="<?php echo $ymax;?>" style="width:60px;">

		
	<button onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filter;?>&<?php echo $fieldname;?>_a='+gid('<?php echo $fieldname;?>_a').value+'&<?php echo $fieldname;?>_b='+gid('<?php echo $fieldname;?>_b').value+'&<?php echo $fieldname2;?>_a='+gid('<?php echo $fieldname2;?>_a').value+'&<?php echo $fieldname2;?>_b='+gid('<?php echo $fieldname2;?>_b').value);return false;">Set</button>
</div>
<?php	
		
}//bubble



function user_showsearch($container,$cmd,$title,$fieldname){
	$bfilters=user_makefilters();
	$basefilter=$bfilters['filters'];
	
	/*
	
	modify user_shownavs to enable cascading search when the record set is trimmed "small enough"
	
	if (!isset($basefilter['this_dim'])&&(
		isset($basefilter['parent_dim_1'])
		||
		isset($basefilter['parent_dim_2'])
	)){
		user_shownav($container, $cmd, 'This Dim Title','this_dim');	
	}
	
	
	*/
	
	if ($basefilter[$fieldname]!=''){
		user_shownav($container,$cmd,$title,$fieldname);
		return;	
	}

	unset($basefilter[$fieldname]);
	
	$filters=user_strfilters($basefilter,$nbasefilter);
	
?>
<div class="navgroupx ng_<?php echo $fieldname;?>">
	<div class="navtitle"><?php echo $title;?></div>
	<input class="inp" id="user_<?php echo $fieldname?>" value="" style="width:120px;"> 
	<button onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $filters;?>&<?php echo $fieldname;?>='+encodeHTML(gid('user_<?php echo $fieldname?>').value));return false;">Search</button>
</div>
<?php
}
	
function user_shownavs($container, $cmd){
	global $pcharts;

	$bfilters=user_makefilters();
	$basefilter=$bfilters['filters'];
	$nbasefilter=$bfilters['nfilters'];
	$filters=user_strfilters($basefilter,$nbasefilter);

	$vfilters=$basefilter;
	unset($vfilters['visible']);
	
	$vfilter=user_strfilters($vfilters,$nbasefilter);

	if (count($vfilters)>0||count($nbasefilter)>0){
		$strfilters=user_strfilters($basefilter,$nbasefilter);
	?>
		<button onclick="gid('userkey').value='';nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','');">Clear Filters</button>
	<?php		
	}	
?>
	<div class="clear"></div>
	<div style="padding:10px;display:none;">
		<input id="searchfilter_user" type="hidden" value="<?php echo $filters;?>" style="border:dashed 1px #dedede;width:100%;">
	</div>
<?php	

	if (!isset($basefilter['visible'])||!$basefilter['visible']){
		
?>	
	<div class="navopen"><a onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $vfilter;?>&visible=1');">show filters</a></div>
<?php 			
	} else {
?>
	<div class="navclose"><a onclick="nav_setfilter('<?php echo $container;?>','userkey','<?php echo $cmd;?>','<?php echo $vfilter;?>&visible=0');">hide filters</a></div>	
<?php			




?>
<div class="clear" style="margin-bottom:20px;"></div>
<?php
		
	user_shownav($container, $cmd, 'Roles','groupnames','groupnames');
	user_shownav($container, $cmd, 'Chat Ready','canchat');
	user_shownav($container, $cmd, 'Has Profile','haspic');
	user_shownav($container, $cmd, 'Active','active');
	user_shownav($container, $cmd, 'Virtual','virtualuser');
	user_shownav($container, $cmd, 'Authenticator','usega');
	user_shownav($container, $cmd, 'YubiKey Auth','useyubi');
	//user_shownav($container, $cmd, 'YubiKey Mode','yubimode');
	user_shownav($container, $cmd, 'SMS Auth.','usesms');
	user_shownav($container, $cmd, 'Smart Card Auth.','needcert');
	



	}
	
		
?>	
	<div class="clear"></div>
<?php	
	
}



