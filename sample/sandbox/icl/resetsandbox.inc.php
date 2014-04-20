<?php
include 'icl/showwelcome.inc.php';

function resetsandbox(){


	$rootfiles=array(
		'index.php','settings.php','myservices.php',
		'autocomplete.js','blind.js','codegen.js','nano.js',
		'accounts.js', 'sandbox.js','tabs.js','validators.js','viewport.js'
	);
	
	$iclfiles=array(
		'listreports.inc.php', 'lookup.inc.php', 'resetsandbox.inc.php', 'rptactionlog.inc.php','setaccountpass.inc.php',
		'showaccount.inc.php', 'showguide.inc.php', 'showgyroscopeupdater.inc.php', 'showhelp.inc.php',
		'showwelcome.inc.php', 'updater.inc.php', 'utils.inc.php'
	);
	
	$dbtables=array(
		'actionlog','actors','filmactors','films','landlords','leases','leasetenants','properties','provs','tenants','docs'
	);

	//clear the database
	
	global $db;
	
	foreach ($dbtables as $table){
		$query="truncate $table";
		sql_query($query,$db);	
	}
	
	
	//delete icl files that are not in the icl list
	
	$dir = './icl/';
	
    if ($dh = opendir($dir)) {
        while (($filename = readdir($dh)) !== false) {
	        $filetype=filetype($dir.$filename);
	        if (strtolower($filetype)=='dir') continue;
	        
	        $rfn=$dir.$filename;
	        
            if (!in_array($filename,$iclfiles)) unlink($rfn);
        }
        closedir($dh);
    }
    
	//delete icl files that are not in the icl list
	
	$dir = './';
	
    if ($dh = opendir($dir)) {
        while (($filename = readdir($dh)) !== false) {
	        $filetype=filetype($dir.$filename);
	        if (strtolower($filetype)=='dir') continue;
	        $parts=explode('.',$filename);
	   		$ext=$parts[1];
	   		if (strtolower($ext)!='js') continue;    
	        $rfn=$dir.$filename;
	        
	        if (!in_array($filename,$rootfiles)) unlink($rfn);
	        
        }
        closedir($dh);
    }	
    
	//restore root files
	
	foreach ($rootfiles as $rootfile){
		copy('savepoint/'.$rootfile,$rootfile);	
	}
    
?>
<!-- {{ -->
</div>
<div style="background-color:#F2DEDE;padding:10px 20px;font-weight:bold;">
	The sandbox has been reset!
</div>
<div>
<!-- }} -->
<?	
	
	showwelcome();	
}