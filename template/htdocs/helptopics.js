inithelptopictexteditor=function(helptopicid,st){

	xajxjs('tinyMCE','tiny_mce/mceloader.js?v=2',function(){
	
		tinyMCE.init({
			protect: [/[\n\f\r\t\v]/g],
			mode : "textareas",
			theme : "advanced",
			plugins: 'paste, advimage',
			theme_advanced_buttons1:"medialib,layout_youtube,objects,|,layout_headline,bold,italic,underline,strikethrough,|,forecolor,backcolor,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink",
			//theme_advanced_buttons2:"fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull,|,sourceedit",
			theme_advanced_buttons2:"layout_col2,layout_colside_right,|,sourceedit,removeformat",
			editor_selector:'helptopictexteditor_'+helptopicid,
			extended_valid_elements : 'img[class|ampwidth|ampheight|src]',
			paste_preprocess:function(pl,o){paste_clean_image(o);},		
			height:420,
			content_css:'tiny_mce/editor.css?v='+hb(),
			init_instance_callback:function(ed){
				var otop=document.tabviews[document.currenttab].scrollTop;
				document.tabviews[document.currenttab].scrollTop=document.tabviews[document.currenttab].scrollHeight;				
				setTimeout(function(){document.tabviews[document.currenttab].scrollTop=0;},80);
				setTimeout(function(){document.tabviews[document.currenttab].scrollTop=otop;},300);			
				if (st!=null) setTimeout(function(){ed.getBody().scrollTop=st;},300);
				ed.getDoc().showqna=function(){}; //declare null function
			},			
			setup: function(ed) {
				ed.onChange.add(function(){marktabchanged('helptopic_'+helptopicid)});
				ed.onMouseUp.add(lookupselection(ed));
				ed.addButton('medialib',{title:'media library',image:'tiny_mce/icons/image.gif',onclick:function(){tinyMCE.activeEditor=ed;loadfs('Media Library','showmedialibrary');}});
				ed.addButton('noamp',{title:'AMP exclusion zone',image:'tiny_mce/icons/noamp.gif',onclick:function(){ed.selection.setContent(mcetemplates.noamp(ed));}});
				ed.addButton('layout_headline',{title:'headline',image:'tiny_mce/icons/subheading1.gif',onclick:function(){ed.selection.setContent(mcetemplates.headline(ed));}});
				ed.addButton('layout_breaker',{title:'split container',image:'tiny_mce/icons/splitter.gif',onclick:function(){ed.selection.setContent('[[[split_injection]]]');var c=ed.getContent();ed.setContent(c.replace('[[[split_injection]]]','</div>...<div class="narrow">').replace(/<\/div>\s*<div class="narrow">/g,''));}});
				ed.addButton('narrow',{title:'narrow container',image:'tiny_mce/icons/narrow.gif',onclick:function(){ed.selection.setContent(mcetemplates.narrow(ed));}});
				ed.addButton('objects',{title:'Smart Objects',image:'tiny_mce/icons/object.gif',onclick:function(){lookupentity(ed,'plugin','Smart Objects');}});
				ed.addButton('layout_col2',{title:'2-column',image:'tiny_mce/icons/col2.gif',onclick:function(){ed.selection.setContent(mcetemplates.col2);}});
				ed.addButton('layout_colside_right',{title:'side column (right)',image:'tiny_mce/icons/col-side-right.gif',onclick:function(){ed.selection.setContent(mcetemplates.colsideright);}});
				ed.addButton('layout_qna',{title:'QnA',image:'tiny_mce/icons/qna.gif',onclick:function(){ed.selection.setContent(mcetemplates.qna);}});
				ed.addButton('layout_youtube',{title:'youtube video',image:'tiny_mce/icons/youtube.gif',onclick:function(){var key=prompt('Enter YouTube Video Key');if (key==null) return;ed.selection.setContent(mcetemplates.youtube(key));}});
				ed.addButton('layout_vimeo',{title:'vimeo - standard',image:'tiny_mce/icons/vimeo.gif',onclick:function(){var key=prompt('Enter Vimeo Video ID');if (key==null) return;ed.selection.setContent(mcetemplates.vimeo(key));}});
				ed.addButton('sourceedit',{title:'Source Editor',image:'tiny_mce/icons/code.gif',onclick:function(){tinyMCE.activeEditor=ed;loadfs('Source Editor','mceeditsource',null,initsourceeditor);}});
			}				
		});		
		
	}); //xajxjs
}


showhelptopic=function(helptopicid,name,bookmark){
	var sandboxcheck=function(){
		var cansandbox = 'sandbox' in document.createElement('iframe');
		if (!cansandbox){
			gid('helptopic_sandbox_warning_'+helptopicid).style.display='block';
			gid('helptopic_sandbox_view_'+helptopicid).style.display='none';	
		}
	}
	addtab('viewhelptopic_'+helptopicid,'<img src="imgs/t.gif" class="ico-helptopic">'+name,'showhelptopic&helptopicid='+helptopicid,sandboxcheck,null,{bookmark:bookmark});	
}

edithelptopic=function(helptopicid,name,bookmark){
	addtab('helptopic_'+helptopicid,'<img src="imgs/t.gif" class="ico-setting">'+name,'edithelptopic&helptopicid='+helptopicid, function(){inithelptopictexteditor(helptopicid);},null,{bookmark:bookmark});	
}

_inline_lookuphelptopic=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
		
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=slv_core__helptopics&mode=embed&key='+encodeHTML(d.value)+soundex);
	},200
	);	
}


addhelptopic=function(gskey){

	var suffix='new';
	var ohelptopictitle=gid('helptopictitle_'+suffix);
	var ohelptopickeywords=gid('helptopickeywords_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(ohelptopictitle)) {valid=0; offender=offender||ohelptopictitle;}
	//if (!valstr(ohelptopickeywords)) {valid=0; offender=offender||ohelptopickeywords;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}

	var helptopictitle=encodeHTML(ohelptopictitle.value);
	var helptopickeywords=encodeHTML(ohelptopickeywords.value);
	
	var params=[];
	params.push('helptopictitle='+helptopictitle);
	params.push('helptopickeywords='+helptopickeywords);

	
	reloadtab('helptopic_new','','addhelptopic',function(req){
		var helptopicid=req.getResponseHeader('newrecid');		
		reloadview('core.helptopics','helptopiclist');
	},params.join('&'),null,gskey);
	
}

updatehelptopic=function(helptopicid,gskey){
	var suffix=helptopicid;
	var ohelptopictitle=gid('helptopictitle_'+suffix);
	var ohelptopickeywords=gid('helptopickeywords_'+suffix);
	var ohelptopictext=gid('helptopictext_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(ohelptopictitle)) {valid=0; offender=offender||ohelptopictitle;}
	//if (!valstr(ohelptopickeywords)) {valid=0; offender=offender||ohelptopickeywords;}
	//if (!valstr(ohelptopictext)) {valid=0; offender=offender||ohelptopictext;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}
	
	var helptopictitle=encodeHTML(ohelptopictitle.value);
	var helptopickeywords=encodeHTML(ohelptopickeywords.value);
	
	var helptopictext=encodeHTML(tinyMCE.get('helptopictext_'+suffix).getContent());
	var st=tinyMCE.get('helptopictext_'+suffix).getBody().scrollTop;	
	
	var params=[];
	params.push('helptopictitle='+helptopictitle);
	params.push('helptopickeywords='+helptopickeywords);
	params.push('helptopictext='+helptopictext);

	
	reloadtab('helptopic_'+helptopicid,'','updatehelptopic&helptopicid='+helptopicid,function(){
		reloadview('core.helptopics','helptopiclist');
		inithelptopictexteditor(helptopicid,st);
		flashstatus(document.dict['statusflash_updated']+ohelptopictitle.value,5000);
	},params.join('&'),null,gskey);
	
}


delhelptopic=function(helptopicid,gskey){
	if (!sconfirm(document.dict['confirm_helptopic_delete'])) return;
	
	reloadtab('helptopic_'+helptopicid,null,'delhelptopic&helptopicid='+helptopicid,function(){
		closetab('helptopic_'+helptopicid);
		reloadview('core.helptopics','helptopiclist');
	},null,null,gskey);
}

inchelptopiclevel=function(helptopicid){
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=inchelptopiclevel&helptopicid='+helptopicid,0,0,null,function(){
		reloadview('core.helptopics','helptopiclist');	
	});
}

dechelptopiclevel=function(helptopicid){
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=dechelptopiclevel&helptopicid='+helptopicid,0,0,null,function(){
		reloadview('core.helptopics','helptopiclist');	
	});
}

///////

helptopic_touchstart=function(d,tmid,page,gskey){
	if (event.touches.length==1){
		return; //uncomment to debug on emulator
	}

	event.preventDefault();

	var sd=gid('helptopicshadow');
	
	d.ontouchend=function(){
		d.tmid=tmid;
		
		if (sd.swapsrc==null||sd.swapsrc.tmid==null) {
			sd.swapsrc=d;
			d.className='sortlistitem src';
		} else {
			if (d!=sd.swapsrc) {sd.swapdst=d;d.className='sortlistitem dst';}
		}


		if (sd.swapsrc!=null&&sd.swapdst!=null){
			sd.swapdst.style.borderTop='dashed 2px';
			var srcid=sd.swapsrc.tmid;
			var dstid=sd.swapdst.tmid;

			setTimeout(function(){
				var params=gid('helptopiclist').reloadparams;
				var newparams=params;
				if (params) {
					newparams=newparams.u.replace(document.appsettings.codepage+'?cmd=slv_codegen__helptopics','');
					newparams=newparams.replace('tmid=','oldtmid=').replace('targetid=','oldtargetid');
				} else newparams='';
			
				ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=swaphelptopicpos&mode=embed&tmid='+srcid+'&page='+page+'&targetid='+dstid+'&'+newparams,0,0,'',function(){
					gid('helptopiclist').reloadparams=params;
				},null,null,gskey);
			},300);
			
			sd.swapsrc=null;
			sd.swapdst=null;
		}

	}
}

helptopic_mousedown=function(d,tmid,page,gskey){
	var dragging;
	var posy=d.offsetTop;
	var oy;
		
	var c=gid('helptopiclist');
	var ch=c.offsetHeight;
	
	var sd=gid('helptopicshadow');
	var label=d.getElementsByTagName('a')[0].innerHTML;
	if (!d.inited){
		if (d.timer) clearTimeout(d.timer);
		d.timer=setTimeout(function(){
		d.onmousemove=function(e){
			if (!dragging){
				dragging=true;
				document.dragtmid=tmid;
				
				if (e) oy=e.clientY; else oy=event.clientY;
				
				sd.style.display='block';
				sd.style.top=posy+'px';
				sd.innerHTML=label;
			
				dragging=true;
				d.style.opacity=0.5;
				return;	
			}
			
			var y;
			if (e) y=e.clientY; else y=event.clientY;
			var newy=posy+y-oy;
			if (newy<0) newy=0;
			if (newy>ch) newy=ch;
			
			sd.style.top=newy+'px';
				
		}	
		
		d.onmouseup=function(e){
			d.style.opacity=1;
			sd.style.display='none';
			d.onmousemove=null;
			d.inited=null;
			d.onmouseup=null;
			d.onmousemove=null;
			dragging=null;
			sd.onmouseup=null;
			document.onmousemove=null;
			document.onmouseup=null;
			
			
			if (document.targettm) {//perform swapping
				var targetid=document.targettm.tmid;
				document.targettm.style.borderBottom='solid 1px #d4edc9';
				document.targettm=null;
				var params=gid('helptopiclist').reloadparams;
				var newparams=params;
				if (params) {
					newparams=newparams.u.replace(document.appsettings.codepage+'?cmd=slv_codegen__helptopics','');
					newparams=newparams.replace('tmid=','oldtmid=').replace('targetid=','oldtargetid');
				} else newparams='';
				ajxpgn('helptopiclist',document.appsettings.codepage+'?cmd=swaphelptopicpos&mode=embed&tmid='+document.dragtmid+'&page='+page+'&targetid='+targetid+'&'+newparams,0,0,'',function(){
					gid('helptopiclist').reloadparams=params;
				},null,null,gskey);
			}
			
			document.dragtmid=null;
			
		}
		
		document.onmouseup=d.onmouseup;
		document.onmousemove=d.onmousemove;
		sd.onmouseup=d.onmouseup;
		
		d.inited=true;
		},50);
	}
}

helptopic_mouseover=function(d,tmid,parent){
	if (!document.dragtmid) return;
	d.tmid=tmid;
	var tg=d.parentNode;
	if (parent) tg=d;
	
	tg.style.borderTop='solid 1px #ffab00';
	if (tmid!=-1) tg.style.borderLeft='solid 5px #ffab00';
	document.targettm=d;	
	
	d.onmouseout=function(){
		if (!document.dragtmid) return;
		document.targettm=null;
		tg.style.borderTop='none';
		if (tmid!=-1) tg.style.borderLeft='solid 5px #dedede';
	}
}


