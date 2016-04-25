<?php

function showaccount(){
	global $user;

	global $db;

?>
<div class="section">

<div class="sectiontitle"><?tr('account_settings');?></div>

<div class="sectionheader"><?tr('password');?></div>
<table>

<tr><td><?tr('current_password');?>:</td>
<td><input id="accountpass" type="password"></td>
</tr>

<tr><td><?tr('new_password');?>:</td>
<td><input id="accountpass1" type="password"></td>
</tr>

<tr><td><?tr('repeat_password');?>:</td>
<td><input id="accountpass2" type="password"></td>
</tr>

<tr><td></td>
<td>
<button onclick="setaccountpass();"><?tr('change_password');?></button>
</td>
</tr>

</table>

</div>
<?
	
}