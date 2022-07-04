<?php

function listsslcerts(){
	global $db;

	$now=time();
	$tscan=$now-3600-1800;
	$texp=$now+3600*24*1;
	$twarn=$now+3600*24*5;

	$query="select * from sslcerts where (lastscanned<? or certexp<?)  order by certserver,certexp ";
	$rs=sql_prep($query,$db,array($tscan,$twarn));

	$c=sql_affected_rows($db,$rs);

	if ($c>0){
	?>
	<div class="sectionheader">SSL Certificate Issues</div>
	<?php
	}

	?>
	<div class="stable">
	<table class="subtable">
	<?php if ($c>0){?>
	<tr>
		<td><b>Server</b></td>
		<td><b>Cert</b></td>
		<td><b>Domain</b></td>
		<td><b>Expiry</b></td>
		<td><b>Last Scanned</b></td>
		<td><b>Issue</b></td>
	</tr>
	<?php }?>
	<?php
	while ($myrow=sql_fetch_assoc($rs)){
		//echo '<pre>'; print_r($myrow); echo '</pre>';
		$lastscanned=$myrow['lastscanned'];
		$certexp=$myrow['certexp'];
		$certfn=$myrow['certfn'];
		$dscanned=date('Y-n-j H:i:s',$lastscanned);
		$dexp=date('Y-n-j',$certexp);
		$server=$myrow['certserver'];
		$alts=explode(',',$myrow['certalts']);
	?>
	<tr>
	<td valign="top"><?php echo $server;?></td>
	<td valign="top"><?php echo $certfn;?></td>
	<td valign="top">
		<?php echo htmlspecialchars($myrow['certdomain']);?>
		<?php foreach ($alts as $alt){
			if (trim($alt)=='') continue;
		?>
			<br><span style="color:#848cf7;">+</span> <?php echo htmlspecialchars($alt);?>
		<?php }?>
	</td>
	<td valign="top"><?php echo $dexp;?></td>
	<td valign="top"><?php echo $dscanned;?></td>
	<td valign="top">
		<?php 
		$label='';
		if ($certexp<$twarn) $label='DUE in '.(ceil(($twarn-$now)/3600/24)).' days';
		if ($certexp<$texp) $label='EXPIRED';
		if ($lastscanned<$tscan) $label.=' (stale scan)';

		echo $label;
		?>
	</td>
	</tr>
	<?php

	}//while
	?>
	</table>
	</div>
	<?php
}
