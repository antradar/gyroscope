(function(){
if (document.createComment&&!self.tinyMCE){
	var script=document.createElement('script');
	script.src='tiny_mce/tiny_mce_src.js?v=2';
	document.body.appendChild(script);
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

function mce_event_hook(e,ed){			    
    

    if (!ed.lastcontent){//one time init
		document.keyboard=[];
    	var content=ed.getContent();
		ed.lastcontent=content;
	}			
			    
	if (e.type=='keydown'){ 
		return document.onkeydown();
	}
	
	if (e.type=='keyup'){
			    
	    if (ed.contenttimer) clearTimeout(ed.contenttimer);
	    ed.contenttimer=setTimeout(function(){
	    var content=ed.getContent();
	    if (ed.lastcontent!=content){
		    marktabchanged(document.tabkeys[document.currenttab]);
		    ed.lastcontent=content;
	    }
		},200);

		return document.onkeyup();
				
	}

	
	return true;   
}

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
	'narrow':function(ed){return '<div class="narrow"><p>'+(ed.selection.getContent()==''?'Content':ed.selection.getContent())+'</p></div>';},	
	'col2':'<div class="cols"><div class="col2"><p>Left Column</p></div><div class="col2"><p>Right Column</p></div><div class="clear"></div></div>',
	'colsideright':'<div class="cols"><div class="col-left"><p>Main Column</p></div><div class="col-right"><p>Side Column</p></div><div class="clear"></div></div>',
	'qna':'<div class="qna"><div class="qnaq"><a onclick="showqna(this);">Question</a></div><div class="qnaa"><p>Answer</p></div></div>',	
	'youtube':function(key){return '<div class="plugincontainer"><p>{{youtube key='+key+'}}</p></div>';},
	'matterport':function(key){return '<div class="plugincontainer"><p>{{matterport key='+key+'}}</p></div>';},
	'tableindent': function(ed){
		var ind=sprompt('Indentation Level:',0);
		var c=ed.selection.getContent();
		if (c=='') return '';
		var lines=c.split("\n");
		var ncols=0;
		var rows=[];
		for (var i=0;i<lines.length;i++) {
			var line=lines[i];
			if (line=='') continue;
			line=line.replace(/^<p>([\S\s]*?)<\/p>/g,'$1').replace(/&nbsp;/g,' ').replace(/\s\s\s\s+/g,'#@SPLITER@#');
			var parts=line.split('#@SPLITER@#');
			if (ncols<parts.length) ncols=parts.length;
			rows.push(parts);
		}
		
		if (ncols<1) return 'INVALID TABLE DATA';
		var space=5*ind; //5% per level of indentation
		var percell=100-space;
		if (ncols>1) percell=Math.floor((100-space)*10/(ncols))/10;
		var html=[];
		html.push('<table nobr="true" width="100%">');
		for (var i=0;i<rows.length;i++){
			var r=[];
			r.push('<tr>');
			if (ind>0){
				var rhead='';
				if (i==0) rhead=' width="'+space+'%"';
				r.push('<td'+rhead+'>&nbsp;</td>');	
			}
			for (j=0;j<rows[i].length;j++){
				var cell=rows[i][j];
				var chead='';
				if (i==0) chead=' width="'+percell+'%"';
				r.push('<td'+chead+'>'+cell+'</td>');
			}//j
			r.push('</tr>');
			html.push(r.join(''));	
		}//i
		html.push('</table>');
				
		return html.join('');
	}
}

reloadmedialibrary=function(selector){
	ajxpgn('fsview',document.appsettings.codepage+'?cmd=showmedialibrary&selector='+selector+'&sels='+listmediaids());	
}

delmedia=function(mediaid,selector,gskey){
	if (!sconfirm('Are you sure you want to remove this image?')) return;
	if (gid('fsview').sels) delete gid('fsview').sels['mediaid_'+mediaid];
	ajxpgn('fsview',document.appsettings.codepage+'?cmd=delmedia&selector='+selector+'&mediaid='+mediaid+'&sels='+listmediaids(),0,0,null,null,null,null,gskey);
}

lookupmediakey=function(d,selector){
	ajxpgn('medialiblist',document.appsettings.codepage+'?cmd=showmedialibrary&mode=embed&selector='+selector+'&sels='+listmediaids()+'&key='+encodeHTML(d.value));
}

_lookupmediakey=function(d,selector){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookupmediakey(d,selector);
	},300);	
}

selectmedia=function(d,mediaid,prefix){
	if (d.checked){
		gid('fsview').sels['mediaid_'+mediaid]=mediaid;	
	} else {
		delete gid('fsview').sels['mediaid_'+mediaid];
	}
	
	var html=[];
	for (var k in gid('fsview').sels) html.push('<img src="'+prefix+gid('fsview').sels[k]+'.img" style="width:80px;margin:5px;">');
	if (html.length>0) html.push('<button onclick="closefs();">Apply Selection</button>');
	gid('medialibsels').innerHTML=html.join(' ');
	
}

listmediaids=function(){
	if (!gid('fsview').sels) return '';
	var sels=[];
	for (var k in gid('fsview').sels) sels.push(gid('fsview').sels[k]);
	return sels.join(',');
}

initsourceeditor=function(){
	var ed=tinyMCE.activeEditor;
	if (!ed) return;
	if (ed.selection.getContent()==''||ed.selection.getNode().outerHTML.indexOf('<body')==0) {
		gid('mcesourceeditor').value=ed.getContent().replace(/<\!--mce\:protected \%0A-->/g,"\n").replace(/<\!--mce\:protected \%09-->/g,"\t").replace(/\t\n/g,"\t").replace(/\n+/g,"\n");
		gid('mcesourceeditor').dsel=null;
	} else {
		gid('mcesourceeditor').value=ed.selection.getNode().outerHTML.replace(/<\!--mce\:protected \%0A-->/g,"\n").replace(/<\!--mce\:protected \%09-->/g,"\t").replace(/\t\n/g,"\t").replace(/\n+/g,"\n").replace(/\s*data-mce-style="[\S\s]*?"/g,'');
		gid('mceeditor_save').style.border='dashed 2px #dedede';
		gid('mceeditor_save').innerHTML='Update Snippet';
		gid('mcesourceeditor').dsel=ed.selection.getNode();

	}
		
}

initimecree=function(){
	gid('imecreekeyboard').cree=null;
	creeime(gid('imecreekeyboard'));

}

updateimecree=function(d){
	var content=gid('imecreekeyboard').value;
	
	var ed=tinyMCE.activeEditor;
	if (!ed) return;
	if (gid('imecreekeyboard').changed&&ed.onChange) ed.onChange.dispatch();		
	
	ed.selection.setContent(content);
	
	closefs();
				
}

updatesourceeditor=function(d){
	var content=gid('mcesourceeditor').value.replace(/\n/g,'<!--mce:protected %0A-->').replace(/\t/g,'<!--mce:protected %09-->');
	
	var ed=tinyMCE.activeEditor;
	if (!ed) return;
	if (gid('mcesourceeditor').changed&&ed.onChange) ed.onChange.dispatch();		
	
	var dsel=gid('mcesourceeditor').dsel;

	if (dsel==null) {
		ed.setContent(content);
	} else {
		dsel.outerHTML=content;
	}
	
	closefs();
				
}

renamemedia=function(mediaid,d,gskey){
	var newname=prompt('Rename to:',d.innerHTML);
	if (newname==null) return;
	
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=renamemedia&mediaid='+mediaid+'&fn='+encodeHTML(newname),0,0,null,function(rq){
		flashstatus(rq.responseText,3000);
		d.innerHTML=newname.replace(' ','_');	
	},null,null,gskey);
	
}

lookupselection=function(ed){
	return function(){
		var content=ed.selection.getContent();
		if (content==null||content=='') return;
		//if (content.replace(/<img class=\"\S+\"\s*ampwidth="\d+"\s*ampheight="\d+" src="\S+"\s*\/?>/,'x')=='x'&&content!='x') lookupentity(ed,'imageoption','Image Options');
		if (content.replace(/<img [\S\s]*?\/?>/,'x')=='x'&&content!='x') lookupentity(ed,'imageoption','Image Options');
	}	
}

replaceimageclass=function(classname){
	if (!document.hotspot) return;
	var content=document.hotspot.selection.getContent();
	if (content==null||content=='') return;

	if (content.indexOf(' class=')==-1) content=content.replace('<img ','<img class="tempclass" ');

	content=content.replace(/<img class=\"(\S+)\"/g,'<img class="'+classname+'"');
	document.hotspot.selection.setContent(content);
	hidelookup();
}
