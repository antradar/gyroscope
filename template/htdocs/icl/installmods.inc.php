<?php
set_time_limit(0);
function installmods(){
	die('This feature has been deprecated');
	global $db;
	global $lang;
	global $viewcount;
	global $toolbaritems;
	global $userroles;
	
	$user=userinfo();
		
	if (!isset($user['groups']['upgrademods'])) apperror('You do not have the privilege to install modules');
	
	$modids=GETSTR('modids');

	$vs=explode('.',GYROSCOPE_VERSION);
	for ($i=0;$i<3;$i++) if (!isset($vs[$i])) $vs[$i]=0;
	
	$version=$vs[0]*1000+$vs[1]*100+$vs[2];	

			
		$req=array(
			'version'=>$version,
			'modkey'=>MOD_KEY,
			'userroles'=>$userroles,
			'viewcount'=>$viewcount,
			'toolbaritems'=>$toolbaritems
		);
				
		$url=MOD_SERVER.'?lang='.$lang.'&cmd=installmods&modids='.$modids.'&version='.$version.'&devmode='.$devmode.'&project='.urlencode(GYROSCOPE_PROJECT).'&vendor='.urlencode(VENDOR_NAME).'&vendorversion='.VENDOR_VERSION;
			
		$curl=curl_init($url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($req));
		
		$res=curl_exec($curl);
		curl_close($curl);
		
		$modinfo=json_decode($res,1);
		if (!is_array($modinfo)) die('error downloading modules');
				
		foreach ($modinfo['sqls'] as $sql) sql_query(base64_decode($sql),$db);
				
		foreach ($modinfo['files'] as $file){
			echo "downloaded $file<br>\r\n";
			
			$curl=curl_init(MOD_SERVER.'?cmd=downloadmodfile&fn='.$file);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			$res=curl_exec($curl);
			curl_close($curl);
			
			
			$f=fopen($file,'wb');
			fwrite($f,base64_decode($res));
			fclose($f);	
						
		}
		
		$icons=$modinfo['icons'];
		$switches=$modinfo['switches'];
		$f=fopen('modswitches.php');
		
		$myservices=file_get_contents('myservices.tmpl.php');
		$myservices=str_replace('///switches///',$switches,$myservices);
		$f=fopen('myservices.php','wb');
		fwrite($f,$myservices);
		fclose($f);

		$settings=file_get_contents('settings.tmpl.php');
		$settings=str_replace('///icons///',$icons,$settings);
		$roles="";
		foreach ($modinfo['roles'] as $rkey=>$rval) $roles.="\t'$rkey'=>'$rval',\r\n";
		$settings=str_replace('///userroles///',$roles,$settings);
		
		$f=fopen('settings.php','wb');
		fwrite($f,$settings);
		fclose($f);
		
		
		
?>
<button onclick="skipconfirm();window.location.reload();">Reload Gyroscope</button>
<br><br>
<em>After reload, do a full-refresh to pick up icon changes.</em>
<?php		
				
}