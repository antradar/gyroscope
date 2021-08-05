<?php

function listhomedashreports(){

	global $db;
	$user=userinfo();
	$userid=$user['userid'];
	
	$query="select * from ".TABLENAME_HOMEDASHREPORTS." where userid=? order by rptname";
	$rs=sql_prep($query,$db,$userid);
	$c=sql_affected_rows($db,$rs);
	if (!$c) return;
	
?>
<div class="sectionheader">My Reports</div>
<?php		
	while ($myrow=sql_fetch_assoc($rs)){
		$homedashreportid=$myrow['homedashreportid'];
		$rptname=$myrow['rptname'];
		$dname=noapos(htmlspecialchars(htmlspecialchars($rptname)));
		$rptkey=$myrow['rptkey'];
		$rpttitle=$myrow['rpttitle'];
		$drpttitle=noapos(htmlspecialchars(htmlspecialchars($rpttitle)));
		$rptlink=$myrow['rptlink'];
		$rpttabkey=$myrow['rpttabkey'];
	?>
	<div style="float:left;width:30%;margin-right:2%;margin-bottom:10px;">
		<nobr>
		<img src="imgs/t.gif" class="ico-report"><a onclick="ajxjs(self.delhomedashreport,'reports.js');closetab('<?php echo $rptkey;?>');addtab('<?php echo $rptkey;?>','<?php echo $dname;?>','<?php echo $rptlink;?>',self.rptinit_<?php echo $rpttabkey;?>?rptinit_<?php echo $rpttabkey;?>:null);" class="hovlink"><?php echo htmlspecialchars($rptname);?></a>
		&nbsp;
		<a onclick="ajxjs(self.delhomedashreport,'reports.js');delhomedashreport('<?php echo $homedashreportid;?>');"><img src="imgs/t.gif" class="img-del"></a>
		</nobr>
		
		
	</div>
	<?php
		
	}//while

	?>
	<div class="clear" style="margin-bottom:20px;"></div>
	<?php
}

