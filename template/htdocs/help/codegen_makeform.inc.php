<?php
include 'help/seeds/seeds.php';

function codegen_makeform($seed=null){
	global $toolbaritems;
	if (!isset($seed)) $seed=SGET('seed');
	global $codegen_seeds;
	
	$seedobj=null;
	
	foreach ($codegen_seeds as $k=>$v){
		if ($seed==$k) {
			$seedobj=$v;
			break;	
		}
		if (isset($v['package'])&&$v['package']){
			foreach ($v['items'] as $ik=>$iv){
				if ($seed==$ik) $seedobj=$iv;	
			}	
		}
		if ($seedobj) break;
	}
	
	
	$seedname=isset($seedobj['name'])?$seedobj['name']:'*no name*';
	$desc=isset($seedobj['desc'])?$seedobj['desc']:null;
	$icon=isset($seedobj['icon'])?$seedobj['icon']:null;
	$package=isset($seedobj['package'])?$seedobj['package']:null;	
	
	if ($package){
		
		$items=$seedobj['items'];
	?>
	This code generator supports a few variants:
	<div style="padding-top:10px;padding-bottom:20px;">
	<?php	
		foreach ($items as $itemkey=>$item){
	?>
	<div class="listitem">
		<a onclick="codegen_makeform('<?php echo $itemkey;?>');"><?php echo $item['name'];?><br><em style="color:#666666;"><?php echo $item['desc'];?></em></a>
	</div>
	<?php		
		}
	?>
	</div>
	<?php
		return;	
	}
			
	$fn='help/seeds/'.$seed.'.json';
	if (!file_exists($fn)) {echo "This module is not available in the community edition of Gyroscope";return;}
	
?>
<div class="sectiontitle" <?php if ($desc!='') echo 'style="margin-bottom:10px;"';?>><?php echo $seedname;?></div>
<?php
if ($desc!=''){
?>
<div style="color:#444444;padding-bottom:20px; echo 'background:transparent url() no-repeat 0 0;';?>">
<?php if ($icon!=''){?>
	<img class="codegenicon" src="help/seeds/icons/<?php echo $icon;?>.png" style="float:left;margin-right:15px;">
<?php }?>
	<div style="float:left;padding-top:5px;"><?php echo $desc;?></div>
	<div class="clear"></div>
</div>
<?php	
}//desc
	$c=file_get_contents($fn);
	$obj=json_decode(file_get_contents($fn),1) or die('error parsing form config file');

	$defindex='custom.module'.(count($toolbaritems)-1);
	
?>
<div style="display:none;"><textarea id="codegen_seedobj"><?php echo $c;?></textarea></div>
<table>
<?php
	foreach ($obj['fields'] as $fld){
		$field=$fld['field'];
		$disp=$fld['disp'];
		$def=$fld['def']??null;
		$numeric=isset($fld['numeric'])?$fld['numeric']:0;
		$type=isset($fld['type'])?$fld['type']:null;
		if ($type=='viewindex') $def=$defindex;
		$tag='input'; $ctag='';
		if ($type=='fieldlist') {$tag='textarea';$ctag=$def.'</textarea>';}
		if ($type=='enum'){
			$tag='select';
			$ctag='';
			$fopts=explode(',',$def);
			foreach ($fopts as $fopt){
				$optparts=explode('|',$fopt);
				$ctag.='<option value="'.$optparts[0].'">'.($optparts[1]??'!undefined').'</option>';	
			}
			$ctag.='</select>';	
		}
		if ($type=='checkbox'){
		?>
		<tr>
			<td></td>
			<td>
				<input type="checkbox" <?php echo $def;?> id="codegenfield_<?php echo $field;?>">
				<label for="codegenfield_<?php echo $field;?>"><?php echo $disp;?></label>
			</td>
		</tr>
		<?php
			continue;	
		}
?>
<tr>
	<td class="formlabel" style="text-align:right;" valign="top"><?php echo $disp;?>:</td>
	<td>
		<<?php echo $tag;?> id="codegenfield_<?php echo $field;?>" class="inp<?php if ($numeric) echo 'short';?> onclick="select(this);" value="<?php echo $def;?>"><?php echo $ctag;?>
	</td>
</tr>
<?php		
	}//foreach	
?>
<tr><td></td><td>
	<button id="codegen_button" onclick="codegen_makecode('<?php echo $seed;?>');">Generate Code!</button>	
</td></tr>
</table>

<div id="codegen_codes" style="padding-top:20px;"></div>
<?php
}
