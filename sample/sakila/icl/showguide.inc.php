<?php

function showguide(){
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