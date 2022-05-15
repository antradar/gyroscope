<?php

function listhomedashreports(){

	global $db;
	$user=userinfo();
	$gsid=$user['gsid'];
	$userid=$user['userid'];
	
	$query="select * from ".TABLENAME_HOMEDASHREPORTS." where ".COLNAME_GSID."=? and (userid=? or shared=1) order by rptname";
	$rs=sql_prep($query,$db,array($gsid,$userid));
	$c=sql_affected_rows($db,$rs);
	if (!$c) return;
	
?>
<div class="sectionheader">My Reports</div>
<?php		
	while ($myrow=sql_fetch_assoc($rs)){
		$rptuserid=$myrow['userid'];
		$homedashreportid=$myrow['homedashreportid'];
		$bingo=intval($myrow['bingo']);
		$rptname=$myrow['rptname'];
		$rptkey=$myrow['rptkey'];
		$rpttitle=$myrow['rpttitle'];
		$drpttitle=noapos(htmlspecialchars(htmlspecialchars($rpttitle)));
		$rptlink=$myrow['rptlink'];
		$rpttabkey=$myrow['rpttabkey'];
		$shared=$myrow['shared'];
	?>
	<div style="float:left;min-width:30%;margin-right:2%;margin-bottom:10px;">
		<nobr>
		<img src="imgs/t.gif" class="ico-report"><a onclick="ajxjs(self.delhomedashreport,'reports.js');closetab('<?php echo $rptkey;?>');addtab('<?php echo $rptkey;?>','<?php echo $drpttitle;?>','<?php echo $rptlink;?>',self.rptinit_<?php echo $rpttabkey;?>?rptinit_<?php echo $rpttabkey;?>:null,null,{bingo:<?php echo $bingo;?>});" class="hovlink"><?php echo htmlspecialchars($rptname);?></a>
		<?php if ($rptuserid==$userid && $user['groups']['sharedashreports']){?>
		&nbsp;
		<acronym title="share this report"><input type="checkbox" onclick="ajxjs(self.delhomedashreport,'reports.js');sharehomedashreport(<?php echo $homedashreportid;?>,this);" <?php if ($shared) echo 'checked';?>></acronym>
		&nbsp; &nbsp;
		<a onclick="ajxjs(self.delhomedashreport,'reports.js');delhomedashreport(<?php echo $homedashreportid;?>);"><img src="imgs/t.gif" class="img-del"></a>
		<?php
		}
		?>
		</nobr>
		
		
	</div>
	<?php
		
	}//while

	?>
	<div class="clear" style="margin-bottom:20px;"></div>
	<?php
}

