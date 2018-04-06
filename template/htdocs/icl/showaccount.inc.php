<?php
include 'icl/showkeyfilepad.inc.php';

function showaccount(){
	global $smskey;
	
	$user=userinfo();

	global $db;
	
	$userid=$user['userid']+0;
	$query="select * from users where userid=$userid";
	$rs=sql_query($query,$db);
	$myrow=sql_fetch_assoc($rs);
	$needkeyfile=$myrow['needkeyfile'];
	$usesms=$myrow['usesms'];
	$smscell=$myrow['smscell'];
	
	if ($smskey=='') $usesms=0;
	
?>
<div class="section">

<div class="sectiontitle"><?tr('account_settings');?></div>

<div class="col">
	<div class="sectionheader"><?tr('password');?></div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('current_password');?>:</div>
		<input class="inp" id="accountpass" type="password">
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('new_password');?>:</div>
		<input class="inp" id="accountpass1" type="password">
	</div>
	
	<div class="inputrow">
		<div class="formlabel"><?tr('repeat_password');?>:</div>
		<input class="inp" id="accountpass2" type="password">
	</div>

	<div class="inputrow">
		<input type="checkbox" <?if ($smskey=='') echo 'disabled';?> id="myaccount_usesms" <?if ($usesms) echo 'checked';?> onclick="if (this.checked) {gid('myaccount_smscellview').style.display='block';gid('myaccount_smscell').focus();} else gid('myaccount_smscellview').style.display='none';">
		<label style="<?if ($smskey=='') echo 'color:#666666;'?>" for="myaccount_usesms">use SMS code to enhance login</label>
	</div>
	
	<div id="myaccount_smscellview" style="padding-left:30px;display:none<?if ($usesms) echo 'a';?>;">
		<div class="inputrow">
			Cell: <input class="inpmed" id="myaccount_smscell" value="<?echo htmlspecialchars($smscell);?>">
		</div>
	</div>
		
	<div class="inputrow">
		<input type="checkbox" id="myaccount_needkeyfile" <?if ($needkeyfile) echo 'checked';?>>
		<label for="myaccount_needkeyfile">use a key file to enhance login</label>
	</div>
	
	
	<div class="inputrow">
		<button onclick="setaccountpass();"><?tr('change_password');?></button>
	</div>
	
</div>
<div class="col">
	<div class="sectionheader">Key File</div>
	<?showkeyfilepad('mykeyfile',$user['userid']);?>
</div>
<div class="clear"></div>



</div><!-- section -->
<?
	
}