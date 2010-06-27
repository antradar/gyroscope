<?
function listlandlords(){

	global $db;
	global $HTTP_GET_VARS;

	$key=GETSTR('key');
?>
<div class="section"><div style="margin-bottom:6px;">
<div>
	<a onclick="addtab('new_landlord','New Landlord','nl',function(){gid('nll_fname').focus();});" 
	onmouseover="hintstatus('Add Landlord',this);">
		<img src="imgs/addlandlord.gif">
	</a>
</div>
<div  style="font-size:12px;">
<?
	$query="select landlords.*, persons.* from landlords, persons ";
	$query.=" where landlords.personid=persons.personid order by persons.fname";
	$rs=sql_query($query,$db);

	while ($myrow=sql_fetch_array($rs)){
		$llid=$myrow['llid'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
?>
	<div style="border-bottom:solid 1px #606060;position:relative;">
		<a onclick="addtab('landlord_<?echo $llid;?>','<?echo $fname;?>','dt0&llid=<?echo $llid;?>');"><?echo ' '.$fname.' '.$lname;?></a>
	</div>
<?
	}//while
?>
</div>
</div></div>
&nbsp;
<script>
	gid('tooltitle').innerHTML='<a>Landlords</a>';
	ajxjs(self.addlandlord,'landlords.js');
	ajxjs(self.addproperty,'properties.js');
	ajxjs(self.addlease,'leases.js');
</script>
<?
}


///+handler::dt0::showlandlord]
function showlandlord(){
	global $db;
	include_once 'icl/persons.inc.php';
	
	$llid=GETVAL('llid');

	$query="select landlords.*, persons.* from landlords, persons where landlords.personid=persons.personid and landlords.llid=$llid";
	$rs=sql_query($query,$db);
	
	if (!$myrow=sql_fetch_array($rs)) return;
	
	$llid=$myrow['llid'];
	$personid=$myrow['personid'];
	$fname=$myrow['fname'];
	$lname=$myrow['lname'];
	
	$addr=$myrow['addr'];
	$city=$myrow['city'];
	$prov=$myrow['prov'];
	$country=$myrow['country'];
	$zip=$myrow['zip'];
	
	
	$tfname="First name:";
	$tlname="Last name:";
?>

<div class="section">
<div style="margin-bottom:6px;">
<div class="sectiontitle"><?echo "$fname $lname";?></div>
<div style="width:400px;float:left;margin-right:10px;">

<div class="sectionheader">General Info</div>
<table style="margin-bottom:5px;">
<tr>
<td><span id="ll<?echo $llid;?>_tfname"><?echo $tfname;?></span></td>
<td><input id="ll<?echo $llid;?>_fname" value="<?echo $fname;?>"></td>
</tr>
<tr>
<td><span id="ll<?echo $llid;?>_tlname"><?echo $tlname;?></span></td>
<td><input id="ll<?echo $llid;?>_lname" value="<?echo $lname;?>"></td>
</tr>
<tr>
<td>Address:</td>
<td><input id="ll<?echo $llid;?>_addr" value="<?echo $addr;?>" style="width:250px;"></td>
</tr>
<tr>
<td>City:</td>
<td><input onfocus="lookupcity(this);" onkeyup="_lookupcity(this);" id="ll<?echo $llid;?>_city" value="<?echo $city;?>"></td>
</tr>
<tr>
<td>Province:</td>
<td><input onfocus="lookupprov(this);" onkeyup="_lookupprov(this);" id="ll<?echo $llid;?>_prov" value="<?echo $prov;?>">
<input id="ll<?echo $llid;?>_country" type="hidden" value="<?echo $country;?>">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input id="ll<?echo $llid;?>_zip" value="<?echo $zip;?>"></td>
</tr>
<tr><td></td><td>
<button onclick="ajxjs(self.addlandlord,'landlords.js');addlandlord('<?echo $llid;?>');">Update</button>
</td></tr>
</table>

<div class="sectionheader">Contact Info</div>
<div id="contactlist_<?echo $personid;?>">
<?
	listcontacts($personid);
?>
</div>


</div><!-- left float -->

<div style="width:400px;float:left;margin-right:10px;">
<div class="sectionheader">Property Ownership</div>
<div id="propertylist_<?echo $llid;?>" style="margin-top:5px;">
<?
	listlandlordproperties($llid);
?>
</div>
</div><!-- left float -->

</div><!-- margin -->
</div>

<?
}

///-handler::dt0::showlandlord]

///+handler::nl::newlandlord]
function newlandlord(){
?>
<div class="section">
<div style="margin-bottom:6px;">

<table style="margin-bottom:5px;">
<tr>
<td><span id="nll_tfname">First name:</span></td>
<td><input id="nll_fname"> *</td>
</tr>
<tr>
<td><span id="nll_tlname">Last name:</span></td>
<td><input id="nll_lname"> *</td>
</tr>
<tr>
<td>Address:</td>
<td><input id="nll_addr"> *</td>
</tr>
<tr>
<td>City:</td>
<td><input onfocus="lookupcity(this);" onkeyup="_lookupcity(this);" id="nll_city"> *</td>
</tr>
<tr>
<td>Province:</td>
<td><input onfocus="lookupprov(this);" onkeyup="_lookupprov(this);" id="nll_prov" value="ON"> *
<input id="nll_country" type="hidden" value="Canada">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input id="nll_zip"> *</td>
</tr>
</table>
<table style="margin-bottom:5px;">
<tr>
<td>Phone(s):</td>
<td><input id="nll_phones"></td>
</tr>
<td>Cell(s):</td>
<td><input id="nll_cells"></td>
</tr>
<td>Email(s):</td>
<td><input id="nll_emails"></td>
</tr>
<tr><td></td><td><em>* required fields</em></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center">
<button onclick="addlandlord();">Add New</button>
</td></tr>
</table>

</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

///-handler::nl::newlandlord]

///+handler::al::addlandlord]
function addlandlord(){
	global $db;

	$fname=GETSTR('fname');
	$lname=GETSTR('lname');
	$addr=GETSTR('addr');
	$city=GETSTR('city');
	$prov=GETSTR('prov');
	$country=GETSTR('country');
	$zip=GETSTR('zip');
	$zip=str_replace(' ','',$zip);
	$zip=strtoupper($zip);
	
	$phones=explode(";",GETSTR('phones'));
	$cells=explode(";",GETSTR('cells'));
	$emails=explode(";",GETSTR('emails'));
	
	$query="insert into persons(fname,lname,addr,city,prov,country,zip) ";
	$query.="values('$fname','$lname','$addr','$city','$prov','$country','$zip')";
	
	$rs=sql_query($query,$db);
	
	$personid=sql_insert_id($rs);
	
	$query="insert into landlords (personid) values ($personid)";
	$rs=sql_query($query,$db);
	
	$llid=sql_insert_id($rs);

	//add to contact table by personid
	foreach ($phones as $phone){
		if (trim($phone)=='') continue;
		$query="insert into personcontacts(personid,ctname,ctval) values (";
		$query.="$personid,'Phone','$phone')";
		sql_query($query,$db);
	}
	foreach ($cells as $cell){
		if (trim($cell)=='') continue;
		$query="insert into personcontacts(personid,ctname,ctval) values (";
		$query.="$personid,'Cell','$cell')";
		sql_query($query,$db);
	}
	foreach ($emails as $email){
		if (trim($email)=='') continue;
		$query="insert into personcontacts(personid,ctname,ctval) values (";
		$query.="$personid,'Email','$email')";
		sql_query($query,$db);
	}

	echo $llid;
}

///-handler::al::addlandlord]

///+handler::ul::updatelandlord]
function updatelandlord(){
	global $db;

	$fname=GETSTR('fname');
	$lname=GETSTR('lname');
	$addr=GETSTR('addr');
	$city=GETSTR('city');
	$prov=GETSTR('prov');
	$country=GETSTR('country');
	$zip=GETSTR('zip');
	$zip=str_replace(' ','',$zip);
	$zip=strtoupper($zip);

	$llid=GETVAL('llid');

	//lookup personid
	$query="select personid from landlords where llid=$llid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);
	$personid=$myrow['personid'];

	$query="update persons set";
	$query.=" fname='$fname',";
	$query.=" lname='$lname',";
	$query.=" addr='$addr',";
	$query.=" city='$city',";
	$query.=" prov='$prov',";
	$query.=" country='$country',";
	$query.=" zip='$zip' ";

	$query.="where personid=$personid";

	$rs=sql_query($query,$db);
}

///-handler::ul::updatelandlord]

///+handler::llpr::listlandlordproperties]
function listlandlordproperties($llid=null){
	global $db;
	if ($llid==null) $llid=GETVAL('llid');
?>
<div id="list_ll_<?echo $llid;?>" style="margin-bottom:10px;">
<?
	$query="select * from properties where llid=$llid order by addr";
	$rs=sql_query($query,$db);
	while ($myrow=sql_fetch_array($rs)){
		$addr=$myrow['addr'];
		$zip=$myrow['zip'];
		$prid=$myrow['prid'];
		$llid=$myrow['llid'];
?>
<div><a onclick="addtab('property_<?echo $prid;?>','<?echo $addr;?>','dt1&prid=<?echo $prid;?>');">
<?echo $addr;?>, <?echo $zip?></a></div>
<?
	}
?>
</div>

<div>
<a onclick="addtab('new_property_<?echo $llid;?>','New Property','npr&llid=<?echo $llid;?>',function(){gid('npr_addr_<?echo $llid;?>').focus();});" 
onmouseover="hintstatus('Add Property',this);">
<img src="imgs/addproperty.gif">
</a>
</div>
<?
}
///-handler::llpr::listlandlordproperties]

