<?
///+handler::slv3::listleases]
function listleases(){
	global $db;
	
	$key=GETSTR('key');
	$mode=GETSTR('mode');

	if ($mode!='lk'){
?>
<div class="section">

<div style="margin-bottom:6px;">
<input style="background:transparent url(imgs/mg.gif) no-repeat top left;padding-left:13px;" id="keylease" onkeyup="lookuplease();">
<div id="leaselist" style="font-size:12px;">
<?
	}

	$query="select leases.*, properties.prid,persons.* from leases,properties,landlords, persons where ";
	$query.="leases.prid=properties.prid and properties.llid=landlords.llid and landlords.personid=persons.personid";
	if (trim($key)!=''){
		$query.=" and (persons.fname like '$key%' or persons.lname like '$key%' ";
		if (is_numeric($key)) $query.="or lsid=$key ";
		$query.=") "; 
	}
	$query.=" order by leases.c_enddate desc";
	$rs=sql_query($query,$db);

	while ($myrow=sql_fetch_array($rs)){
		$lsid=$myrow['lsid'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
		$prid=$myrow['prid'];
		$aday=$myrow['aday'];
		$amon=$myrow['amon'];
		$ayear=$myrow['ayear'];
		$bday=$myrow['bday'];
		$bmon=$myrow['bmon'];
		$byear=$myrow['byear'];
?>
<div style="cursor:pointer;border-bottom:solid 1px #606060;" onclick="addtab('lease_<?echo $lsid;?>','Lease #<?echo $lsid;?>','dt3&lsid=<?echo $lsid;?>');">
<div>Lease #<?echo $lsid;?></div>
<div><?echo $ayear.'.'.$amon.'.'.$aday;?> - <?echo $byear.'.'.$bmon.'.'.$bday;?></div>
<div><?echo $fname.' '.$lname;?></div>
</div>
<?
	}
	
	if ($mode!='lk'){
?>
</div><!-- leaselist -->
</div>
</div>
&nbsp;
<script>
gid('tooltitle').innerHTML='<a>Leases</a>';
ajxjs(self.addlease,'leases.js');
</script>
<?
	}//mode
}

///-handler::slv3::listleases]

///+handler::nls::newlease]
function newlease(){
	global $db;
	
	$prid=GETVAL('prid');
	$query="select properties.*, persons.fname, persons.lname from properties,landlords,persons where properties.prid=$prid and properties.llid=landlords.llid and landlords.personid=persons.personid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);

	$addr=$myrow['addr'];
	$prname=$addr;
	$llname=$myrow['fname'].' '.$myrow['lname'];

?>
<div class="section">
<div style="margin-bottom:6px;">

<table style="margin-bottom:5px;">
<tr><td>Property:</td>
<td><input readonly id="nls_prname_<?echo $prid;?>" value="<?echo $prname;?>">
<input type="hidden" id="nls_prid_<?echo $prid;?>" value="<?echo $prid;?>"></td>
</tr>
<tr><td>Landlord:</td>
<td><input readonly id="nls_llname_<?echo $prid;?>" value="<?echo $llname;?>">
</tr>
<tr>
<td>Start:</td>
<td>
<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" id="nls_datea_<?echo $prid;?>" style="width:65px;"> <span style="font-size:12px"><em>yyyy-mm-dd</em></span>
</td>
</tr>
<tr>
<td>End:</td>
<td>
<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" id="nls_dateb_<?echo $prid;?>" style="width:65px;"> <span style="font-size:12px"><em>yyyy-mm-dd</em></span>
</td>
</tr>
<tr>
<td>Monthly Rent:</td>
<td><input id="nls_mrent_<?echo $prid;?>" style="width:80px;text-align:right;">
</td>
</tr>
<tr>
<td>Deposit:</td>
<td><input id="nls_deposit_<?echo $prid;?>" style="width:80px;text-align:right;">
</td>
</tr>
</table>

<div style="margin-left:40px;margin-top:10px;">
<button onclick="addlease('<?echo $prid;?>');">Add New</button>
</div><!-- button holder -->

</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

///-handler::nls::newlease]

///+handler::als::addlease]
function addlease(){
	global $db;

	$prid=GETVAL('prid');
	
	$dateas=explode("-",GETSTR('datea'));
	$datebs=explode("-",GETSTR('dateb'));
	
	$ayear=$dateas[0];
	$amon=$dateas[1];
	$aday=$dateas[2];
	
	$byear=$datebs[0];
	$bmon=$datebs[1];
	$bday=$datebs[2];
	
	$c_startdate=mktime(0,0,0,$amon,$aday,$ayear);
	$c_enddate=mktime(23,59,59,$bmon,$bday,$byear);
	
	$mrent=GETVAL('mrent');
	$deposit=GETVAL('deposit');
	
	
	$query="insert into leases(prid,aday,amon,ayear,bday,bmon,byear,mrent,c_startdate,c_enddate,deposit) values (";
	$query.="$prid,'$aday','$amon','$ayear','$bday','$bmon','$byear',$mrent,'$c_startdate','$c_enddate',$deposit)";
	
	$rs=sql_query($query,$db);
	$lsid=sql_insert_id($rs);
	
	echo $lsid;
}
///-handler::als::addlease]

///+handler::dt3::showlease]
function showlease(){
	global $db;

	$lsid=GETVAL('lsid');

	$query="select leases.*,properties.*, landlords.llid,persons.fname, persons.lname from properties,leases,landlords,persons where properties.prid=leases.prid and leases.lsid=$lsid and properties.llid=landlords.llid and landlords.personid=persons.personid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_array($rs);

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

	$datea="$ayear-$amon-$aday";
	$dateb="$byear-$bmon-$bday";

	$llid=$myrow['llid'];
	$zip=$myrow['zip'];

?>

<div class="section">
<div style="margin-bottom:6px;">
<div class="sectiontitle">Lease #<?echo $lsid;?> 
<table style="margin-bottom:5px;">
<tr><td>Property:</td>
<td><a onclick="addtab('property_<?echo $prid;?>','<?echo $addr;?>','dt1&prid=<?echo $prid;?>');" id="ls<?echo $lsid;?>_prname_<?echo $prid;?>"><?echo $prname;?></a>
<input type="hidden" id="ls<?echo $lsid;?>_prid_<?echo $prid;?>" value="<?echo $prid;?>"></td>
</tr>
<tr><td>Landlord:</td>
<td><a onclick="addtab('landlord_<?echo $llid;?>','<?echo $llname;?>','dt0&llid=<?echo $llid;?>');" id="ls<?echo $lsid;?>_llname_<?echo $prid;?>"><?echo $llname;?></a>
</tr>
<tr>
<td>Start:</td>
<td>
<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" id="ls<?echo $lsid;?>_datea_<?echo $prid;?>" value="<?echo $datea;?>" style="width:65px;"> <span style="font-size:12px;"><em>yyyy-mm-dd</em></span>
</td>
</tr>
<tr>
<td>End:</td>
<td>
<input onfocus="pickdate(this);" onkeyup="_pickdate(this);" id="ls<?echo $lsid;?>_dateb_<?echo $prid;?>" value="<?echo $dateb;?>" style="width:65px;"> <span style="font-size:12px;"><em>yyyy-mm-dd</em></span>
</td>
</tr>
<tr>
<td>Monthly Rent:</td>
<td><input id="ls<?echo $lsid;?>_mrent_<?echo $prid;?>" value="<?echo $mrent;?>" style="width:80px;text-align:right;">
</td>
</tr>
<tr>
<td>Deposit:</td>
<td><input id="ls<?echo $lsid;?>_deposit_<?echo $prid;?>" value="<?echo $deposit;?>" style="width:80px;text-align:right;">
</td>
</tr>
</table>

<div style="margin-left:40px;margin-top:10px;">
<button onclick="addlease('<?echo $prid;?>','<?echo $lsid;?>');">Update</button>
</div><!-- button holder -->

<div class="sectionheader">Tenants</div>
<div id="ls<?echo $lsid;?>_tenantlist_<?echo $prid;?>" style="margin-bottom:5px;">
<?
	listleasetenants($lsid,$prid);
?>
</div><!-- tenant list -->

</div><!-- margin-bottom -->
</div><!-- section -->
<?
}

///-handler::dt3::showlease]

///+handler::llstn::listleasetenants]
function listleasetenants($lsid=null,$prid=null){
	global $db;
	if (!isset($lsid)) {
		$lsid=GETVAL('lsid');
		$prid=GETVAL('prid');
	}

	$query="select tenants.*, persons.fname, persons.lname, leasetenants.* from tenants,persons,leasetenants where leasetenants.lsid=$lsid and leasetenants.tnid=tenants.tnid and tenants.personid=persons.personid";
	$rs=sql_query($query,$db);
?>
<table>
<?
	while ($myrow=sql_fetch_array($rs)){
		$tnid=$myrow['tnid'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
		$tnname=$fname.' '.$lname;
		$lstnid=$myrow['lstnid'];
?>
<tr>
<td>
<span style="cursor:pointer;" tnid="<?echo $tnid;?>" onclick="addtab('tenant_<?echo $tnid;?>','<?echo $fname;?>','dt2&tnid=<?echo $tnid;?>');"><?echo $tnname;?></span>
</td>
<td>
<nobr>
&nbsp;&nbsp;
<a onclick="deltenantfromlease(<?echo $lstnid;?>,<?echo $lsid;?>,<?echo $prid;?>);">[x]</a>
</nobr>
</td>
</tr>
<?
}
?>
</table>
<table style="margin-left:10px;margin-top:10px;">
<tr><td>Tenant Name:</td>
<td><input style="margin-top:5px;" id="ls<?echo $lsid;?>_newitem_<?echo $prid?>" onfocus="document.hotspot=this;" onkeyup="_lookuptenant(this);">
</td>
</tr>
<tr><td></td><td><button onclick="addtenanttolease('<?echo $lsid;?>','<?echo $prid;?>');">Add Tenant to Lease</button>
</td></tr>
</table>
<?
}

///-handler::llstn::listleasetenants]

///+handler::alstn::addleasetenant]
function addleasetenant(){
	global $db;
	
	$lsid=GETVAL('lsid');
	$tnid=GETVAL('tnid');
	
	$query="insert into leasetenants(tnid,lsid) values ($tnid,$lsid);";
	sql_query($query,$db);
}

///-handler::alstn::addleasetenant]

///+handler::dlstn::delleasetenant]
function delleasetenant(){
	global $db;
	
	$lstnid=GETVAL('lstnid');
	
	$query="delete from leasetenants where lstnid=$lstnid";
	sql_query($query,$db);
}

///-handler::dlstn::delleasetenant]


///+handler::uls::updatelease]
function updatelease(){
	global $db;
	
	$prid=GETVAL('prid');
	$lsid=GETVAL('lsid');
	
	$dateas=explode("-",GETSTR('datea'));
	$datebs=explode("-",GETSTR('dateb'));
	
	$ayear=$dateas[0];
	$amon=$dateas[1];
	$aday=$dateas[2];
	
	$byear=$datebs[0];
	$bmon=$datebs[1];
	$bday=$datebs[2];
	
	
	$c_startdate=mktime(0,0,0,$amon,$aday,$ayear);
	$nm=$bmon+1;
	$ny=$byear;
	
	if ($nm>12) {$nm=$nm-12;$ny++;}
	$c_enddate=mktime(23,59,59,$nm,0,$ny); //use the last day of month for scheduling
	
	$mrent=GETVAL('mrent');
	$deposit=GETVAL('deposit');
	
	$query="update leases set aday='$aday',amon='$amon',ayear='$ayear',bday='$bday', ";
	$query.="bmon='$bmon',byear='$byear',mrent=$mrent,c_startdate='$c_startdate',c_enddate='$c_enddate',deposit=$deposit ";
	$query.=" where lsid=$lsid";
	
	sql_query($query,$db);
}

///-handler::uls::updatelease]

?>