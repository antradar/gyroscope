<?php
include 'help/seeds/seeds.php';

function showguide(){
	global $codegen_seeds;
	
	?>
	
	<div style="padding:10px;">
		Generate Code: <select id="codegen_seed">
		<option value=""></option>
		<?
		foreach ($codegen_seeds as $seed=>$label){
		?>
		<option value="<?echo $seed;?>"><?echo $label;?></option>
		<?		
		}
		?>
		</select>
		<button onclick="ajxjs(self.codegen,'codegen.js');codegen_makeform();">Go</button>
	</div>
	
	<div id="codegen_view">
	
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



