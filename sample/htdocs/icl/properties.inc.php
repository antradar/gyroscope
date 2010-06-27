<?
function listproperties(){
	global $db;
	global $HTTP_GET_VARS;
	
	$key=GETSTR('key');
	$mode=GETSTR('mode');
	if ($mode!='lk'){
?>
<div class="section">
<div style="margin-bottom:6px;">

<div>
<input style="background:transparent url(imgs/mg.gif) no-repeat top left;padding-left:13px;" id="keyproperty" onkeyup="_property_lookupproperty();">
</div>
<div id="propertylist" style="font-size:12px;">
<?
	}
	
	$query="select properties.*, properties.city as pcity, properties.zip as pzip,properties.addr as paddr, landlords.*, persons.* from properties,landlords,persons where properties.llid=landlords.llid and landlords.personid=persons.personid ";
	if (trim($key)!='') $query.=" and properties.addr like '%$key%' ";
	$query.=" order by persons.fname";
	$rs=sql_query($query,$db);

	while ($myrow=sql_fetch_array($rs)){
		$prid=$myrow['prid'];
		$addr=$myrow['paddr'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
		$llid=$myrow['llid'];
		$zip=$myrow['pzip'];
		$unit=$myrow['unit'];
		$city=$myrow['pcity'];
		if ($unit!=''){
			if (is_numeric($unit)||strlen($unit)<4) $addr.=" Unit $unit";
			else $addr.=" $unit";
		}
?>
<div style="position:relative;border-bottom:solid 1px #606060;margin-bottom:2px;">
<a onclick="ajxjs(self.addlease,'leases.js');addtab('property_<?echo $prid;?>','<?echo $addr;?>','dt1&prid=<?echo $prid;?>');"><?echo "$addr, $city";?></a>
</div>
<?
	}
	
	if ($mode!='lk'){
?>
</div><!-- property -->
</div>
</div>
&nbsp;
<script>
gid('tooltitle').innerHTML='<a>Properties</a>';
ajxjs(self.addproperty,'properties.js');
ajxjs(self.addlease,'leases.js');
</script>
<?
	}//mode
}

function showproperty(){
	global $HTTP_GET_VARS;
	global $db;
	
	$prid=$HTTP_GET_VARS['prid'];
	$query="select properties.*,landlords.llid,persons.fname,persons.lname from properties,landlords,persons where properties.llid=landlords.llid and properties.prid=$prid and persons.personid=landlords.personid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);
	$llname=$myrow['fname'];
	$llid=$myrow['llid'];
	$addr=$myrow['addr'];
	$city=$myrow['city'];
	$prov=$myrow['prov'];
	$country=$myrow['country'];
	$zip=$myrow['zip'];
	$nrooms=$myrow['nrooms'];
	$nparking=$myrow['nparking'];
	$prdesc=$myrow['prdesc'];
	$unit=$myrow['unit'];
	
	$user=userinfo();
	$admin=$user['groups']['admins'];

?>
<div class="section">
<div style="margin-bottom:6px;">
<div style="width:400px;float:left;margin-right:10px;">
<div class="sectionheader">General Info</div>

<table style="margin-bottom:5px;">
<?if ($admin){?>
<tr><td>Landlord:</td>
<td><a onclick="addtab('landlord_<?echo $llid;?>','<?echo $llname;?>','dt0&llid=<?echo $llid;?>');"id="pr<?echo $prid;?>_llname_<?echo $llid;?>"><?echo $llname;?></a>
<input type="hidden" id="pr<?echo $prid;?>_llid_<?echo $llid;?>" value="<?echo $llid;?>"></td></tr>
<?}?>
<tr>
<td>Address:</td>
<td>
<input <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_addr_<?echo $llid;?>" value="<?echo $addr;?>"> *
</td>
</tr>
<tr>
<td>Unit</td>
<td>
<input <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_unit_<?echo $llid;?>" value="<?echo $unit;?>">
</td>
</tr>
<tr>
<td>City:</td>
<td><input onkeyup="_lookupcity(this);" onfocus="lookupcity(this);" <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_city_<?echo $llid;?>" value="<?echo $city;?>"> *</td>
</tr>
<tr>
<td>Province:</td>
<td><input onkeyup="_lookupprov(this);" onfocus="lookupprov(this);" <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_prov_<?echo $llid;?>" value="<?echo $prov;?>"> *
<input id="pr<?echo $prid;?>_country_<?echo $llid;?>" type="hidden" value="<?echo $country?>">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_zip_<?echo $llid;?>" value="<?echo $zip;?>"> *</td>
</tr>
</table>
<table style="margin-bottom:5px;">
<tr>
<td># Rooms:</td>
<td><input <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_nrooms_<?echo $llid;?>" value="<?echo $nrooms;?>"></td>
</tr>
<tr>
<td># Parking:</td>
<td><input <?if (!$admin) echo 'style="border:none;" readonly '?> id="pr<?echo $prid;?>_nparking_<?echo $llid;?>" value="<?echo $nparking;?>"></td>
</tr>
<?if ($admin){?>
<tr>
<td valign="top">Description:</td>
<td><textarea id="pr<?echo $prid;?>_desc_<?echo $llid;?>" style="width:100%;height:100px; border:solid 1px #666666;"><?echo $prdesc;?></textarea></td>
</tr>
<tr><td colspan="2"><span id="pr<?echo $prid;?>_idcgeo_<?echo $llid;?>" style="display:none;">resolving geocode...</span></td></tr>
<tr><td colspan="2" align="center">
<button onclick="ajxjs(self.addproperty,'properties.js');addproperty('<?echo $llid;?>','<?echo $prid;?>');">Update</button>
</td></tr>
<?}?>
</table>
</div><!-- float -->
<div style="width:400px;float:left;margin-right:10px;">
<?if ($admin){?>
<div class="sectionheader">Leases</div>
<div id="propertyleases_<?echo $prid;?>">
<?listpropertyleases($prid);?>
</div><!-- propertyleases -->
<?}?>

</div><!-- float -->
</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

function newproperty(){
	global $db;
	$llid=GETVAL('llid');
	$query="select persons.* from landlords,persons where landlords.llid=$llid and persons.personid=landlords.personid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);
	$llname=$myrow['fname'].' '.$myrow['lname'];
?>
<div class="section">
<div style="margin-bottom:6px;">

<table style="margin-bottom:5px;">
<tr><td>Landlord:</td>
<td><input readonly id="npr_llname_<?echo $llid;?>" value="<?echo $llname;?>">
<input type="hidden" id="npr_llid_<?echo $llid;?>" value="<?echo $llid;?>"></td></tr>
<tr>
<td>Address:</td>
<td><input id="npr_addr_<?echo $llid;?>"> *</td>
</tr>
<tr>
<td>Unit:</td>
<td><input id="npr_unit_<?echo $llid;?>"></td>
</tr>
<tr>
<td>City:</td>
<td><input onkeyup="_lookupcity(this);" onfocus="lookupcity(this);" id="npr_city_<?echo $llid;?>"> *</td>
</tr>
<tr>
<td>Province:</td>
<td><input onkeyup="_lookupprov(this);" onfocus="lookupprov(this);" id="npr_prov_<?echo $llid;?>" value="ON"> *
<input id="npr_country_<?echo $llid;?>" type="hidden" value="Canada">
</td>
</tr>
<tr>
<td>Postal:</td>
<td><input id="npr_zip_<?echo $llid;?>"> *</td>
</tr>
</table>
<table style="margin-bottom:5px;">
<tr>
<td># Rooms:</td>
<td><input id="npr_nrooms_<?echo $llid;?>" value="0"></td>
</tr>
<tr>
<td># Parking:</td>
<td><input id="npr_nparking_<?echo $llid;?>" value="0"></td>
</tr>
<tr>
<td valign="top">Description:</td>
<td><textarea id="npr_desc_<?echo $llid;?>" style="border:solid 1px;"></textarea></td>
</tr>
<tr><td colspan="2" align="center"><em>* required fields</em></td></tr>
<tr><td colspan="2" align="center">&nbsp</td></tr>
<tr><td colspan="2"><span id="npr_idcgeo_<?echo $llid;?>" style="display:none;">resolving geocode...</span></td></tr>
<tr><td colspan="2" align="center">
<button onclick="addproperty('<?echo $llid;?>');">Add New</button>
</td></tr>
</table>

</div><!-- margin-bottom -->
</div><!-- section -->
<?
}

function addproperty(){
	global $HTTP_RAW_POST_DATA;
	global $db;
	
	$zip=GETSTR('zip');
	$zip=str_replace(' ','',$zip);
	$zip=strtoupper($zip);
	$addr=GETSTR('addr');
	$unit=GETSTR('unit');
	
	$city=GETSTR('city');
	$country=GETSTR('country');
	$prov=GETSTR('prov');
	$nrooms=GETVAL('nrooms');
	$nparking=GETVAL('nparking');
	$desc=str_replace("'","\'",$HTTP_RAW_POST_DATA);
	$llid=GETVAL('llid');
	
	$query="insert into properties(llid,addr,unit,city,prov,country,zip,nrooms,prdesc,nparking) values (";
	$query.="$llid,'$addr','$unit','$city','$prov','$country','$zip',$nrooms,'$desc',$nparking)";
	
	$rs=sql_query($query,$db);
	
	$prid=sql_insert_id($rs);
	
	echo $prid;
}

function listpropertyleases($prid=null){
	global $db;
	
	if ($prid==null) $prid=GETVAL('prid');
	
	$query="select leases.*,persons.fname,persons.lname from leases, properties,landlords,persons where ";
	$query.=" leases.prid=properties.prid and properties.llid=landlords.llid and landlords.personid=persons.personid and ";
	$query.=" leases.prid=$prid order by leases.c_enddate desc";
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$lsid=$myrow['lsid'];
		$prid=$myrow['prid'];
		$aday=$myrow['aday'];
		$amon=$myrow['amon'];
		$ayear=$myrow['ayear'];
		$bday=$myrow['bday'];
		$bmon=$myrow['bmon'];
		$byear=$myrow['byear'];
		$llname=$myrow['fname'].' '.$myrow['lname'];
?>
<div><a onclick="addtab('lease_<?echo $lsid;?>','LS<?echo $lsid;?>','dt3&lsid=<?echo $lsid;?>');">
#<?echo $lsid;?> &nbsp; <?echo $ayear.'.'.$amon.'.'.$aday;?> - <?echo $byear.'.'.$bmon.'.'.$bday;?></a> 
<?  
	}
?>
<div style="margin-top:5px;">
<a onclick="ajxjs(self.addlease,'leases.js');addtab('new_lease_<?echo $prid;?>','New Lease','nls&prid=<?echo $prid;?>');" 
onmouseover="hintstatus('Add Lease',this);">
<img src="imgs/addlease.gif">
</a>
</div>
<?
}


?>