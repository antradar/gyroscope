<? 
include 'settings.php';

include 'connect.php';
include 'auth.php';
include 'xss.php';

xsscheck();
login(true); //silent mode

$cmd=$_GET['cmd'];

function GETVAL($key){ $val=$_GET[$key]; if (!is_numeric($val)) die('invalid parameter'); return $val;}
function GETSTR($key){ $val=decode_unicode_url($_GET[$key]); $val=str_replace("\'","'",$val); $val=str_replace("'","\'",$val); return $val; }

function decode_unicode_url($str){
	$res = '';
	
	$i = 0; $max=strlen($str)-6;
	
	while ($i<=$max){
		$c=$str[$i];
		if ($c=='%'&&$str[$i + 1]=='u'){
			$v=hexdec(substr($str,$i+2,4));
			$i+=6;
			
			if ($v<0x0080) $c=chr($v); //1 byte
			else if ($v<0x0800) $c=chr((($v&0x07c0)>>6)|0xc0).chr(($v&0x3f)|0x80); // 2 bytes: 110xxxxx 10xxxxxx
			else $c=chr((($v&0xf000)>>12)|0xe0).chr((($v&0x0fc0)>>6)|0x80).chr(($v&0x3f)|0x80); // 3 bytes: 1110xxxx 10xxxxxx 10xxxxxx
		} else $i++;
		
		$res.=$c;
	}//while
	
	return $res . substr($str, $i);
}


//load only required modules

switch($cmd){
	case 'slv0': case 'dt0': case 'nl': case 'al': case 'ul': case 'llpr': include 'icl/landlords.inc.php'; break;
	case 'slv1': case 'dt1': case 'npr': case 'apr': case 'prls': include 'icl/properties.inc.php'; break;
	case 'upr': include 'icl/updateproperty.inc.php'; break;
	case 'slv2': case 'ntn': case 'atn': case 'dt2': case 'ltnls': case 'utn': include 'icl/tenants.inc.php'; break;
	case 'slv3': case 'dt3': case 'nls': case 'als': case 'uls': case 'llstn': case 'alstn': case 'dlstn': case 'lstnfg': include 'icl/leases.inc.php'; break;	  
	case 'lc': case 'act': case 'dct': case 'lid': case 'aid': case 'did': include 'icl/persons.inc.php'; break;
	case 'lktn': case 'pkd': case 'showday': case 'aplkpr': case 'lkcity': case 'lkprov': include 'icl/lookup.inc.php'; break;
	case 'pump': include 'icl/utils.inc.php'; break;
}

switch ($cmd){

//landlords
	case 'slv0': listlandlords(); break;
	case 'dt0': showlandlord(); break;
	case 'nl': newlandlord(); break;
	case 'al': addlandlord(); break;
	case 'ul': updatelandlord(); break;
	case 'llpr': listlandlordproperties(); break;

//properties
	case 'slv1': listproperties(); break;
	case 'dt1': showproperty(); break;
	case 'npr': newproperty(); break;
	case 'apr': addproperty(); break;
	case 'prls': listpropertyleases(); break;

//tenants
	case 'slv2': listtenants(); break;
	case 'ntn': newtenant(); break;
	case 'atn': addtenant(); break;
	case 'dt2': showtenant(); break;
	case 'ltnls': listtenantleases(); break;


//persons
	case 'lc': listcontacts(); break;
	case 'act': addcontact(); break;
	case 'dct': deletecontact(); break;
	case 'lid': listids(); break;
	case 'aid': addid(); break;
	case 'did': deleteid(); break;

//leases
	case 'slv3': listleases(); break;
	case 'nls': newlease(); break;
	case 'als': addlease(); break;
	case 'dt3': showlease(); break;
	case 'llstn': listleasetenants(); break;
	case 'alstn': addleasetenant(); break;
	case 'dlstn': delleasetenant(); break;
	case 'lstnfg': setleasetenantflag(); break;

	case 'wk': showwelcome(); break;

//lookups
	case 'pkd': showdatepicker(); break;
	case 'showday': showday(); break;
	case 'aplkpr': aplookupproperty(); break;
	case 'lkcity': lookupcity(); break;
	case 'lkprov': lookupprovince(); break;
	case 'lktn': lookuptenant(); break;
  
	case 'uls': updatelease(); break;
	case 'utn': updatetenant(); break;
	case 'upr': updateproperty(); break;

	case 'pump': authpump(); break;

	default: echo 'unspecified interface:'.$cmd;
}

function showwelcome(){
global $db;
?>
<div class="section">
<div class="sectiontitle">Quick Start</div>

<div>
<div class="sectionheader">Leasing</div>
<div style="padding-left:10px;">
<div><a onclick="showview(0);"><img src="imgs/bll.gif"> Landlords</a></div>
<div><a onclick="showview(2);"><img src="imgs/btn.gif"> Tenants</a></div>
<div><a onclick="showview(1);"><img src="imgs/bpr.gif"> Properties</a></div>
<div><a onclick="showview(3);"><img src="imgs/bls.gif"> Leases</a></div>
</div><!-- padding -->
</div><!-- float -->

<div style="clear:left;padding-top:20px;">
<div class="sectiontitle">Help</div>
<div class="sectionheader">Tutorial Videos</div>
<ul>
<li>Getting around</li>
<li>Booking appointments</li>
<li>Tracking expenses</li>
<li>Generating print materials</li>
<li>Managing payments</li>
</ul>

<div class="sectionheader">Licenses</div>
<ul>
<li>End-user License Agreement</li>
<li>Antradar Servicing License</li>
<li>Macronetic&trade; Hosted Edition</li>

</ul>
<div style="font-size:11px;">
&copy; Antradar Software Inc., All Rights Reserved
</div>
</div>

</div><!-- section -->
<?
}
