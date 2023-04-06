<?php

function codegen_makecode(){
	$seed=SGET('seed');
	$seed=preg_replace('/[^A-Za-z0-9-_.]/','',$seed);
	
	$fn='help/seeds/'.$seed.'.json';
	if (!file_exists($fn)) {echo "missing form file $seed.json";return;}	
	
	$c=file_get_contents($fn);
	$obj=json_decode(file_get_contents($fn),1) or die('error parsing form config file');
	
	$opts=$_POST;
	
	$templates=$obj['templates'];

	foreach ($templates as $idx=>$template){
		$fseed=$template['template'];
		$filename=$template['filename'];
		$nocopy=isset($template['nocopy'])?intval($template['nocopy']):0;
		
		if (isset($template['show-when'])){
			$condparts=explode('=',$template['show-when']);
			if ($opts[$condparts[0]]!=$condparts[1]) continue;
		}
		
		foreach ($opts as $k=>$v) $filename=str_replace("#$k#",$v,$filename);	
	
		codegen_quotecode($fseed,$filename,$opts,$idx,$nocopy);
			
	}
}

function codegen_quotecode($seed,$filename,$opts,$midx,$nocopy,$subcall=0){

	$fn='help/seeds/'.$seed.'.seed';
	if (!file_exists($fn)) {echo "missing seed file $seed.seed<br>";return;}
	$code=file_get_contents($fn);
	$code=htmlentities($code);
	
	foreach ($opts as $k=>$v){
		$code=str_replace("#$k#",htmlspecialchars($v),$code);	
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
			foreach ($opts as $k=>$v) $c=str_replace("#$k#",htmlspecialchars($v),$c);
			$parts=explode('|',$field);
			foreach ($parts as $idx=>$part) {
				$c=str_replace("#fld$idx#",htmlspecialchars($part),$c);
			}
			
			if ($fidx<$fc-1) $c=preg_replace('/#delim([\S\s]+?)#/','${1}',$c);
			else $c=preg_replace('/#delim([\S\s]+?)#/','',$c);

			$str.=$c;
		}
		
		return $str;
	},$code);
	
	$code=preg_replace_callback('/#include-(\S+?)-when-(\S+?)-is-(\S+?)#/',function($matches) use ($opts){
		$var=$opts[$matches[2]]??'';
		$val=$matches[3];
		
		if ($var==$val) return '#include-'.$matches[1].'#';
		
	},$code);	
	
	$code=preg_replace_callback('/#include-([\S]+?)#/',function($matches) use ($opts){
		$subseed=str_replace(array('.','/'),'',$matches[1]);
		$c=codegen_quotecode($subseed,'',$opts,-1,1,1);
		return $c;
	},$code);
	
	if ($subcall) return $code;
		
	$lines=explode("\n",$code);
	$height=200;
	$lc=count($lines);
	if ($lc<10) $height=$lc*18;
?>
<div><input type="checkbox"> <b><?php echo $filename;?></b> 
&nbsp; 
<?php if (!$nocopy){?>
<a class="labelbutton" onclick="codegen_copy(<?php echo $midx;?>)">copy</a>
<?php } else {
?>
<span class="labelbutton" style="cursor:default;background:#cccccc;">copy</span>
<?php	
}?>

</div>

<div style="margin:5px 10px;">
<textarea spellcheck="false" id="codegensnippet_<?php echo $midx;?>" style="width:100%;padding:5px;height:<?php echo $height;?>px;font-family:monospace;font-size:12px;">
<?php echo $code;?>

</textarea>
</div>

<?php	
}