<?php

function codegen_makecode(){
	$seed=GETSTR('seed');
	
	$fn='help/seeds/'.$seed.'.json';
	if (!file_exists($fn)) {echo "missing form file $seed.json";return;}	
	
	$c=file_get_contents($fn);
	$obj=json_decode(file_get_contents($fn),1) or die('error parsing form config file');
	
	$opts=$_POST;
	
	$templates=$obj['templates'];
	
	foreach ($templates as $template){
		$fseed=$template['template'];
		$filename=$template['filename'];
		foreach ($opts as $k=>$v) $filename=str_replace("#$k#",$v,$filename);	
	
		codegen_quotecode($fseed,$filename,$opts);
			
	}
}

function codegen_quotecode($seed,$filename,$opts){
	$fn='help/seeds/'.$seed.'.seed';
	if (!file_exists($fn)) {echo "missing seed file $seed.seed";return;}
	$code=file_get_contents($fn);
	$code=htmlentities($code);
	
	foreach ($opts as $k=>$v){
		$code=str_replace("#$k#",$v,$code);	
	}

	$code=preg_replace_callback('/#iterator-(\S+?)-(\S+?)#/',function($matches) use ($opts){
		$listname=$matches[1];
		$fieldlist=trim($opts[$listname]);
		$fields=explode("\n",$fieldlist);
		$tmplfn=$matches[2];		
		
		$tmpl='help/seeds/'.$tmplfn.'.itr';
		
		
		if (!file_exists($tmpl)) {echo '<div style="padding:10px 0;color:#ab0200;">missing iterator '.$matches[2].'.itr</div>';return '';}
		$bc=file_get_contents($tmpl);
		$str='';
		$fc=count($fields);

		foreach ($fields as $fidx=>$field){
			if (trim($field)=='') continue;
			$c=$bc;
			foreach ($opts as $k=>$v) $c=str_replace("#$k#",$v,$c);
			$parts=explode('|',$field);
			foreach ($parts as $idx=>$part) {
				$c=str_replace("#fld$idx#",$part,$c);
			}
			
			if ($fidx<$fc-1) $c=preg_replace('/#delim([\S\s]+?)#/','${1}',$c);
			else $c=preg_replace('/#delim([\S\s]+?)#/','',$c);

			$str.=$c;
		}
		
		return $str;
	},$code);
	
	$lines=explode("\n",$code);
	$height=200;
	$lc=count($lines);
	if ($lc<10) $height=$lc*18;
?>
<div><input type="checkbox"> <b><?echo $filename;?></b></div>
<div style="margin:5px 10px;">
<textarea style="width:100%;padding:5px;height:<?echo $height;?>px;font-family:monospace;font-size:12px;">
<?echo $code;?>

</textarea>
</div>

<?	
}