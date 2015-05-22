<?php
include 'help/seeds/seeds.php';

function codegen_makeform($seed=null){
	global $toolbaritems;
	if (!isset($seed)) $seed=GETSTR('seed');
	global $codegen_seeds;
	
	$seedname=$codegen_seeds[$seed]['name'];
	$desc=$codegen_seeds[$seed]['desc'];
	$icon=$codegen_seeds[$seed]['icon'];
		
			
	$fn='help/seeds/'.$seed.'.json';
	if (!file_exists($fn)) {echo "This module is not available in the community edition of Gyroscope";return;}
	
?>
<div class="sectiontitle" <?if ($desc!='') echo 'style="margin-bottom:10px;"';?>><?echo $seedname;?></div>
<?
if ($desc!=''){
?>
<div style="color:#444444;padding-bottom:20px; echo 'background:transparent url() no-repeat 0 0;';?>">
<?if ($icon!=''){?>
	<img src="help/seeds/icons/<?echo $icon;?>.png" style="float:left;margin-right:15px;">
<?}?>
	<div style="float:left;padding-top:5px;"><?echo $desc;?></div>
	<div class="clear"></div>
</div>
<?	
}//desc
	$c=file_get_contents($fn);
	$obj=json_decode(file_get_contents($fn),1) or die('error parsing form config file');

	$defindex=2;
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
		$tag='input'; $ctag='';
		if ($type=='fieldlist') {$tag='textarea';$ctag=$def.'</textarea>';}
?>
<tr>
	<td class="formlabel" style="text-align:right;" valign="top"><?echo $disp;?>:</td>
	<td>
		<<?echo $tag;?> id="codegenfield_<?echo $field;?>" class="inp<?if ($numeric) echo 'short';?> onclick="select(this);" value="<?echo $def;?>"><?echo $ctag;?>
	</td>
</tr>
<?		
	}//foreach	
?>
<tr><td></td><td>
	<button onclick="codegen_makecode('<?echo $seed;?>');">Generate Code!</button>
</td></tr>
</table>

<div id="codegen_codes" style="padding-top:20px;"></div>
<?
}
