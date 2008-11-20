<?
function showlist2(){

	$key=GETSTR('key');
?>
<div class="section"><div style="margin-bottom:6px;">

<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(0,'E2 Item0');">E2 Item0</a></div>
<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(1,'E2 Item1');">E2 Item1</a></div>
<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(2,'E2 Item2');">E2 Item2</a></div>
<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(3,'E2 Item3');">E2 Item3</a></div>
<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(5,'E2 Item5');">E2 Item5</a></div>
<div style="border-bottom:dashed 1px #444444;"><a onclick="show_module2_details(7,'E2 Item7');">E2 Item7</a></div>

</div></div>
&nbsp;
<script>
	gid('tooltitle').innerHTML='<a>List of Entity 2</a>';
	ajxjs(self.show_module2_details,'module2.js');
	//add additional JavaScript files here
</script>
<?
}


function showdetails2(){
	$m2id=GETVAL('m2id');
?>

<div class="section">
<div style="margin-bottom:6px;">
<div class="sectiontitle"><?echo "Test";?></div>
Showing Module 2 (E2) Details for Item # <?echo $m2id;?>

<!-- give the following list container an id, so that we can do cross-tab update -->
<div id="m1m2list_<?echo $m1id;?>">
<?listM2M1s($m2id);?>
</div>

<div>

<div style="margin-top:20px;margin-bottom:10px;font-size:12px;">
Just for kicks, click on the following links to add content to other tabs.
<br>
In a real app, the JavaScript function directly reloads the target list container and stores the changes in the database;
<br>
As there's no storage here, the target tab has to be open when you click on the links.
</div>

<div><a onclick="m2_add_to_m1list(0,<?echo $m2id;?>,'E2 Item <?echo $m2id;?>');">Add me to E1-Item 0</a></div>
<div><a onclick="m2_add_to_m1list(1,<?echo $m2id;?>,'E2 Item <?echo $m2id;?>');">Add me to E1-Item 1</a></div>

</div>


</div><!-- margin-bottom -->
</div><!-- section -->

<?
}

//display all the M1 items that are related to the specified M2 item
function listM2M1s($m2id=null){
	if ($m2id==null) $m1id=GETVAL('m2id'); //this makes the function callable by both internal and web calls
	
	//let's hard code some one two many relations here
	$m1s=array();
	
	if ($m2id==0) $m1s=array(
		array('title'=>'E1 Item 0','id'=>0)
	);		

	if ($m2id>0) $m1s=array(
		array('title'=>'E1 Item 0','id'=>0),	
		array('title'=>'E1 Item 1','id'=>1)	
	);		
			
	//now let's display the list
	foreach ($m1s as $m1){
		$title=$m1['title'];
		$m1id=$m1['id'];
		//note that we're switching to a different tab
		//and it's possible that the JavaScript file for that entity type is not yet loaded
		//so we use ajxjs to conditionaly load that file
?>
<div style="border-bottom:solid 1px #444444;"><a onclick="ajxjs(self.show_module1_details,'module1.js');show_module1_details(<?echo $m1id;?>,'<?echo $title;?>');"><?echo $title;?></a></div>
<?			
	}
}
?>

