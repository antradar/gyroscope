<?php
include 'help/seeds/seeds.php';

function showguide(){
	global $codegen_seeds;
	?>
		
<!-- {{ -->	
</div>
	
	<div id="codegenlist" style="padding:20px 20px;background-color:#444444;color:#ffffff;display:none;">
		
		<?foreach ($codegen_seeds as $seed=>$seedinfo){
			$label=$seedinfo['name'];
		?>
		<div style="float:left;width:100px;margin-right:10px;margin-bottom:10px;">
			<a style="font-size:12px;background-color:#333333;border-radius:4px;padding:5px 8px;white-space:nowrap;" onclick="ajxjs(self.codegen,'codegen.js');codegen_makeform('<?echo $seed;?>');"><?echo $label;?></a>
		</div>
		<?	
		}
		?>
		<div class="clear"></div>
		
	</div>
	
</div class="section">
<!-- }} -->	
	
	<div id="codegen_view" style="padding:20px;">
	
	</div>
	<?
	
	/*
	quotecode('listview','listactors.php',array(
		'viewindex'=>3,
		'record'=>'actor',
		'records'=>'actors',
		'Record'=>'Actor',
		'Records'=>'Actors',
		'tablename'=>'actors',
		'searchquery'=>"where fname like '$key%'",
		'sortquery'=>'fname'
	));
	
	return;
	*/
	
	//makeconfigform('listview');
	
	
	return;
?>
<p>
The Gyroscope UI consists of <a onclick="showhelp('menuicons','Configuring Menu Icons');"><u>Menu Icons</u></a>,
<a onclick="showhelp('listviews','Writing a List View');"><u>List Views</u></a> and Detailed Views.
Detailed Views are displayed in <a onclick="showhelp('tabs','Tab Operations');"><u>Tabs</u></a>.
<?
/*
<br><br>
A Detailed View displays the basic information of a record. It also pivots to other record types through its "Related Records" section.
*/
?>
</p>
<p>
You can change this welcome message by editing <em>icl/showwelcome.inc.php</em>.<br>
The help files in the <em>help/</em> folder can be safely removed. Though the help folder can be utilized as part of your own <a onclick="showhelp('helpsys','The Help System');"><u>help system</u></a>.
</p>
<?
}



