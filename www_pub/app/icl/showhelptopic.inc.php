<?php

include 'icl/safe_showhelptopic.inc.php';

function showhelptopic($helptopicid=null){
	if (!isset($helptopicid)) $helptopicid=GETVAL('helptopicid');
	
	global $db;
	global $codepage;
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	$query="select * from ".TABLENAME_HELPTOPICS." where helptopicid=?";
	$rs=sql_prep($query,$db,array($helptopicid));
	
	if (!$myrow=sql_fetch_array($rs)) die(_tr('record_removed'));
	
	$helptopictitle=$myrow['helptopictitle'];
	$helptopickeywords=$myrow['helptopickeywords'];
	$helptopictext=$myrow['helptopictext'];
	
	//$helptopictext=htmlspecialchars($helptopictext);
	
	//$helptopictext=str_replace("\n","<br>\n",$helptopictext);
	//$helptopictext=str_replace("\t","&nbsp; &nbsp; ",$helptopictext);
	
	$mysort=$myrow['helptopicsort'];
	
	$nextid=null; $nexttitle=null;
	$previd=null; $prevtitle=null;
	
	$query="select helptopicid,helptopictitle from ".TABLENAME_HELPTOPICS." where helptopicid!=? and helptopicsort>? order by helptopicsort limit 1";
	$rs=sql_prep($query,$db,array($helptopicid,$mysort));
	if ($myrow=sql_fetch_assoc($rs)){
		$nextid=$myrow['helptopicid'];
		$nexttitle=$myrow['helptopictitle'];
	}

	$query="select helptopicid,helptopictitle from ".TABLENAME_HELPTOPICS." where helptopicid!=? and helptopicsort<? order by helptopicsort desc limit 1";
	$rs=sql_prep($query,$db,array($helptopicid,$mysort));
	if ($myrow=sql_fetch_assoc($rs)){
		$previd=$myrow['helptopicid'];
		$prevtitle=$myrow['helptopictitle'];
	}

	header('newtitle: '.tabtitle('<img src="imgs/t.gif" class="ico-helptopic">'.htmlspecialchars($helptopictitle)));
	
?>
<div id="helptopic_sandbox_warning_<?php echo $helptopicid;?>" style="display:none;">
	<div class="section">
		<div class="sectiontitle"><?php echo htmlspecialchars($helptopictitle);?></div>
		
		<div class="warnbox">
		Your browser is missing an important sandboxing feature that prevents rich content from carrying malicious data.
		<br>
		For your protection, the requested help page is not shown.
		</div>
	</div>
</div>
<iframe id="helptopic_sandbox_view_<?php echo $helptopicid;?>" sandbox="allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-scripts" style="width:100%;height:84%;border:none;" frameborder="no" src="<?php echo $codepage;?>?cmd=safe_showhelptopic&helptopicid=<?php echo $helptopicid;?>"></iframe>

<div class="section" style="padding-top:0;margin-top:0;">
	<?php if (isset($previd)){?>
	<div style="padding-top:20px;">
		<em>Prev:</em> &nbsp;<a class="hovlink" onclick="showhelptopic('<?php echo $previd;?>','<?php echo noapos(htmlspecialchars(htmlspecialchars($prevtitle)));?>');"><?php echo htmlspecialchars($prevtitle);?></a>
	</div>
	<?php } ?>

	<?php if (isset($nextid)){?>
	<div style="text-align:right;padding-top:20px;">
		<em>Next:</em> &nbsp;<a class="hovlink" onclick="showhelptopic('<?php echo $nextid;?>','<?php echo noapos(htmlspecialchars(htmlspecialchars($nexttitle)));?>');"><?php echo htmlspecialchars($nexttitle);?></a>
	</div>
	<?php } ?>
</div>
	
<?php
}
