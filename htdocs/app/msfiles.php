<?php

include 'lb.php';
include 'auth.php';
include 'connect.php';

include 'inbound/libmsgraph.php';

login();

$user=userinfo();
$userid=$user['userid'];

$query="select msgraphtoken from users where userid=$userid";

$rs=sql_query($query,$db);
$myrow=sql_fetch_assoc($rs);
$rtoken=$myrow['msgraphtoken'];

if ($rtoken!=''){
	$token=msgraph_gettoken($rtoken);
	$me=msgraph_request($token,'/me');
	//echo '<pre>'; print_r($me); echo '</pre>';
	if ($me['displayName']=='') $rtoken='';
} else die('You are not connected to a Microsoft account');


$drives=msgraph_getmydrives($token);

echo '<pre>'; print_r($drives); echo '</pre>'; die();

$itemid=null;

if (isset($_GET['itemid'])) $itemid=$_GET['itemid'];

$download=intval($_GET['download']);

if (!$download||!$itemid){
	$files=msgraph_getfiles($token,$itemid);
} else {
	$content=msgraph_downloadfile($token,$itemid);//msgraph_request($token,'/drive/items/'.$itemid);
	
}

//echo '<pre>'; print_r($files); echo '</pre>';

//echo '<pre>'; print_r($res); echo '</pre>';

render_files($files);

/////////

function render_files($files){
	$parentid=$files['parentid'];
	if (isset($parentid)){
?>
<div>
	<a href="msfiles.php?itemid=<?php echo $parentid;?>">&laquo; back</a>
</div>
<?php
	}
?>
<table>
<?php
	foreach ($files['files'] as $file){
?>
<tr>
	<td>
	<?php 
	if ($file['type']=='folder'){
	?>
		<a href="msfiles.php?itemid=<?php echo $file['id'];?>" style="color:#848cf7;"><?php echo htmlspecialchars($file['name']);?>/</a>
	<?php
	} else {
	?>
		<a target=_blank href="msfiles.php?itemid=<?php echo $file['id'];?>&download=1" style="color:#444444;"><?php echo htmlspecialchars($file['name']);?></a>
	<?php
	}
	?></td>
	<td><?php echo $file['size'];?></td>
</tr>
<?php
	}
?>
</table>
<?php
}

/////////////



