<?php

function showaccount(){
	global $user;

	global $db;

?>
<div class="section">

<div class="sectiontitle">Account Settings</div>

<div class="sectionheader">Password</div>
<table>

<tr><td>Current Password:</td>
<td><input id="accountpass" type="password"></td>
</tr>

<tr><td>New Password:</td>
<td><input id="accountpass1" type="password"></td>
</tr>

<tr><td>Verify Password:</td>
<td><input id="accountpass2" type="password"></td>
</tr>

<tr><td></td>
<td>
<button onclick="setaccountpass();">Change Password</button>
</td>
</tr>

</table>

</div>
<?
	
}