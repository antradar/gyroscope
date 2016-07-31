(function(){
if (document.createComment){
	document.write('<'+'script src="tiny_mce/tiny_mce_src.js" type="text/javascript"><'+'/script>');
} else {
	window.tinyMCE={
		init:function(){},
		get:function(id){
			var d=document.getElementById(id);
			d.getContent=function(){return d.value;};
			return d;
		}
	}
}
})();

function paste_clean_image(o){
	var c=o.content;
	var div=document.createElement('div');
	c=c.replace(/onload=/g,'xattr=');
	c=c.replace(/onclick=/g,'xattr=');
	c=c.replace(/onmouseover=/g,'xattr=');
	div.innerHTML=c;
		
	var imgs=div.getElementsByTagName('img');
	
	var tags=['style','id','name','class','alt','data-sz'];
	
	for (var i=0;i<imgs.length;i++){
		var img=imgs[i];
		for (var t=0;t<tags.length;t++) {
			img.removeAttribute(tags[t]);
			img.className='';
		}
	}
	
	o.content=div.innerHTML;
	
}

mcetemplates={
	'headline':function(ed){return '<div class="headline"><p>'+(ed.selection.getContent()==''?'Headline':ed.selection.getContent())+'</p></div>';},	
	'noamp':function(ed){return '<div class="noamp"><!-- noampstart --><p>'+(ed.selection.getContent()==''?'AMP Excluded Content':ed.selection.getContent())+'</p><!-- noampend --></div>';},	
	'col2':'<div class="cols"><div class="col2"><p>Left Column</p></div><div class="col2"><p>Right Column</p></div><div class="clear"></div></div>',
	'colsideright':'<div class="cols"><div class="col-left"><p>Main Column</p></div><div class="col-right"><p>Side Column</p></div><div class="clear"></div></div>',
	'qna':'<div class="qna"><div class="qnaq"><a onclick="showqna(this);">Question</a></div><div class="qnaa"><p>Answer</p></div></div>',	
	'youtube':function(key){return '<div class="plugincontainer"><p>{{youtube key='+key+'}}</p></div>';},
	'matterport':function(key){return '<div class="plugincontainer"><p>{{matterport key='+key+'}}</p></div>';}
}

reloadmedialibrary=function(){
	ajxpgn('fsview',document.appsettings.codepage+'?cmd=showmedialibrary');	
}

delmedia=function(mediaid){
	if (!confirm('Are you sure you want to remove this image?')) return;
	ajxpgn('fsview',document.appsettings.codepage+'?cmd=delmedia&mediaid='+mediaid);
}

lookupmediakey=function(d){
	ajxpgn('medialiblist',document.appsettings.codepage+'?cmd=showmedialibrary&mode=embed&key='+encodeHTML(d.value));
}

_lookupmediakey=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookupmediakey(d);
	},300);	
}