<?
///+handler::lc::listcontacts]
function formatphone($n){
  $num=$n;

  $pattern='/^\d+/';
  if (!preg_match($pattern,$n)) return $num;
  if (strlen($num)!=10) return $num;

  $num='('.substr($n,0,3).') '.substr($n,3,3).'-'.substr($n,6,4);
  return $num;
}

function listcontacts($personid=null){
global $db;
global $HTTP_GET_VARS;
if (!isset($personid)) $personid=$HTTP_GET_VARS['pid'];
?>
<table style="margin-bottom:2px;margin-left:10px;">
<?
$query="select * from personcontacts where personid=$personid";
$rs=sql_query($query,$db);
while ($myrow=sql_fetch_array($rs)){
  $pcid=$myrow['pcid'];
  $ctname=$myrow['ctname'];
  $ctval=formatphone($myrow['ctval']);
?>
<tr><td><nobr><?echo $ctname;?></nobr>:</td><td><?echo $ctval;?></td>
<td><a onclick="deletecontact('<?echo $pcid;?>','<?echo $personid;?>');">[x]</a></td>
</tr>
<?
}
?>
</table>
<div style="margin-left:10px;margin-bottom:5px;">
<acronym title="Contact Type, e.g: 'Phone'">
<input id="nctname_<?echo $personid;?>" style="width:70px;"></acronym>:&nbsp;
<acronym title="Contact Value, e.g.: 'info@domushousing.com'">
<input id="nctval_<?echo $personid;?>"></acronym>
&nbsp;
<a onclick="addcontact('<?echo $personid;?>');">[Add]</a>
</div>
<?
}

///-handler::lc::listcontacts]

///+handler::act::addcontact]
function addcontact(){
global $db;
global $HTTP_GET_VARS;

$pid=$HTTP_GET_VARS['pid'];
$ctname=$HTTP_GET_VARS['ctname'];
$ctval=$HTTP_GET_VARS['ctval'];
$query="insert into personcontacts (personid,ctname,ctval) values(";
$query.="$pid,'$ctname','$ctval')";
sql_query($query,$db);
}

///-handler::act::addcontact]

///+handler::dct::deletecontact]
function deletecontact(){
global $db;
global $HTTP_GET_VARS;

$pcid=$HTTP_GET_VARS['pcid'];
//todo: authenticate by group

$query="delete from personcontacts where pcid=$pcid";
sql_query($query,$db);
}

///-handler::dct::deletecontact]


?>