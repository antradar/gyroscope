inittemplatetexteditor=function(templateid,st){
	xajxjs('tinyMCE','tiny_mce/mceloader.js?v=2',function(){
	
		var plugins=gid('templateplugins_'+templateid).value;
		var anysize='';
		var alignment='';
		if (plugins.indexOf('anysize')!=-1) anysize='|width|height';
		if (plugins.indexOf('alignment')!=-1) alignment='|,justifyleft,justifycenter,justifyright,';
		tinyMCE.init({
			protect: [/[\n\f\r\t\v]/g],
			mode : "textareas",
			theme : "advanced",
			plugins: 'paste, advimage',
			theme_advanced_buttons1:"bold,italic,underline,strikethrough,|,forecolor,backcolor,|,bullist,numlist,"+alignment+"|,outdent,indent,blockquote,|,link,unlink",
			//theme_advanced_buttons2:"fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull,|,code",
			theme_advanced_buttons2:plugins+",systemplatevars,sourceedit,removeformat", //imecree
			editor_selector:'templatetexteditor_'+templateid,
			extended_valid_elements : 'img[class|ampwidth|ampheight|src|title|alt'+anysize+'],table[nobr|class|border|cellpadding|cellspacing|width|style],tr[nobr|class|bgcolor|style]',
			paste_preprocess:function(pl,o){paste_clean_image(o);},		
			height:500, //match original textarea height
			content_css:'tiny_mce/templateeditor.css?v='+hb(),
			init_instance_callback:function(ed){
				if (st!=null){
					setTimeout(function(){
						ed.getBody().scrollTop=st;
						ed.getBody().parentNode.scrollTop=st;
					},20);
				}
			},
		    handle_event_callback:mce_event_hook,			
		    setup: function(ed) {

			    ed.onChange.add(function(){marktabchanged(document.tabkeys[document.currenttab]);});
				ed.onMouseUp.add(function(ed){var tag=ed.selection.getContent().replace(/^\s\s*/, '').replace(/\s\s*$/, '');var stem=tag.replace(/%%\S+%%/g,'');if (tag!=''&&stem=='') lookupentity(ed,'templatevar&templateid='+templateid+'&varkey='+encodeHTML(tag.replace(/%/g,'')),'Template Variables'); });
				// ed.onMouseUp.add(lookupselection(ed)); //uncomment to enable image class selector
	
			    ed.addButton('medialib',{title:'media library',image:'tiny_mce/icons/image.gif',onclick:function(){tinyMCE.activeEditor=ed;loadfs('Media Library','showmedialibrary');}});
			    ed.addButton('mediaselector',{title:'image selector',image:'tiny_mce/icons/image.gif',onclick:function(){
				    tinyMCE.activeEditor=ed;
				    loadfs('Image Selector','showmedialibrary&selector=1',
				    function(){},
				    function(){gid('fsview').sels=[]});
				}});
			    ed.addButton('systemplatevars',{title:'Template Variables',image:'tiny_mce/icons/magic.gif',onclick:function(){tinyMCE.activeEditor=ed;lookupentity(ed,'templatevar&templateid='+templateid,'Template Variables');}});
			    ed.addButton('styles',{title:'Styles',image:'tiny_mce/icons/brush.gif',onclick:function(){tinyMCE.activeEditor=ed;lookupentity(ed,'styles&mode=systemplate&id='+templateid,'Styles');}});
			    ed.addButton('imecree',{title:'Enter Cree Text',image:'tiny_mce/icons/cree.gif',onclick:function(){tinyMCE.activeEditor=ed;loadfs('Enter Cree Text','imecree',null,initimecree);}});
			    ed.addButton('sourceedit',{title:'Source Editor',image:'tiny_mce/icons/code.gif',onclick:function(){tinyMCE.activeEditor=ed;loadfs('Source Editor','mceeditsource',null,initsourceeditor);}});
			}
		});		
	
	});//xajxjs
}


showtemplate=function(templateid,name){
	addtab('template_'+templateid,name,'showtemplate&templateid='+templateid, function(){inittemplatetexteditor(templateid);});	
}

_inline_lookuptemplate=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('templatelist',document.appsettings.codepage+'?cmd=slv_core__templates&mode=embed&key='+encodeHTML(d.value)+soundex);
	},300
	);	
}


addtemplate=function(templatetypeid,gskey){
	var suffix='new';
	var otemplatename=gid('templatename_'+suffix);

	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(otemplatename)) {valid=0; offender=offender||otemplatename;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}

	var templatename=encodeHTML(otemplatename.value);
	
	var params=[];
	params.push('templatename='+templatename);

	
	reloadtab('template_new','','addtemplate&templatetypeid='+templatetypeid,function(req){
		var templateid=req.getResponseHeader('newrecid');
		refreshtab('templatetype_'+templatetypeid,1);		
		reloadview('core.templates','templatelist');
	},params.join('&'),null,gskey);
	
}

updatetemplate=function(templateid,templatetypeid,gskey){
	var suffix=templateid;
	var otemplatename=gid('templatename_'+suffix);
	var otemplatetext=gid('templatetext_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(otemplatename)) {valid=0; offender=offender||otemplatename;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}
	
	var templatename=encodeHTML(otemplatename.value);
	var templatetext=encodeHTML(tinyMCE.get('templatetext_'+suffix).getContent());
	var st=tinyMCE.get('templatetext_'+suffix).getBody().parentNode.scrollTop;
	if (st<tinyMCE.get('templatetext_'+suffix).getBody().scrollTop) st=tinyMCE.get('templatetext_'+suffix).getBody().scrollTop;
	
	var params=[];
	params.push('templatename='+templatename);
	params.push('templatetext='+templatetext);

	
	reloadtab('template_'+templateid,'','updatetemplate&templateid='+templateid,function(){
		ajxpgn('templatetypetemplates_'+templatetypeid,document.appsettings.codepage+'?cmd=listtemplatetypetemplates&templatetypeid='+templatetypeid);
		reloadview('core.templates','templatelist');
		inittemplatetexteditor(templateid,st);
		flashstatus('Updated '+otemplatename.value,5000);
	},params.join('&'),null,gskey);
	
}


deltemplate=function(templateid,templatetypeid,gskey){
	if (!sconfirm('Are you sure you want to remove this template?')) return;
	
	reloadtab('template_'+templateid,null,'deltemplate&templateid='+templateid,function(){
		closetab('template_'+templateid);
		refreshtab('templatetype_'+templatetypeid,1);		
		reloadview('core.templates','templatelist');
	},null,null,gskey);
}

updatetemplate_rectitle=function(templateid){
	var otitle=gid('dir_templatename_'+templateid);
	if (!valstr(otitle)) return;
	
	if (gid('templatename_'+templateid)) gid('templatename_'+templateid).value=otitle.value;
	
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=updatetemplate_rectitle&templateid='+templateid+'&templatename='+encodeHTML(otitle.value),0,0,null,function(rq){
		marktabsaved('template_'+templateid,rq.responseText);
		gid('vrectitle_templatename_'+templateid).style.display='inline';
		gid('mrectitle_templatename_'+templateid).style.display='none';
		var newtitle=rq.getResponseHeader('newtitle');
		if (newtitle==null||newtitle=='') newtitle=otitle.value; else newtitle=decodeHTML(newtitle);
		gid('vrectitle_templatename_'+templateid).innerHTML=newtitle+' <span class="edithover"></span>';
		settabtitle('template_'+templateid,newtitle);
	});
}


