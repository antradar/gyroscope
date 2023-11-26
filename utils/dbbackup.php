<?php


$dbname='gyrostart';
$dbuser='root';
$dbpass='mnstudio';

include '../template/htdocs/sql.php';
$db=sql_get_db('127.0.0.1',$dbname,$dbuser,$dbpass);

$timezone='America/Toronto';
$datadir='/var/lib/mysql/';
$backupdir='/root/backups/';
$regfn='tableshots';

$skiptables=array('backups','backuptables','backuptapes','accesslogseq');
$tapetables=array(
	'actionlog'=>array('pkey'=>'alogid','cond'=>''),
	//'chats'=>array('pkey'=>'chatid','cond'=>'chatstatus=2'), //experimental, do not use
);


date_default_timezone_set($timezone);

$idx=0;
$preview=0;

$mode='backup';
$cur=null;

while ($idx<$argc){
	$arg=$argv[$idx];
	switch ($arg){
		case '--help':
			showbanner();
			showhelp();	
		break;
		case '--restore':
		
			showbanner();
			
			$nidx=$idx+1;

			$mode='restore';
						
			if (!isset($argv[$nidx])) {
				echo "Missing restore date. See usage below: \r\n";
				showhelp();
			}
			
			$rawdate=$argv[$nidx];
			if (!preg_match('/(\d\d\d\d)-(\d\d)-(\d\d)-(\d\d)/',$rawdate,$matches)){
				echo "Invalid restore date. See usage below: \r\n";
				showhelp();	
			}
			
			$year=$matches[1];
			$mon=$matches[2];
			$day=$matches[3];
			$hour=$matches[4];
			
			$cur=mktime($hour,0,0,$mon,$day,$year);
			
		break;
		case '--preview':
			$preview=1;
		break;
		default:
		
		
			
	}//switch
	$idx++;
}//while


if (!isset($cur)) $cur=time();

if ($mode=='restore'){
	echo "Creating restore instructions to go back to ".date('M j, Y H:i:s',$cur)."\r\n\r\n";
}//restore banner


/// get table registry

$lasttables=array();

/*
if (file_exists($backupdir.$regfn)){
	$lasttables=json_decode(file_get_contents($backupdir.$regfn),1);
}
*/

$query="select t.*,backupdate from (select tablekey,max(backupid) as backupid from backuptables where tablemodified<? group by tablekey) t, backups where t.backupid=backups.backupid";
$rs=sql_prep($query,$db, $cur);
while ($myrow=sql_fetch_assoc($rs)){
	$lasttables[$myrow['tablekey']]=array('backupid'=>$myrow['backupid'],'mtime'=>$myrow['backupdate']);
}//while

//print_r($lasttables); die();

/*
//mock last tables
$lasttables=array(
	'gss'=>mktime(10,0,0,8,5,2023),
	'users'=>mktime(10,0,0,8,5,2023),
	'yubikeys'=>mktime(10,0,0,11,20,2023),
);
*/

/// get tables from db

$query="show tables";
$exts=array('ibd','frm');

$rs=sql_prep($query,$db);
$tables=array();
while ($myrow=sql_fetch_array($rs)){
	$tablename=$myrow[0];
	if (isset($tapetables[$tablename])) continue;
	if (in_array($tablename,$skiptables)) continue;

	$mtime=null;
	$tstem=rtrim($datadir,'/').'/'.$dbname.'/'.$tablename;
	foreach ($exts as $ext){
		$tfn=$tstem.'.'.$ext;
		if (file_exists($tfn)){
			$mftime=filemtime($tfn);
			if (!isset($mtime)||$mtime<$mftime) $mtime=$mftime;	
		}
	}//foreach ext

	$lastseen=null;	

	if (isset($lasttables[$tablename])) $lastseen=$lasttables[$tablename]['mtime'];
		
	$tables[$tablename]=array('modified'=>$mtime,'lastseen'=>$lastseen);
	
}//while


//print_r($missingfiles);

//print_r($remtables);

$res=planbackup($tables,$cur);
//print_r($lasttables); die();
//print_r($res); die();

$basefn=$res['dmaxtime'];

if (count($res['remtables'])>0){
	if ($mode=='restore'){
		echo "The following tables are not in any backups:\r\n";
		foreach ($res['remtables'] as $tkey=>$_){
			if (isset($_['lastseen']))
				echo "  \033[33m$tkey\033[0m (".date('M j, Y \a\t H:i:s',$_['lastseen']).")\r\n";  
			else 
				echo "  \033[31m$tkey\033[0m\r\n";
		}//foreach	
	}
	
	if ($mode=='backup'){
		echo "\r\nThe following \033[33mmissing\033[0m tables will be fully backed up:\r\n";
		foreach ($res['remtables'] as $tkey=>$_){
			echo "  \033[33m$tkey \033[0m\r\n";
		}//foreach	
			
	}	
}

if (count($res['missingfiles'])>0){
	echo "\r\nMissing backup files:\r\n";
	foreach ($res['missingfiles'] as $fnkey=>$ts){
		echo "  \033[31m$fnkey\033[0m ".implode(', ',$ts)."\r\n";	
	}//foreach
}


//print_r($backups);

if ($mode=='backup'){
	
	$backupid=null;

	if (count($res['fulltables'])>0){
		$lasttables=array();
		foreach ($tables as $tkey=>$table){
			if (!isset($lasttables[$tkey])) $lasttables[$tkey]['mtime']=$res['maxtime'];	
		}
		
		foreach ($res['deltatables'] as $tkey) $lasttables[$tkey]['mtime']=$cur;
		
		//print_r($lasttables); die();
		
		$query="insert into backups (backupdate) values (?)";
		$rs=sql_prep($query,$db,$cur);
		$backupid=sql_insert_id($db,$rs);
		
		foreach ($res['fulltables'] as $tkey){
			$mtime=$cur;
			if (isset($lasttables[$tkey])) $mtime=$lasttables[$tkey]['mtime'];
			$query="insert into backuptables(backupid,tablekey,tablemodified) values (?,?,?)";
			$rs=sql_prep($query,$db,array($backupid,$tkey,$mtime));	
		}

		
		$cmd="mysqldump -u$dbuser -p$dbpass $dbname ".implode(' ',$res['fulltables'])." > ".$backupdir.'backup_'.$backupid.'.sql';
		//echo $cmd."\r\n";	
		passthru($cmd);
		
		
		//file_put_contents($backupdir.$regfn,json_encode($lasttables));
		
	}//full tables
	
	if (count($res['missingtapes'])>0){
		if (!isset($backupid)){
			$query="insert into backups (backupdate) values (?)";
			$rs=sql_prep($query,$db,$cur);
			$backupid=sql_insert_id($db,$rs);	
		}//get backup id
		
		foreach ($res['missingtapes'] as $tape){
			$tapefn=$tape['table'].'_'.$tape['min'].'_'.$tape['max'];
			$pkey=$tapetables[$tape['table']]['pkey'];
			echo "$pkey: ".$tape['min']." to ".$tape['max']."\r\n";
			$cmd="mysqldump -u$dbuser -p$dbpass $dbname ".$tape['table']." --where=\"($pkey >= ".$tape['min']." and $pkey <=".$tape['max'].")\" > ".$backupdir.$tapefn.'.tape.sql';
			//echo $cmd."\r\n";
			passthru($cmd);
			
			$query="insert into backuptapes(backupid,tapekey,tapedate,tapemin,tapemax) values (?,?,?,?,?)";
			sql_prep($query,$db,array($backupid,$tape['table'],$cur,$tape['min'],$tape['max']));			
			
		}
	
	}//missing tapes
	
}//backup

if ($mode=='restore'){
	print_r($res['backups']);	
}



echo "\r\n\r\n";

////////////////////////

function planbackup($tables,$cur){
	global $db;
	global $lasttables;
	global $tapetables;
	global $backupdir;
	

	$remtables=$tables;
	
	$backups=array();
	
	$fixtables=array();
	$deltatables=array();
	
	$missingfiles=array();
			
	foreach ($remtables as $tkey=>$table){
		$lastseen=$table['lastseen'];
		$mtime=$table['modified'];
		if (isset($mtime)&&isset($lastseen)&&$mtime>$lastseen){
			array_push($deltatables,$tkey);	
		}
		if (isset($lastseen)&&$lastseen<=$cur){
			$fnkey=date('Y-m-d-H',$lastseen);
			
			if (isset($lasttables[$tkey])) $fnkey='backup_'.$lasttables[$tkey]['backupid'];
			
			//echo "\033[34m$fnkey\033[0m\r\n";
			unset($remtables[$tkey]);
					
			$fn=$backupdir.$fnkey.'.sql';
			if (!file_exists($fn)){
				if (!isset($missingfiles[$fnkey])) $missingfiles[$fnkey]=array();
				array_push($missingfiles[$fnkey],$tkey);
				array_push($fixtables,$tkey);
			}
			
			if (!isset($backups[$fnkey])) $backups[$fnkey]=array();
			array_push($backups[$fnkey],$tkey);
			
		}
		
		
	}
	
	$fulltables=array();
	foreach ($remtables as $tkey=>$_) if (!in_array($tkey,$fulltables)) array_push($fulltables,$tkey);
	foreach ($fixtables as $tkey) if (!in_array($tkey,$fulltables)) array_push($fulltables,$tkey);
	foreach ($deltatables as $tkey) if (!in_array($tkey,$fulltables)) array_push($fulltables,$tkey);
	
	$maxtime=null;
	
	foreach ($fulltables as $tkey){
		if (!isset($tables[$tkey]['modified'])) continue;
		if (!isset($maxtime)||$maxtime<$tables[$tkey]['modified']) $maxtime=$tables[$tkey]['modified'];
	}
	
	if (!isset($maxtime)) $maxtime=$cur;

	$taperanges=array();
	
	foreach ($tapetables as $tkey=>$tt){
		$pkey=$tt['pkey'];
		$cond=$tt['cond'];
		$query="select max($pkey) as maxid from $tkey";
		$rs=sql_prep($query,$db);
		$myrow=sql_fetch_assoc($rs);
		$maxid=$myrow['maxid'];
		$tapecursor=$maxid;
		
		if ($cond!='') {
			$query="select min($pkey) as tapecursor from $tkey where !($cond)";
			$rs=sql_prep($query,$db);
			$myrow=sql_fetch_assoc($rs);
			if (isset($myrow['tapecursor'])) $tapecursor=$myrow['tapecursor'];
		}
		
		$otapecursor=$tapecursor;
		
		$query="select max(backupid) as lastbackupid from backups";
		$rs=sql_prep($query,$db);
		$myrow=sql_fetch_assoc($rs);
		$lastbackupid=$myrow['lastbackupid'];
		$minmin=null;
		if (isset($lastbackupid)){
			$query="select min(tapemax) as minmax,min(tapemin) as minmin from backuptapes where tapekey=? and backupid=?";
			$rs=sql_prep($query,$db,array($tkey,$lastbackupid));
			$myrow=sql_fetch_assoc($rs);
			if (isset($myrow['minmax'])) $tapecursor=$myrow['minmax'];
			$minmin=$myrow['minmin'];
			
		}
				
		if ($tapecursor!=$maxid){
			$forced=0;
			if ($cond!='') $forced=1;
			array_push($taperanges,array('table'=>$tkey,'min'=>1,'max'=>$tapecursor,'cursor'=>$tapecursor));
			array_push($taperanges,array('table'=>$tkey,'min'=>$tapecursor,'max'=>$maxid,'cursor'=>$tapecursor,'forced'=>$forced));
		} else {
			if (isset($minmin)){
				
				$forced=0;
				if ($cond!=''&&$otapecursor!=$maxid) $forced=1;
				//echo "$minmin $maxid $tapecursor $otapecursor\r\n"; die();			
				array_push($taperanges,array('table'=>$tkey,'min'=>1,'max'=>$minmin,'cursor'=>$minmin));
				array_push($taperanges,array('table'=>$tkey,'min'=>$minmin,'max'=>$maxid,'cursor'=>$tapecursor,'forced'=>$forced));
				
				/*
				if (!$forced){
					array_push($taperanges,array('table'=>$tkey,'min'=>$minmin,'max'=>$minmin,'cursor'=>$minmin,'forced'=>1));
				}
				*/
				
			} else {
				
				array_push($taperanges,array('table'=>$tkey,'min'=>1,'max'=>$maxid,'cursor'=>$tapecursor));
			}				
		}
		

						
	}//foreach tapetable
	
	//print_r($taperanges); die();
	
	$rawtaperanges=$taperanges;
	$taperanges=array();
	foreach ($rawtaperanges as $taperange){
		if ($taperange['min']==1){
			$tmin=1;
			$tmax=1;
			$query="select * from backuptapes where tapekey=? and tapemax<=? order by tapemin ";
			$rs=sql_prep($query,$db,array($taperange['table'],$taperange['cursor']));
			
			while ($myrow=sql_fetch_assoc($rs)){
				if ($tmin==1||$myrow['tapemax']){
					array_push($taperanges,array(
						'table'=>$taperange['table'],
						'min'=>$myrow['tapemin'],
						'max'=>$myrow['tapemax'],
						'split'=>1
					));
					$tmin=$myrow['tapemin'];
					$tmax=$myrow['tapemax'];
				}
				
			}//while
			
			if ($tmax<$taperange['max']){
				array_push($taperanges,array(
					'table'=>$taperange['table'],
					'min'=>$tmax,
					'max'=>$taperange['max'],
					'residual'=>1
				));	
			}
						
			
		} else {
			array_push($taperanges,$taperange);
		}
	}
	
	//print_r($taperanges); die();
	
	$missingtapes=array();
	
	foreach ($taperanges as $taperange){
		$tkey=$taperange['table'];
		$forced=0;
		if (isset($taperange['forced'])&&$taperange['forced']) $forced=1;
		if (isset($taperange['residual'])&&$taperange['residual']) $forced=1;		
		
		$query="select backupid,tapemin,tapemax from backuptapes where tapekey=? and tapedate<=? and tapemin=? and tapemax>=? order by tapedate desc limit 1";
		$rs=sql_prep($query,$db,array($tkey,$cur,$taperange['min'],$taperange['max']));
		if ((!$myrow=sql_fetch_assoc($rs))||$forced){
			array_push($missingtapes,$taperange);	
		} else {
			//check if actual file exists
			$tapefn=$tkey.'_'.$myrow['tapemin'].'_'.$myrow['tapemax'];
			if (!file_exists($backupdir.$tapefn.'.tape.sql')){
				$taperange['missingfile']=$tapefn.'.tape.sql';
				array_push($missingtapes,$taperange);
			} else {
				
				$backups[$tapefn]=$myrow['backupid'];
			}
		}
		
	}//foreach taperange
		
	$res=array(
		'remtables'=>$remtables,
		'missingfiles'=>$missingfiles,
		'fixtables'=>$fixtables,
		'deltatables'=>$deltatables,
		'fulltables'=>$fulltables,
		'missingtapes'=>$missingtapes,
		'backups'=>$backups,
		'maxtime'=>$maxtime,
		'dmaxtime'=>date('Y-m-d-H',$maxtime)
	);
	
	return $res;
}



function showbanner(){
?>

Incremental DB Backup Utility
=============================

<?php	
}

function showhelp(){
?>

  --help        this help message
  --restore [YYYY-MM-DD-HH]        restore the database to the time point
  --preview		
  
<?php	

	die();
}



