Gyroscope has a built-in help system that displays help topics in <a onclick="showhelp('tabs','Tab Operations');"><u>Tabs</u></a>.
<br><br>
Each help topic is assigned a <em>topic key</em>, which becomes part of the tab key.<br>
For instance, the topic key for the Help System is "helpsys", and its tab key is "help_helpsys".
<br><br>
Instead of using the generic tab function to open a help document, use the convenient <em>showhelp</em> function:<br>
showhelp(<em>topic key</em>,<em>topic title</em>);
<br><br>
The help documents are stored in the <em>help/</em> folder. The help system uses the topic key as a component of the file name.<br>
For example: <em>help/tabs.help.php</em>
<br><br>
You may use this help icon <acronym title="source: imgs/help.gif"><img src="imgs/help.gif"></acronym> here and there to offer the users inline help.