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
	
	$lines=explode("\n",$code);
	$height=200;
	$lc=count($lines);
	if ($lc<10) $height=$lc*18;
?>
<div><b><?echo $filename;?></b></div>
<div style="margin:5px 10px;">
<textarea style="width:100%;padding:5px;height:<?echo $height;?>px;font-family:monospace;font-size:12px;">
<?echo $code;?>
</textarea>
</div>

<?	
}