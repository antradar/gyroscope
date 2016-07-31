<?php
include 'help/seeds/seeds.php';

function codegen_makeform($seed=null){
	global $toolbaritems;
	if (!isset($seed)) $seed=GETSTR('seed');
	global $codegen_seeds;
	
	$seedobj=null;
	
	foreach ($codegen_seeds as $k=>$v){
		if ($seed==$k) {
			$seedobj=$v;
			break;	
		}
		if ($v['package']){
			foreach ($v['items'] as $ik=>$iv){
				if ($seed==$ik) $seedobj=$iv;	
			}	
		}
		if ($seedobj) break;
	}
	
	
	$seedname=$seedobj['name'];
	$desc=$seedobj['desc'];
	$icon=$seedobj['icon'];
	$package=$seedobj['package'];	
	
	if ($package){
		
		$items=$codegen_seeds[$seed]['items'];
	?>
	This code generator supports a few variants:
	<div style="padding-top:10px;padding-bottom:20px;">
	<?	
		foreach ($items as $itemkey=>$item){
	?>
	<div class="listitem">
		<a onclick="codegen_makeform('<?echo $itemkey;?>');"><?echo $item['name'];?><br><em style="color:#666666;"><?echo $item['desc'];?></em></a>
	</div>
	<?		
		}
	?>
	</div>
	<?
		return;	
	}
			
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

	$defindex='custom.module'.(count($toolbaritems)-1);
	
?>
<div style="display:none;"><textarea id="codegen_seedobj"><?echo $c;?></textarea></div>
<table>
<?
	foreach ($obj['fields'] as $fld){
		$field=$fld['field'];
		$disp=$fld['disp'];
		$def=$fld['def'];
		$numeric=isset($fld['numeric'])?$fld['numeric']:0;
		$type=isset($fld['type'])?$fld['type']:null;
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
