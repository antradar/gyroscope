Gyroscope uses a unique key to identify each tab. For example, the key for the tab you're viewing now is "help_tabs".
Each tab has a display title.
<br><br>
When a tab is added, or reloaded, a request is sent to the server to fetch the tab content.
Such request is expressed in the <em>params</em> argument. 
A tab loading function carries an optional <em>data</em> argument, which is useful fo sending large form objects such as long text descriptions.
<br><br>
Tabs load asynchronously. The <em>loadfunc</em> parameter offers an opportunity to declare a callback function.

<br><br>
There are 4 tab operations, which are defined in <em>tabs.js</em>:
<br><br>
addtab (key, title, params, <em>loadfunc</em>,<em>data</em>,<em>options</em>)<br>
reloadtab (key, title, params, <em>loadfunc</em>,<em>data</em>)<br>
showtab (key)<br>
closetab (key)<br>
<br>
As of Gyroscope 3.0, a tab can be added without having a close button. This is accomplished by using the <em>options</em> parameter, which is also reserved for future extended functionality.
<br><br>
An example from <em>index.php</em>:
<br><br>
addtab('welcome','Welcome','wk',null,null,{noclose:1});


