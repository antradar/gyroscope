<?php

include 'icl/showgyroscopeupdater.inc.php';
include 'icl/showguide.inc.php';
include 'icl/listhomedashreports.inc.php';
//include 'icl/listsslcerts.inc.php';

include 'libscal.php';

//include 'gsx.php'; //uncomment this to see gsx in action
//include 'gsx_hello.inc.php'; //uncomment this to see gsx in bypass mode, remember to modify gsx.php

function showwelcome(){
	
makechangebar('welcome',"if (gid('codegen_button')) gid('codegen_button').onclick();");
		
?>
<div style="position:relative;margin-left:60px;"><?php makehelp('welcometab2','maxtab',1);?></div>
<div class="section" style="position:relative;">
	<?php makehelp('welcometab','tabview',1);?>
	<div class="sectiontitle"><a ondblclick="toggletabdock();"><?php tr('hometab_welcome');?></a></div>
	
<?php

//listsslcerts();

/*
?>
SCal Test
<div style="width:40%;margin:0 auto;background_:#ffdeff;">
	<?php scal_makecal('test');?>
</div>
<?php
*/

/*
?>
	Wide View Test: 
	<a class="hovlink" onclick="ajxjs(self.showwideviewdemo,'wideview.js');addtab('wide_1','Wide View 1','showwidedemo&wideid=1',null,null,{wide:true});">wide view 1</a>
	&nbsp;
	<a class="hovlink" onclick="ajxjs(self.showwideviewdemo,'wideview.js');addtab('wide_2','Wide View 2','showwidedemo&wideid=2',null,null,{wide:true});">wide view 2</a>
<?php
*/
	
/*

//auto lookup tests:

	<input class="inp" id="dtest0" onfocus="pickdate(this,{params:'vmode=test&testid=123'});" onkeyup="_pickdate(this,{params:'vmode=test&testid=123'});" placeholder="Date">
	<?php makelookup('dtest0',1);?>
	<input class="inp" id="dtest1" onfocus="pickdate(this,{params:'vmode=heat&recid=123'});" onkeyup="_pickdate(this,{params:'vmode=heat&recid=123'});" placeholder="Date">
	<?php makelookup('dtest1',1);?>

	<input class="inp" onfocus="document.hotspot=this;">
	<textarea onfocus="document.hotspot=this;" class="inplong"></textarea>
	
	<input class="inp" id="mtest" onfocus="pickmonth(this,<?php echo date('Y');?>);" placeholder="Month">
	<?php makelookup('mtest',1);?>
	<br><br>
	<input class="inp" id="dtest" onfocus="pickdate(this);" onkeyup="_pickdate(this);" placeholder="Date">
	<?php makelookup('dtest',1);?>
	<br><br>
	<input class="inp" id="test" onfocus="pickdatetime(this,{start:0,end:24});" onkeyup="_pickdatetime(this,{start:0,end:24});" placeholder="Date/Time">
	<span id="test_val2"></span>
	<?php makelookup('test',1);?>
	<br><br>
	<input class="inp" id="test2" onfocus="picktime(this,{start:0,end:24,y:2015,m:11,d:1});" placeholder="Time on 2015-11-1">
	<span id="test2_val2"></span>
	<?php makelookup('test2',1);?>
*/

/*
//gsx demo:
	
	$lines=array(
		'"Parallel Programming',
		'is the art of deliverying a baby',
		'in just one month',
		'with the help of 10 women."',
	);
	
	$ta=microtime(1);
			
	gsx_begin();
	
	foreach ($lines as $line){	
		//gsx_hello($line,1);
		gsx_exec('gsx_hello',array('msg','delay'),$line,1);
	}//foreach
		
	gsx_end();
	
	$tb=microtime(1);
	echo "<br>Total time: ".round($tb-$ta,2)."s";
*/
?>
<div id="homedashreports">
<?php
		listhomedashreports();
?>
</div>
<?php
		//lazy way to generate a starter screen, but better than nothing
		
		auto_welcome();		
			
		showgyroscopeupdater();
		
		if ($_SERVER['REMOTE_ADDR']==='127.0.0.1'&&($_SERVER['O_IP']==='127.0.0.1'||$_SERVER['O_IP']==='::1')) showguide(); else echo '<div style="padding-bottom:100px;"></div>';
		
	?>			


	
</div><!-- section -->
<?php


}

function auto_welcome(){
	$user=userinfo();
	global $toolbaritems;
	?>
	<div class="section">

	<?php
	foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']=='custom'){
	?>
	<?php echo $ti['desktop'];?>
	<?php	
		continue;
	}
	
	if (!isset($ti['icon'])||$ti['icon']=='') continue;
	
	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;	
	}

	$binmode='null';
	if (isset($ti['bingo'])&&$ti['bingo']==1) $binmode=1;	

	$action="showview('".$modid."',1,1,null,null,".$binmode.");";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];
	
?>	
	<div class="welcometile">
	<a onclick="<?php echo $action;?>"><img alt="<?php echo $ti['title'];?>" style="vertical-align:middle;margin-right:5px;" class="<?php echo $ti['icon'];?>-light" src="imgs/t.gif"> <span style="vertical-align:middle;"><?php echo $ti['title'];?></span></a>
	</div>
	
<?php }//foreach
?>

	
	<div class="clear"></div>	
	
<?php	
/*
if (preg_match('/sm\-r\d+/i',$_SERVER['HTTP_USER_AGENT'])){				
?>
<div class="infobox">
	Courtesy Browsing:<br>
	Many smart watch browsers do not offer an interface to enter arbitrary web addresses.
	Use the following interface instead.
</div>
URL: <input class="inp" id="watch_homeurl" value="https://">
<div class="buttonbelt"><button onclick="window.location.href=gid('watch_homeurl').value;">Go</button></div>
<?php		
}
*/	
?>
	
	<div style="display:none;">
	<textarea class="inp" id="test"
	ttstags="version info,about gyroscope,version information,
	die versionsinformation, die versionsinformation aus,die versionsinformationen, die versionsinformationen aus,
	"><?php tr('powerbanner',array('version'=>GYROSCOPE_VERSION));?></textarea>
	</div>
		
</div>
<?php

		
}


