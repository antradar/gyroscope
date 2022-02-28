<?php

include 'icl/safe_showhelptopic.inc.php';

function showhelptopic($helptopicid=null){
	if (!isset($helptopicid)) $helptopicid=SGET('helptopicid');
	
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
	


	header('newtitle:'.tabtitle('<img src="imgs/t.gif" class="ico-helptopic">'.htmlspecialchars($helptopictitle)));
	
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
<iframe id="helptopic_sandbox_view_<?php echo $helptopicid;?>" sandbox="allow-popups allow-popups-to-escape-sandbox" style="width:100%;height:100%;border:none;" frameborder="no" src="<?php echo $codepage;?>?cmd=safe_showhelptopic&helptopicid=<?php echo $helptopicid;?>"></iframe>
<?php
}
