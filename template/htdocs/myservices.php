<?
include 'settings.php';
include 'adodb.php';


include 'connect.php';
include 'auth.php';
include 'xss.php';

xsscheck();	
login(true); //silent mode

$cmd=$_GET['cmd'];

function GETVAL($key){
  $val=$_GET[$key];
  if (!is_numeric($val)) die('invalid parameter');
  return $val;
}

function GETSTR($key){
	$val=decode_unicode_url($_GET[$key]);
	$val=str_replace("\'","'",$val);
	$val=str_replace("'","\'",$val);
	return $val;
}

function decode_unicode_url($str){
  $res = '';

  $i = 0;
  $max = strlen($str) - 6;
  while ($i <= $max)
  {
    $character = $str[$i];
    if ($character == '%' && $str[$i + 1] == 'u')
    {
      $value = hexdec(substr($str, $i + 2, 4));
      $i += 6;

      if ($value < 0x0080) // 1 byte: 0xxxxxxx
        $character = chr($value);
      else if ($value < 0x0800) // 2 bytes: 110xxxxx 10xxxxxx
        $character =
            chr((($value & 0x07c0) >> 6) | 0xc0)
          . chr(($value & 0x3f) | 0x80);
      else // 3 bytes: 1110xxxx 10xxxxxx 10xxxxxx
        $character =
            chr((($value & 0xf000) >> 12) | 0xe0)
          . chr((($value & 0x0fc0) >> 6) | 0x80)
          . chr(($value & 0x3f) | 0x80);
    }
    else
      $i++;

    $res .= $character;
  }

  return $res . substr($str, $i);
}


/*
	include additional modules here
*/	

include 'icl/utils.inc.php'; 
include 'icl/module1.inc.php';
include 'icl/module2.inc.php';
include 'icl/lookup.inc.php';


switch ($cmd){

//Entity 1
  case 'slv0': showlist1(); break;
  case 'dt0': showdetails1(); break;

//Entity 2
  case 'slv1': showlist2(); break;
  case 'dt1': showdetails2(); break;

  case 'pkd': showdatepicker(); break; //lookup
  case 'pump': authpump(); break; //comment this out to disable authentication
  case 'wk': showwelcome(); break;
  
  case 'echo': echo_message(); break;
  
  default: echo 'unspecified interface:'.$cmd;
}

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle">Welcome to Antradar Gyroscope</div>
	
	<div>
	<p>
	Before you explore the code, play with this application a bit.<br>
	There are two "Entities", or record types presented by this application.<br>
	Their relations are captured by the following table:
	</p>
	<table border="1" cellpadding="2" cellspacing="2">
	<tr><th>Entity 1</th><th>Entity 2</th></tr>
	<tr><td>0</td><td>1</td></tr>
	<tr><td>0</td><td>2</td></tr>
	<tr><td>1</td><td>0</td></tr>
	<tr><td>1</td><td>1</td></tr>
	<tr><td>1</td><td>2</td></tr>
	<tr><td>1</td><td>3</td></tr>
	<tr><td>1</td><td>5</td></tr>
	<tr><td>1</td><td>7</td></tr>	
	</table>
	<p>
	Now with Gyroscope View, you can view each record in its entirity and easily shift the locus to another record.
	</p>
	<ul>
		<li>Click on the [1] and [2] icons to see a list of each entity</li>
		<li>Click on any item in the list to see its details</li>
		<li>Each item also lists its related items, try navigating from there</li>
		<li>The content of one tab can directly interact with another tab, or a part of the tab. Discover how.</li>
	</ul>
	</div>
	<p>
	Now a quick taste of the development side:
	</p>
	<div style="margin-bottom:10px;">
	<div>Using Tabs</div>
		<div>
		addtab(<em>unique_key</em>,<em>tab_title</em>,<em>parameters</em>[,<em>call_back</em>]);
		</div>
	<div>
		<div>Examples:</div>
		<div class="example">
			<div>addtab('test1','Test One','echo&msg=Test1'); <a onclick="addtab('test1','Test One','echo&msg=Test1');">[try me]</a></div>
			<div>addtab('test2','Test Two','echo&msg=Test2',function(){alert('Test One Loaded');}); <a onclick="addtab('test2','Test Two','echo&msg=Test2',function(){alert('Test Tab Loaded');});">[try me]</a></div>
		</div>
	</div>
	</div>

	<div style="margin-bottom:10px;">
	<div>Self-referencing Lookup Controls</div>
		<div class="example">
		&lt;input onfocus="pickdate(<em>this</em>);" onkeyup="_pickdate(<em>this</em>);"&gt;
		</div>
	<div>
		<div>Try it!</div>
		<div><input onfocus="pickdate(this);" onkeyup="_pickdate(this);"></div>
	</div>
	</div>

		
</div><!-- section -->
<?
}

function echo_message(){
	$msg=GETSTR('msg');
?>	
<div class="section">
	<h2><?echo $msg;?></h2>	
</div>	
<?
}

die();
?>
