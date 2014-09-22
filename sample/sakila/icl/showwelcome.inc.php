<?php
include 'icl/showguide.inc.php';
include 'icl/showgyroscopeupdater.inc.php';

function showwelcome(){
?>
<div class="section">
	<div class="sectiontitle">Welcome to Sakila DB Sample Project</div>
	
	<div class="col">
		<div class="sectionheader">Running the Sample Project</div>
		<p style="line-height:20px;">
		This sample project uses a fraction of the schema and features from the Sakila database.
		The source code is moderately annotated. By reading through the source code, you'll learn the standard techniques for implementing Tool Icons,
		List Views, Detail Views, Lookups, Record Creation, Modification and Deletion, etc. You'll also see some of Gyroscope's latest features
		such as Conditional Inline Lookup, Identity Locking, Cross Tab Synchronization, More Efficient Tab Reload, Mobile Optimized Layouts and so on. 
		</p>

	<p>
	Gyroscope also offers a selection of convenient date/time pickers:
	</p>

	Date: <input id="datetest" onfocus="pickdate(this,{mini:0});" onkeyup="_pickdate(this,{mini:0});">
	<?makelookup('datetest',1);?>

	<br><br>
	Time: <input id="timetest" onfocus="picktime(this,{start:9,end:17,mini:0});">
	<?makelookup('timetest',1);?>

	<br><br>
	Date/Time: <input id="datetimetest" onfocus="pickdatetime(this,{start:7,end:20,mini:0});" onkeyup="_pickdatetime(this,{start:7,end:20,mini:0});">
	<?makelookup('datetimetest',1);?>
	<br><br>

		Click on an icon to start:<br><br>
		
		<a onclick="showview(1,null,1);"><img src="imgs/t.gif" class="img-actors" width="32" height="32"> </a> &nbsp; &nbsp;
		<a onclick="showview(2,null,1);"><img src="imgs/t.gif" class="img-films" width="32" height="32"> </a> &nbsp; &nbsp;
		<a onclick="showview(0,null,1);"><img src="imgs/t.gif" class="img-reports" width="32" height="32"> </a><br><br>
		
	</div>

	<div class="col">
		<div class="sectionheader">About Sakila and Sakila DB</div>
		<p style="line-height:20px;">
		Sakila is the name of the dolphin on the MySQL logo.
		<br>
		The Sakila Sample Database can be downloaded from the MySQL website. It was developed by a former member of the MySQL documentation team,
		and is intended to provide a standard schema that can be used for examples in books, tutorials, articles, samples, and so forth. 
		Sakila sample database also serves to highlight the latest features of MySQL such as Views, Stored Procedures, and Triggers.
		<br><span style="color:#444444;">(source: dev.mysql.com)</span>
		</p>
	</div>
		
	<div class="clear"></div>




	<?
	showgyroscopeupdater();
	showguide();
	?>			

	
</div><!-- section -->
<?
}
