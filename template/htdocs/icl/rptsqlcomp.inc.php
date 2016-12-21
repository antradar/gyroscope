<?php

function rptsqlcomp(){
	$mode=GETSTR('mode');
	if ($mode!='comp'){
?>
<div class="section">
	<div class="sectiontitle">Dev Tools &raquo; DB Structure Comparison</div>
	
	<div class="sectionheader">My Database Snapshot</div>
	<textarea class="inplong"><?echo base64_encode(serialize(mydbobj()));?></textarea>	

	<div class="sectionheader">Other Database Snapshot</div>
	<textarea class="inplong" id="sqlcomp_otherobj"></textarea>	
	
	<div><button onclick="ajxpgn('sqlcompareview',document.appsettings.codepage+'?cmd=rptsqlcomp&mode=comp',0,0,'other='+gid('sqlcomp_otherobj').value)">Compare</button></div>
	
	<div id="sqlcompareview">
<?
} else {
	$other=trim(QETSTR('other'));

	sqlobjcomp(mydbobj(), unserialize(base64_decode($other)));	

}	

if ($mode!='comp'){
?>	
	</div>	
</div>
<?		
}
	
}

function tablecomp($my,$other){
	$same=1;
	$diffs=array();
		
	foreach ($my as $k=>$v){
		if (!isset($other[$k])||!is_array($other[$k])) {$diffs[$k]=array('status'=>'dne_other');$same=0;}
	}
	
	foreach ($other as $k=>$v){
		if (!isset($my[$k])||!is_array($my[$k])) {$diffs[$k]=array('status'=>'dne_mine');$same=0;}
		else {
			if ($my[$k]['Type']!=$other[$k]['Type']) {$diffs[$k]=array('status'=>'typediff','mytype'=>$my[$k]['Type'],'othertype'=>$other[$k]['Type']);$same=0;}	
		}	
	}
	
	
	return array('same'=>$same,'diffs'=>$diffs);	
}

function sqlobjcomp($my,$other){
	$diffs=array();
	if (!is_array($other)) $other=array();
		
	foreach ($my as $k=>$v){
		if (!isset($other[$k])||!is_array($other[$k])) $diffs[$k]=array('status'=>'dne_other');
	}
	
	foreach ($other as $k=>$v){
		if (!isset($my[$k])||!is_array($my[$k])) $diffs[$k]=array('status'=>'dne_mine');
		else {
			$tablecomp=tablecomp($my[$k],$other[$k]);
			$sametable=$tablecomp['same'];
			$coldiffs=$tablecomp['diffs'];
			
			
			

			if (!$sametable) $diffs[$k]=array('status'=>'coldiff','coldiffs'=>$coldiffs);
		}
	}	
	
	
	//echo '<pre>'; print_r($diffs); echo '</pre>';
?>
<table style="width:100%;">
	<tr><td style="width:50%;"><b>Mine</b></td><td style="width:50%;"><b>Other's</b></td></tr>
<?	
	foreach ($diffs as $table=>$diff){
		switch ($diff['status']){
		case 'dne_mine': 
	?>
	<tr><td><em>missing</em></td><td><?echo $table;?></td></tr>
	<?	
		break;
		
		case 'dne_other': 
	?>
	<tr><td><?echo $table;?></td><td><em>missing</em></td></tr>
	<?	
		break;
		case 'coldiff': 
	?>
	<tr><td colspan="2"><div><?echo $table?> not the same</div></td></tr>
	<tr><td colspan="2" style="text-align:center;">
		<table style="width:80%;margin:0 auto;text-align:left;">
		<?
			foreach ($diff['coldiffs'] as $field=>$coldiff){
				
				switch($coldiff['status']){
					
				case 'dne_mine':
		?>
		<tr><td><em>missing</em></td><td><?echo $field;?></td></tr>
		<?		
				break;
				case 'dne_other':
		?>
		<tr><td><?echo $field;?></td><td><em>missing</em></td></tr>
		<?		
				break;	
				case 'typediff':
		?>
		<tr><td><?echo $coldiff['mytype'];?></td><td><?echo $coldiff['othertype'];?></td></tr>
		<?		
				break;	
							
				}//switch coldiffstatus
			}//foreach coldiff
		?>
		</table>
	</td></tr>
	<?	
		break;				
		}//switch
	}//foreach
?>
</table>	
<?
	
}

function mydbobj(){
	global $db;

	$query="show tables";
	$rs=sql_query($query,$db);
	
	$mydb=array();
	
	while ($myrow=sql_fetch_assoc($rs)){
		foreach ($myrow as $k=>$v) {
			$mydb[$v]=array();
			$query="describe $v";
			$rs2=sql_query($query,$db);
			while ($myrow2=sql_fetch_assoc($rs2)){
				$mydb[$v][$myrow2['Field']]=$myrow2;				
			}//while
		}	
	}//while
		
	return $mydb;
}