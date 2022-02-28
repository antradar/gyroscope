<?php

//include 'tmpl.php';

function safe_showhelptopic($helptopicid=null){
	if (!isset($helptopicid)) $helptopicid=SGET('helptopicid');
	
	global $db;
	
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
	


	header('newtitle:'.tabtitle(htmlspecialchars($helptopictitle)));
	
?>
<html>
<head>
	<link href="gyroscope.css?v=2" type="text/css" rel="stylesheet" />
	<link href="toolbar.css" type="text/css" rel="stylesheet" />
	<link href="tiny_mce/editor.css" type="text/css" rel="stylesheet" />
</head>
<body style="background:#ffffff;">
<div class="section">
	<div class="sectiontitle"><?php echo htmlspecialchars($helptopictitle);?></div>
	
	<?php if (is_callable('tmpl')) tmpl($helptopictext); else echo $helptopictext;?>
</div>
</body>
</html>
<?php
}
