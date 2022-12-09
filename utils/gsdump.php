<?php

/*

A GS container specific data dump tool

*/

$fn='gsdump.config';

if (!file_exists($fn)){
	die("missing config file $fn\n");	
}

$params=array();

$gsid=null;

$append='';
$appendflags='--skip-add-drop-table --no-create-db --no-create-info --skip-triggers ';

foreach ($argv as $idx=>$arg){
	if ($idx==0) continue;
	if (preg_match('/gsid=(\d+)/i',$arg,$matches)){
		$gsid=$matches[1];
		continue;	
	}
	if (strtolower($arg)=='append'){
		$append=$appendflags;
		continue;
	}
	array_push($params,$arg);
}

if (!isset($gsid)) die("missing parameter gsid=###\n");

$strparams=implode(' ',$params);



$rules=array();

$vars=array();

$f=fopen($fn,'rt');
while (!feof($f)){
	$line=trim(fgets($f));
	if ($line=='') continue;
	if ($line[0]=='#') continue;
	if (preg_match('/^\s*(\w+?)\s*=\s*([\S\s]+)/',$line,$matches)){
		$vars[strtolower($matches[1])]=$matches[2];
		continue;
	}
	array_push($rules,$line);
}
fclose($f);

if (!isset($vars['gscol'])) die("gscol (default gsid) is not set in config file.\n");

if (count($rules)==0) die("no rules were found.\n");

echo "# GS_ID: $gsid\n";
echo "# gscol: ".$vars['gscol']."\n";

echo "\n";

$out='';
if (isset($vars['outfile'])&&$vars['outfile']!='') {
	$outfn=$vars['outfile'];
	
	$outfn=str_replace('%%gsid%%',$gsid,$outfn);
	foreach ($vars as $k=>$v){
		$outfn=str_replace('%%'.$k.'%%',$v,$outfn);	
	}
	echo '> '.$outfn."\n";
	$out=' >> '.$outfn;
}

if (isset($vars['defparams'])) $strparams=$vars['defparams'].' '.$strparams;


//print_r($rules);

$lock='--lock-all-tables';
if (isset($vars['nolock'])){
	if ($vars['nolock']==1||strtolower($var['nolock'])=='true'){
		$lock='--single-transaction';	
	}	
}



foreach ($rules as $rule){
	list($table,$where,$useappend)=parse_rule($rule,$vars['gscol'],$gsid);
	if (!isset($table)) continue;	
	echo "mysqldump $lock $useappend ".$strparams." $table $where $out\n\n";	
}//foreach rule


function parse_rule($rule,$gscol,$gsid){
	
	global $append;
	global $appendflags;
	
	$table='';
	$where='';
	
	$useappend=$append;
	
	$extrawhere='';
	
	$parts=explode(':',$rule);
	$rawtable=trim($parts[0]);
	$tableparts=explode('@',$rawtable);
	$table=$tableparts[0];
	$altgscol=$gscol;
	if (count($tableparts)>1) $altgscol=$tableparts[1];
	if (count($tableparts)>2){
		switch ($tableparts[2]){
			case '+': $useappend=$appendflags;	break;
		}
	}
	if (count($tableparts)>3){
		$extrawhere=$tableparts[3];
	}
	
	if (count($parts)==1) $subrule=''; else $subrule=trim($parts[1]);
	
	
	if ($useappend==''){
		if ($subrule=='*') return array($table,'',$useappend);
		if ($subrule=='-') return array($table,'-d',$useappend);
		if ($subrule=='?') return array($table,'--where="('.$altgscol.'='.$gsid.' or '.$altgscol.'=0 or '.$altgscol.' is null)"',$useappend);		
	} else {
		
		if ($subrule=='*') return array(null,null,null);
		if ($subrule=='-') return array(null,null,null);
				
		if ($subrule=='?') return array($table,'--where="'.$altgscol.'='.$gsid.'"',$useappend);
	}
	

		
	if ($subrule=='') return array($table,'--where="'.$altgscol.'='.$gsid.' '.$extrawhere.'"',$useappend);

		
	$subparts=explode('|',$subrule);
	$tables=explode(',',trim($subparts[0]));
	$keys=explode(',',trim($subparts[1]));
		
	$maintable=$tables[0];
	$mainkeys=explode('-',$keys[0]);
	$mainkey=$mainkeys[0];
	
	$tailtable=$tables[count($tables)-1];
	$tailkeyparts=explode('-',$keys[count($keys)-1]);
	$tailkey=trim($tailkeyparts[1]);
			
	$where='--where="'.$tailkey.' in (select '.$tailtable.'.'.$tailkey.' from '.implode(',',$tables)." where ${maintable}.${gscol}=${gsid} ";
	
	foreach ($keys as $idx=>$keypair) {
		$kparts=explode('-',$keypair);
		$keya=$kparts[0];
		$keyb=$kparts[1];
		$where.=' and '.$tables[$idx].'.'.$keya.'='.$tables[$idx+1].'.'.$keyb;
	}
	
	$where.=' '.$extrawhere.')';
	
	$where.='" ';
			
	
	return array($table,$where,$useappend);	
}


