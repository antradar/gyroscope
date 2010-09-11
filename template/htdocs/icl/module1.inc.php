<?
function showlist1(){

	//here's a hard coded list of items
	//in practice this list is usually populated by a data table
?>
<div class="section"><div style="margin-bottom:6px;">
<div class="listitem"><a onclick="show_module1_details(0,'E1 Item0');">E1 Item0</a></div>
<div class="listitem"><a onclick="show_module1_details(1,'E1 Item1');">E1 Item1</a></div>
</div></div>
&nbsp;
<script>
	gid('tooltitle').innerHTML='<a>List of Entity 1</a>';
	ajxjs(self.show_module1_details,'module1.js');
	//add additional JavaScript files here
</script>
<?
}


function showdetails1(){
	$m1id=GETVAL('m1id');
?>

<div class="section">
<div style="margin-bottom:6px;">
<div class="sectiontitle"><?echo "Test";?></div>
Showing Module 1 (E1) Details for Item # <?echo $m1id;?>

<!-- give the following list container an id, so that we can do cross-tab update -->
<div id="m1m2list_<?echo $m1id;?>">
<?listM1M2s($m1id);?>
</div>

</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

//display all the M2 items that are related to the specified M1 item
function listM1M2s($m1id=null){
	if ($m1id==null) $m1id=GETVAL('m1id'); //this makes the function callable by both internal and web calls
	
	//let's hard code some 1-N relations here
	$m2s=array();
	
	if ($m1id==0) $m2s=array(
		array('title'=>'E2 Item 0','id'=>0),	
		array('title'=>'E2 Item 2','id'=>2),	
		array('title'=>'E2 Item 3','id'=>3)	
	);		

	if ($m1id==1) $m2s=array(
		array('title'=>'E2 Item 1','id'=>1),	
		array('title'=>'E2 Item 2','id'=>2),	
		array('title'=>'E2 Item 3','id'=>3),	
		array('title'=>'E2 Item 5','id'=>5),	
		array('title'=>'E2 Item 7','id'=>7)	
	);		
			
	//now let's display the list
	foreach ($m2s as $m2){
		$title=$m2['title'];
		$m2id=$m2['id'];
		//note that we're switching to a different tab
		//and it's possible that the JavaScript file for that entity type is not yet loaded
		//so we use ajxjs to conditionaly load that file
?>
<div style="border-bottom:solid 1px #444444;"><a onclick="ajxjs(self.show_module2_details,'module2.js');show_module2_details(<?echo $m2id;?>,'<?echo $title;?>');"><?echo $title;?></a></div>
<?			
	}
}
?>
