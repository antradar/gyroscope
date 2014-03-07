<?php

include 'icl/listfilmactors.inc.php';
include 'icl/listfilminventory.inc.php';

function showfilm($filmid=null){
	if (!isset($filmid)) $filmid=GETVAL('filmid');
	
	global $db;
	
	$query="select * from film left join language on film.language_id=language.language_id where film_id=$filmid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) die('Invalid record');
	
	$title=htmlspecialchars($myrow['title']);
	$year=$myrow['release_year']+0;
	$language=htmlspecialchars($myrow['name']);
	$description=htmlspecialchars($myrow['description']);
	
?>
<div class="section">
	<div class="col">
		<div class="sectionheader">Film Details</div>
		<table>
		<tr>
			<td class="formlabel">Title:</td>
			<td><input id="filmtitle_<?echo $filmid;?>" class="inplong" value="<?echo $title;?>"></td>
		</tr>
		<tr>
			<td class="formlabel">Release Year:</td>
			<td><input id="filmyear_<?echo $filmid;?>" class="inpshort" value="<?echo $year;?>"></td>
		</tr>
		<tr>
			<td class="formlabel" valign="top">Language:</td>
			<td valign="top">
				<input id="filmlanguage_<?echo $filmid;?>" disabled class="inpshort" value="<?echo $language;?>" 
					onfocus="lookuplanguage(this);" onkeyup="_lookuplanguage(this);"> 
				<span id="filmlanguage_<?echo $filmid;?>_val2">
					<?cancelpickup('filmlanguage_'.$filmid);?>
				</span>
				<?makelookup('filmlanguage_'.$filmid);?>
			</td>
		</tr>
		<tr>
			<td class="formlabel" valign="top">Description:</td>
			<td valign="top">
				<textarea id="filmdescription_<?echo $filmid;?>" class="inplong" style="height:100px;"><?echo $description;?></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><button onclick="updatefilm(<?echo $filmid;?>);">Save Changes</button> &nbsp; &nbsp; 
			<button onclick="delfilm(<?echo $filmid;?>);" style="background-color:#ab0200;color:#ffffff;">Delete Film</button></td>
		</tr>
		</table>
	</div>
	<div class="col">
		<div class="sectionheader">Cast</div>
		<div id="filmactors_<?echo $filmid;?>">
			<?listfilmactors($filmid);?>
		</div>
	</div>
	<div class="col">
		<div class="sectionheader">Inventory</div>
		<div id="filminventory_<?echo $filmid;?>">
			<?listfilminventory($filmid);?>
		</div>
		
	</div>
	<div class="clear"></div>
</div>
<?		
}