<?php

function pretty_array($items,$stem='unique_container_name',$showall=0,$depth=0,$pk=''){

	if (!is_array($items)) return;
	
	foreach ($items as $k=>$v){

		if (str_replace('-','',$k)==''&&$v==''){
			echo '<hr>';
			continue;	
		}
	
	?>
	<div style="padding:3px 0;padding-left:<?php echo $depth*5;?>px;<?php if ($depth>0){?>border-left:solid 2px #848cf7;margin-left:5px;<?php }?>">
		<?php if ($depth>0){?>
		<acronym title="<?php echo htmlspecialchars($pk);?>/<?php echo htmlspecialchars($k);?>" style="cursor:pointer;">
		<?php }?>
		<?php echo htmlspecialchars($k);?>
		<?php if ($depth>0){?>
		</acronym>
		<?php }?>
		 =>
		<?php if (!is_array($v)){//non array value
			ob_start();
			var_dump($v);
			$vinfo=ob_get_clean();
			
			if (is_int($v)&&strlen($v)==10){
				$year=date('Y',$v);
				if ($year>=1800&&$year=3000){
					$vinfo.='Date: '.date('Y-n-j H:i:s',$v);	
				}	
			}
			
		?>
			<acronym title="<?php echo htmlspecialchars($vinfo);?>" style="cursor:pointer;"><?php echo htmlspecialchars($v);?></acronym>
		<?php	
			if (!isset($v)){
		?>
			<em style="color:#666666;">(null)</em>
		<?php		
			}
		
		} else {//array value
			if (count($v)==0){
			?>
			<em style="color:#666666;">(empty)</em>
			<?php	
			} else {
				if (!$showall){
		?>
		<a class="hovlink" onclick="showhide('<?php echo $stem;?>_<?php echo $k;?>_<?php echo str_replace('/','-',$pk);?>');">[+]</a>
		<?php
				}
		?>
		<div style="display:none<?php if ($showall) echo 'a';?>;" id="<?php echo $stem;?>_<?php echo $k;?>_<?php echo str_replace('/','-',$pk);?>">
		<?php
			pretty_array($v,$stem,$showall,$depth+1,$pk.'/'.$k);
		?>
		</div>
		<?php	
			}//non-empty sub array
		
		}//array value
		?>
	</div>
	<?php
		
		
	}//foreach item
		
	
}