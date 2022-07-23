<?php

function showdashcols($container,$cols,$func){
	$ncols=count($cols);
	$colwidth=290; //floor(100/$ncols);
	
	$subtitled=0;
	
	
	foreach ($cols as $cidx=>$col){
		if (!isset($cols[$cidx]['key'])) $cols[$cidx]['key']=md5(strtolower($col['title']));
		if (isset($cols[$cidx]['title2'])&&$cols[$cidx]['title2']!='') $subtitled=1;
	}
	
	
?>	
	<div style="padding:5px 0;line-height:2em;margin-bottom:10px;">
		<?php foreach ($cols as $cidx=>$col){
			$hint=htmlspecialchars($col['title']);
			if (isset($col['title2'])&&$col['title2']!='') $hint.='; '.htmlspecialchars($col['title2']);
			
			$subborder='';
			if ($subtitled){
				$subcolor='#dedede';
				if (isset($col['color2'])&&$col['color2']!='') $subcolor=$col['color2'];
				$subborder='border-bottom:solid 3px '.$subcolor.';';
			}	
		?>
		<acronym title="<?php echo $hint;?>"><img class="dashcolbutton" src="imgs/t.gif"
			 onclick="scrollcoldash('<?php echo $container;?>','<?php echo $col['key'];?>')"
			 style="background:<?php echo $col['color'];?>;cursor:pointer;border:solid 1px #dedede;margin-right:5px;border-radius:4px;<?php echo $subborder;?>width:14px;height:14px;font-size:1px;"></acronym>
		<?php }?>
	</div>
	<div id="<?php echo $container;?>_view" style="position:relative;overflow:auto;padding-bottom:10px;">
		<div id="<?php echo $container;?>" style="width:<?php echo $colwidth*$ncols;?>px;">
			<?php foreach ($cols as $cidx=>$col){?>
			<div id="<?php echo $container;?>_<?php echo $col['key'];?>" style="width:<?php echo $colwidth;?>px;float:left;overflow:hidden;transition:opacity 250ms;">
			<div style="padding-right:10px;">
				<div class="dashcoltitle" style="padding:5px 0;background:<?php echo $col['color'];?>;white-space:nowrap;text-align:center;"><?php echo $col['title'];?></div>
				<?php if (isset($col['title2'])||$subtitled){
					$subtitle=$col['title2'];
					if ($subtitle=='') $subtitle='&nbsp;';
					$subcolor=$col['color2'];
					if ($subcolor=='') $subcolor='#f0f0f0';	
				?>
					<div class="dashcolsubtitle" style="border-top:dashed 1px #909090;padding:5px 0;white-space:nowrap;background:<?php echo $subcolor;?>;text-align:center;"><?php echo $subtitle;?></div>
				<?php }?>
				<div id="<?php echo $container;?>_<?php echo $col['key'];?>_content" style="min-height:200px;padding:5px;">
					<?php
						if (is_callable($func)){
							$func($col['args']);	
						} else echo $func;
						
					?>
				</div>
			</div>
			</div>
			<?php }?>
			<div class="clear"></div>
		</div>

	</div>
<?php	
	
}
