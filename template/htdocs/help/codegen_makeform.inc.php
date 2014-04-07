<?php

function codegen_makeform($seed=null){
	global $toolbaritems;
	if (!isset($seed)) $seed=GETSTR('seed');
	
	$fn='help/seeds/'.$seed.'.json';
	if (!file_exists($fn)) {echo "This module is not available in the community edition of Gyroscope";return;}
	
	$c=file_get_contents($fn);
	$obj=json_decode(file_get_contents($fn),1) or die('error parsing form config file');

	$defindex=1;
	foreach ($toolbaritems as $mi){if ($defindex<=$mi['viewindex']) $defindex=$mi['viewindex']+1;}
	
?>
<div style="display:none;"><textarea id="codegen_seedobj"><?echo $c;?></textarea></div>
<table>
<?
	foreach ($obj['fields'] as $fld){
		$field=$fld['field'];
		$disp=$fld['disp'];
		$def=$fld['def'];
		$numeric=$fld['numeric'];
		$type=$fld['type'];
		if ($type=='viewindex') $def=$defindex;
?>
<tr>
	<td class="formlabel" style="text-align:right;"><?echo $disp;?>:</td>
	<td><input id="codegenfield_<?echo $field;?>" class="inp<?if ($numeric) echo 'short';?>"  onclick="select(this);" value="<?echo $def;?>"></td>
</tr>
<?		
	}//foreach	
?>
<tr><td></td><td>
	<button onclick="codegen_makecode('<?echo $seed;?>');">Generate Code!</button>
</td></tr>
</table>

<div id="codegen_codes"></div>
<?
}
