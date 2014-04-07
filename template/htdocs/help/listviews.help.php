A list view is typically invoked by clicking on a <a onclick="showhelp('menuicons','Configuring Menu Icons');"><u>menu icon</u></a>. In the following templates, replace the text between # and # with proper table names.
<br><br>
<b>A basic list view template</b>:
<br><br>
<textarea style="width:100%;height:200px;">
&lt;?php

function list#records#{
	global $db;
	
?&gt;
<div class="section">
	A sample list!
</div>
<script>
	gid('tooltitle').innerHTML='<a>#Records#</a>';
	ajxjs(self.show#record#,'#records#.js');
</script>
&lt;?		
}
</textarea>

<br><br>

<b>A more advanced example that features lookup and paging</b>:

<br><br>

<textarea style="width:100%;height:300px;">
&lt;?php

function list#record#s(){
	global $db;
	$mode=GETSTR('mode');
	$key=GETSTR('key');
	
	$page=$_GET['page']+0;
	
	if ($mode!='embed'){

?&gt;
<div class="section">
<div class="listbar">
<input id="#record#key" class="img-mg" onkeyup="_inline_lookup#record#(this);">
</div>
<div id="#record#list">
&lt;?		
	}

	$query="select * from #record#s ";
	if ($key!='') $query.=" where (fname like '$key%' or lname like '$key%' or concat(fname,' ',lname) like '$key%' ) ";
	$rs=sql_query($query,$db);
	$count=sql_affected_rows($db,$rs);
	
	$perpage=20;
	$maxpage=ceil($count/$perpage)-1;
	if ($maxpage<0) $maxpage=0;
	if ($page<0) $page=0;
	if ($page>$maxpage) $page=$maxpage;
	$start=$perpage*$page;

	if ($maxpage>0){
?&gt;
<div style="font-size:12px;padding:10px 0;">
&lt;?echo $page+1;?&gt; of &lt;?echo $maxpage+1;?&gt;
&amp;nbsp;
<a href=# onclick="ajxpgn('#record#list',document.appsettings.codepage+'?cmd=slv1&page=&lt;?echo $page-1;?&gt;&mode=embed');return false;">&amp;laquo; Prev</a>
|
<a href=# onclick="ajxpgn('#record#list',document.appsettings.codepage+'?cmd=slv1&page=&lt;?echo $page+1;?&gt;&mode=embed');return false;">Next &amp;raquo;</a>
</div>
&lt;?		
	}
	
	$query.=" order by fname,lname limit $start,$perpage";	
	
	$rs=sql_query($query,$db);
	
	while ($myrow=sql_fetch_array($rs)){
		$#primarykey#=$myrow['#primarykey#'];
		$fname=$myrow['fname'];
		$lname=$myrow['lname'];
		$#record#title="$fname $lname";
		$db#record#title=noapos(htmlspecialchars($#record#title));
?&gt;
<div class="listitem"><a onclick="show#record#(&lt;?echo $#primarykey#;?&gt;,'&lt;?echo $db#record#title;?&gt;');">&lt;?echo $#record#title;?&gt;</a></div>
&lt;?		
	}//while
	
	if ($mode!='embed'){
?&gt;
</div>
</div>

<script>
gid('tooltitle').innerHTML='<a>#record#s</a>';
ajxjs(self.show#record#,'#record#s.js');
</script>
&lt;?	
	}//embed mode

}
</textarea>

<br><br>
<b>Template for <em>#records#.js</em></b>:

<textarea style="width:100%;height:200px;">
show#record#=function(#primarykey#,name){
	addtab('#record#_'+#primarykey#,name,'show#record#&#primarykey#='+#primarykey#);	
}

_inline_lookup#record#=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('#record#list',document.appsettings.codepage+'?cmd=slv1&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}
</textarea>