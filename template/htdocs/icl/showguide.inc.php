<?php
include 'help/seeds/seeds.php';

function showguide(){
	global $codegen_seeds;
	?>
		
<!-- {{ -->	
</div>
	
	<div id="codegenlist" style="padding:20px 20px;background-color:#444444;color:#ffffff;display:none;">
		
		<?php foreach ($codegen_seeds as $seed=>$seedinfo){
			$label=isset($seedinfo['name'])?$seedinfo['name']:'';
			$type=isset($seedinfo['type'])?$seedinfo['type']:'';
			if ($type=='break'){
			?>
		<div class="clear"></div>
			<?php
				continue;
			}
		?>
			<a style="float:left;margin-right:15px;margin-bottom:10px;display:block;font-size:12px;background-color:#333333;border-radius:4px;padding:5px 8px;white-space:nowrap;" onclick="ajxjs(<?php jsflag('codegen');?>,'codegen.js');codegen_makeform('<?php echo $seed;?>');"><?php echo $label;?></a>
		<?php	
		}
		?>
		<div class="clear"></div>
		
	</div>
	
<div class="section">
<!-- }} -->	
	
	<div id="codegen_view" style="padding:20px;">
	
	</div>
<?php
}



