<?
///+handler::slv2::listtenants]
function listtenants(){
	global $db;

	$key=GETSTR('key');
	$mode=GETSTR('mode');

	if ($mode!='lk'){
?>
<div class="section">
<div style="margin-bottom:6px;">
<div>
<input style="background:transparent url(imgs/mg.gif) no-repeat top left;padding-left:13px;" id="keytenant" onkeyup="_tenant_lookuptenant();">
&nbsp;&nbsp;
<a onclick="addtab('new_tenant','New Tenant','ntn',function(){gid('ntn_fname').focus();});" 
onmouseover="hintstatus('Add Tenant',this);">
<img src="imgs/addclient.gif">
</a>
</div>
<div id="tenantlist" style="font-size:12px;">
<?
	}
	
	$query="select tenants.*, persons.* from tenants, persons where tenants.personid=persons.personid ";
	if (trim($key)!='') $query.=" and (persons.fname like '$key%' or persons.lname like '$key%') "; 
	$query.=" order by persons.lname";
	$rs=sql_query($query,$db);

	while ($myrow=sql_fetch_array($rs)){
		$tnid=$myrow['tnid'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
?>
<div style="border-bottom:solid 1px #606060;">
<a onclick="addtab('tenant_<?echo $tnid;?>','<?echo str_replace("'","\'",$fname);?>','dt2&tnid=<?echo $tnid;?>');"><?echo $fname.' '.$lname;?></a>
</div>
<?
	}
	
	if ($mode!='lk'){
?>
</div><!-- tenantlist -->
</div>
</div>
&nbsp;
<script>
gid('tooltitle').innerHTML='<a>Tenants</a>';
ajxjs(self.addtenant,'tenants.js');
</script>
<?
	}//mode
}

///-handler::slv2::listtenants]

///+handler::ntn::newtenant]
function newtenant(){
?>
<div class="section">
<div style="margin-bottom:6px;">

<table style="margin-bottom:5px;">
<tr>
<td><span id="ntn_tfname">First name:</span></td>
<td><input id="ntn_fname"> *</td>
</tr>
<tr>
<td><span id="ntn_tlname">Last name:</span></td>
<td><input id="ntn_lname"> *</td>
</tr>
<tr>
<td>Address:</td>
<td><input id="ntn_addr"> *</td>
</tr>
<tr>
<td>City:</td>
<td><input onfocus="lookupcity(this)" onkeyup="_lookupcity(this);" id="ntn_city"> *</td>
</tr>
<tr>
<td>Province:</td>
<td><input onfocus="lookupprov(this)" onkeyup="_lookupprov(this);" id="ntn_prov" value="ON"> *
<input id="ntn_country" type="hidden" value="Canada">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input id="ntn_zip"> *</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td>Phone(s):</td>
<td><input id="ntn_phones"></td>
</tr>
<tr>
<td>Cell(s):</td>
<td><input id="ntn_cells"></td>
</tr>
<tr>
<td>Email(s):</td>
<td><input id="ntn_emails"></td>
</tr>
<tr><td></td><td><em>* required fields</em></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="center">
<button onclick="addtenant();">Add New</button>
</td></tr>
</table>

</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

///-handler::ntn::newtenant]

///+handler::atn::addtenant]
function addtenant(){
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

	$query="insert into tenants (personid) values (";
	$query.="$personid)";
	$rs=sql_query($query,$db);

	$tnid=sql_insert_id($rs);

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

	echo $tnid;
}
///-handler::atn::addtenant]


///+handler::dt2::showtenant]
function showtenant(){
	global $db;
	$tnid=GETVAL('tnid');
	include_once 'icl/persons.inc.php';

	$query="select tenants.*, persons.* from tenants, persons where tenants.personid=persons.personid and tenants.tnid=$tnid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_array($rs)) return;
	$tnid=$myrow['tnid'];
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

<div class="sectiontitle"><?echo $fname.' '.$lname;?></div>

<div style="width:400px;float:left;margin-right:10px;">

<div class="sectionheader">General Info</div>

<table style="margin-bottom:5px;">
<tr>
<td><span id="tn<?echo $tnid;?>_tfname"><?echo $tfname;?></span></td>
<td><input id="tn<?echo $tnid;?>_fname" value="<?echo $fname;?>"></td>
</tr>
<tr>
<td><span id="tn<?echo $tnid;?>_tlname"><?echo $tlname;?></span></td>
<td><input id="tn<?echo $tnid;?>_lname" value="<?echo $lname;?>"></td>
</tr>
<tr>
<td>Address:</td>
<td><input id="tn<?echo $tnid;?>_addr" value="<?echo $addr;?>" style="width:250px;"></td>
</tr>
<tr>
<td>City:</td>
<td><input onfocus="lookupcity(this)" onkeyup="_lookupcity(this);" id="tn<?echo $tnid;?>_city" value="<?echo $city;?>"></td>
</tr>
<tr>
<td>Province:</td>
<td><input onfocus="lookupprov(this)" onkeyup="_lookupprov(this);" id="tn<?echo $tnid;?>_prov" value="<?echo $prov;?>">
<input id="tn<?echo $tnid;?>_country" type="hidden" value="<?echo $country;?>">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input id="tn<?echo $tnid;?>_zip" value="<?echo $zip;?>"></td>
</tr>
<tr><td></td><td>
<button onclick="ajxjs(self.addtenant,'tenants.js');addtenant('<?echo $tnid;?>');">Update</button>
</td></tr>

</table>

<div class="sectionheader">Contact Info</div>
<div id="contactlist_<?echo $personid;?>">
<?
listcontacts($personid);
?>
</div>

</div><!-- left float -->

<div style="width:400px;float:left;">
<div class="sectionheader">Leases</div>
<div id="tenantleases_<?echo $tnid;?>">
<?
listtenantleases($tnid);
?>
</div><!-- tenant list -->
</div><!-- left float -->

</div><!-- margin -->
</div>
<?
}

///-handler::dt2::showtenant]

///+handler::ltnls::listtenantleases]
function listtenantleases($tnid=null){
	global $db;
	if (!isset($tnid)) $tnid=GETVAL('tnid');

	$query="select leases.*,properties.*, persons.fname, persons.lname from properties,leases,landlords,persons,leasetenants where properties.prid=leases.prid and leases.lsid=leasetenants.lsid and leasetenants.tnid=$tnid and properties.llid=landlords.llid and landlords.personid=persons.personid order by leases.c_enddate desc";
	$rs=sql_query($query,$db);
?>
<table>
<?
	while ($myrow=sql_fetch_array($rs)){
		$addr=$myrow['addr'];
		$prname=$addr;
		$prid=$myrow['prid'];
		$llname=$myrow['fname'].' '.$myrow['lname'];
		
		$mrent=$myrow['mrent'];
		$deposit=$myrow['deposit'];
		$ayear=$myrow['ayear'];
		$amon=$myrow['amon'];
		$aday=$myrow['aday'];
		$byear=$myrow['byear'];
		$bmon=$myrow['bmon'];
		$bday=$myrow['bday'];
		$lsid=$myrow['lsid'];
?>
<tr><td>
<span style="cursor:pointer;" onclick="ajxjs(self._lookuptenant,'leases.js');addtab('lease_<?echo $lsid;?>','LS<?echo $lsid?>','dt3&lsid=<?echo $lsid;?>&prid=<?echo $prid;?>');">
#<?echo $lsid;?> <?echo $ayear.'.'.$amon.'.'.$aday;?> - <?echo $byear.'.'.$bmon.'.'.$bday;?>
</span>
</td>
<td>
<?echo $addr;?>
</td>
</tr>
<?
	}//while
?>
</table>
<?
}

///-handler::ltnls::listtenantleases]

///+handler::utn::updatetenant]
function updatetenant(){
	global $db;

	$tnid=GETVAL('tnid');
	$fname=GETSTR('fname');
	$lname=GETSTR('lname');
	$addr=GETSTR('addr');
	$city=GETSTR('city');
	$prov=GETSTR('prov');
	$country=GETSTR('country');
	$zip=GETSTR('zip');
	$zip=str_replace(' ','',$zip);
	$zip=strtoupper($zip);


	//lookup personid
	$query="select personid from tenants where tnid=$tnid";
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

///-handler::utn::updatetenant]

?>