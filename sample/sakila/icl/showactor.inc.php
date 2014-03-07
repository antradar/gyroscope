<?php

include 'icl/listactorfilms.inc.php';

function showactor(){
	$actorid=GETVAL('actorid');

	global $db;
	
	$query="select * from actor where actor_id=$actorid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('invalid record');
	
	$fname=htmlspecialchars($myrow['first_name']);
	$lname=htmlspecialchars($myrow['last_name']);
?>
<div class="section">
	<div class="col">
		<div class="sectionheader">Basic Info</div>
		<table>
		<tr>
			<td class="formlabel">ID:</td>
			<td><?echo $actorid;?></td>
		<tr>
			<td class="formlabel">First Name:</td>
			<td><input id="actor_fname_<?echo $actorid;?>" class="inp" value="<?echo $fname;?>"></td>
		</tr>
		<tr>
			<td class="formlabel">Last Name:</td>
			<td><input id="actor_lname_<?echo $actorid;?>" class="inp" value="<?echo $lname;?>"></td>
		</tr>
		<tr>
			<td></td>
			<td><button onclick="updateactor(<?echo $actorid;?>);">Save Changes</button></td>
		</tr>
		
		</table>
	</div>
	<div class="col">
		<div class="sectionheader">Films</div>
		<div id="actorfilms_<?echo $actorid;?>">
		<?listactorfilms($actorid);?>
		</div>
	</div>
	<div class="clear"></div>

</div>
<?		
	

}