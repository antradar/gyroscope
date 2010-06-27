<?

function updateproperty(){
	global $HTTP_RAW_POST_DATA;
	global $db;
	
	$prid=GETVAL('prid');
	$zip=strtoupper(str_replace(' ','',GETSTR('zip')));
	$addr=GETSTR('addr'); $unit=GETSTR('unit');
	
	$city=GETSTR('city'); $country=GETSTR('country'); $prov=GETSTR('prov');
	$nrooms=GETVAL('nrooms'); $nparking=GETVAL('nparking');
	$desc=str_replace("'","\'",$HTTP_RAW_POST_DATA);
				
	$query="update properties set nparking=$nparking, addr='$addr',unit='$unit',city='$city',prov='$prov' ";
	
	$query.=", country='$country',zip='$zip',nrooms='$nrooms',prdesc='$desc' ";
	$query.=" where prid=$prid";
	
	$rs=sql_query($query,$db);

}
