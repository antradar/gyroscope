inittemplatetexteditor=function(templateid){
	plugins=gid('templateplugins_'+templateid).value;
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins: 'paste, advimage',
		theme_advanced_buttons1:"bold,italic,underline,strikethrough,|,forecolor,backcolor,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink",
		//theme_advanced_buttons2:"fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull,|,code",
		theme_advanced_buttons2:plugins+",systemplatevars,|,code",
		editor_selector:'templatetexteditor_'+templateid,
		extended_valid_elements : 'img[class|ampwidth|ampheight|src]',
		paste_preprocess:function(pl,o){paste_clean_image(o);},		
		height:300,
		content_css:'tiny_mce/editor.css?v='+hb(),
	    setup: function(ed) {
		    ed.addButton('medialib',{title:'media library',image:'tiny_mce/icons/image.gif',onclick:function(){ loadfs('Media Library','showmedialibrary');}});
		    ed.addButton('systemplatevars',{title:'Template Variables',image:'tiny_mce/icons/magic.gif',onclick:function(){lookupentity(ed,'templatevar&templateid='+templateid,'Template Variables');}});
		    ed.addButton('styles',{title:'Styles',image:'tiny_mce/icons/brush.gif',onclick:function(){lookupentity(ed,'styles&mode=systemplate&id='+templateid,'Styles');}});
		}
	});		
}


showtemplate=function(templateid,name,templatetypeid){
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


addtemplate=function(templatetypeid){
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

	
	reloadtab('template_new',otemplatename.value,'addtemplate&templatetypeid='+templatetypeid,function(req){
		var templateid=req.getResponseHeader('newrecid');		
		ajxpgn('templatetypetemplates_'+templatetypeid,document.appsettings.codepage+'?cmd=listtemplatetypetemplates&templatetypeid='+templatetypeid);
		reloadview('core.templates','templatelist');
	},params.join('&'));
	
}

updatetemplate=function(templateid,templatetypeid){
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
	
	var params=[];
	params.push('templatename='+templatename);
	params.push('templatetext='+templatetext);

	
	reloadtab('template_'+templateid,otemplatename.value,'updatetemplate&templateid='+templateid,function(){
		ajxpgn('templatetypetemplates_'+templatetypeid,document.appsettings.codepage+'?cmd=listtemplatetypetemplates&templatetypeid='+templatetypeid);
		reloadview('core.templates','templatelist');
		inittemplatetexteditor(templateid);
		flashstatus('Updated '+otemplatename.value,5000);
	},params.join('&'));
	
}


deltemplate=function(templateid,templatetypeid){
	if (!confirm('Are you sure you want to remove this template?')) return;
	
	reloadtab('template_'+templateid,null,'deltemplate&templateid='+templateid,function(){
		closetab('template_'+templateid);
		ajxpgn('templatetypetemplates_'+templatetypeid,document.appsettings.codepage+'?cmd=listtemplatetypetemplates&templatetypeid='+templatetypeid);		
		reloadview('core.templates','templatelist');
	});
}
