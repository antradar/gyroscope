<?php

function lookupplugin(){

?>
<div class="section">


	<div class="listitem"><a onclick="lookupentity(document.hotspot,'pluginmention','Mention');">Mention</a></div>

	<div class="listitem"><a onclick="loadfs('Image Selector','showmedialibrary&selector=1',
			    function(){
				    var sels=listmediaids();
				    if (sels!=''&&document.hotspot&&document.hotspot.selection){
				   		document.hotspot.selection.setContent('<div class=&quot;plugincontainer pluginslides&quot;><p>{{slides mediaids='+sels+'}}</p></div>');
				   		hidelookup();
						if (document.hotspot&&document.hotspot.onChange) document.hotspot.onChange.dispatch();
				   		document.hotspot.focus();
			    	}
			    },
			    function(){gid('fsview').sels=[]});">Slide Show</a>
	</div>

</div>
<?php
}