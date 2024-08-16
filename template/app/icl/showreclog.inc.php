<?php

function showreclog($rectype,$recid=null){
	if (!isset($recid)) $recid=GETVAL('recid');
	global $db;

	$query="select actionlog.*,dispname from actionlog left join users on actionlog.userid=users.userid where rectype=? and recid=? order by logdate desc";
	$rs=sql_prep($query,$db,array($rectype,$recid));
	$c=sql_affected_rows($db,$rs);
	
	if ($c>0){
?>
<div class="sectionheader">Change History</div>
<?php
	}

	while ($myrow=sql_fetch_assoc($rs)){
		//echo '<pre>'; print_r($myrow); echo '</pre>';
		$logdate=$myrow['logdate'];
		$ddate=date('Y-n-j g:ia',$logdate);
		$dispname=$myrow['dispname'];
		$obj=json_decode($myrow['rawobj'],1);
	?>
	<div class="listitem">
		<div><b><?php echo $ddate;?></b> <?php echo htmlspecialchars($dispname);?></div>
		<ul>
		<?php
			foreach ($obj as $k=>$v){
				if ($k==$rectype.'id') continue;
				$v=str_replace('->','<span style="color:#ffab00;">&rArr;</span>',$v);
			?>
			<li><?php echo htmlspecialchars($k);?>: <?php echo $v;?></li>
			<?php
			}
		?>
		</ul>
	</div>
	<?php
	}//while

}