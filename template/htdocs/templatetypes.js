showtemplatetype=function(templatetypeid,name){
	addtab('templatetype_'+templatetypeid,name,'showtemplatetype&templatetypeid='+templatetypeid);	
}

_inline_lookuptemplatetypelookup=function(d,templateid){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('templatetypelookuplist',document.appsettings.codepage+'?cmd=lookuptemplatevar&mode=embed&varkey='+encodeHTML(d.value)+'&templateid='+templateid);
	},300
	);	
}

_inline_lookuptemplatetype=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('templatetypelist',document.appsettings.codepage+'?cmd=slv_core__templatetypes&mode=embed&key='+encodeHTML(d.value)+soundex);
	},300
	);	
}


addtemplatetype=function(gskey){

	var suffix='new';
	var otemplatetypename=gid('templatetypename_'+suffix);
	var otemplatetypekey=gid('templatetypekey_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(otemplatetypename)) {valid=0; offender=offender||otemplatetypename;}
	if (!valstr(otemplatetypekey)) {valid=0; offender=offender||otemplatetypekey;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}

	var templatetypename=encodeHTML(otemplatetypename.value);
	var templatetypekey=encodeHTML(otemplatetypekey.value);
	
	var params=[];
	params.push('templatetypename='+templatetypename);
	params.push('templatetypekey='+templatetypekey);

	
	reloadtab('templatetype_new','','addtemplatetype',function(req){
		var templatetypeid=req.getResponseHeader('newrecid');		
		reloadview('core.templatetypes','templatetypelist');
	},params.join('&'),null,gskey);
	
}

updatetemplatetype=function(templatetypeid,gskey){
	var suffix=templatetypeid;
	var otemplatetypename=gid('templatetypename_'+suffix);
	var otemplatetypekey=gid('templatetypekey_'+suffix);
	var oactivetemplateid=gid('activetemplateid_'+suffix);
	var oplugins=gid('templatetypeplugins_'+suffix);
	var oclasses=gid('templatetypeclasses_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(otemplatetypename)) {valid=0; offender=offender||otemplatetypename;}
	if (!valstr(otemplatetypekey)) {valid=0; offender=offender||otemplatetypekey;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}
	
	var templatetypename=encodeHTML(otemplatetypename.value);
	var templatetypekey=encodeHTML(otemplatetypekey.value);
	var activetemplateid=oactivetemplateid.value2;
	if (!activetemplateid){
		if (oactivetemplateid.disabled) activetemplateid=0;
		else activetemplateid=-1;	
	}
	
	var plugins=encodeHTML(oplugins.value);
	var classes=encodeHTML(oclasses.value);
	
	var params=[];
	params.push('templatetypename='+templatetypename);
	params.push('templatetypekey='+templatetypekey);
	params.push('activetemplateid='+activetemplateid);
	params.push('templatetypeplugins='+plugins);
	params.push('templatetypeclasses='+classes);

	
	reloadtab('templatetype_'+templatetypeid,'','updatetemplatetype&templatetypeid='+templatetypeid,function(){
		reloadview('core.templatetypes','templatetypelist');
		flashstatus(document.dict['statusflash_updated']+otemplatetypename.value,5000);
	},params.join('&'),null,gskey);
	
}


deltemplatetype=function(templatetypeid,gskey){
	if (!sconfirm(document.dict['confirm_templatetype_delete'])) return;
	
	reloadtab('templatetype_'+templatetypeid,null,'deltemplatetype&templatetypeid='+templatetypeid,function(){
		closetabtree('templatetype_'+templatetypeid);
		reloadview('core.templatetypes','templatetypelist');
	},null,null,gskey);
}

addtemplatevar=function(templatetypeid,gskey){
	var ovarname=gid('templatevarname_'+templatetypeid);
	var ovardesc=gid('templatevardesc_'+templatetypeid);
	
	if (!valstr(ovarname)) return;
	if (!valstr(ovardesc)) return;
	
	var varname=encodeHTML(ovarname.value);
	var vardesc=encodeHTML(ovardesc.value);
	
	ajxpgn('templatetypetemplatevars_'+templatetypeid,document.appsettings.codepage+'?cmd=addtemplatevar&templatetypeid='+templatetypeid+'&varname='+varname+'&vardesc='+vardesc,0,0,null,function(){
		marktabsaved('templatetype_'+templatetypeid);		
	},null,1,gskey);
		
}

deltemplatevar=function(templatevarid,templatetypeid,gskey){
	if (!sconfirm('Are you sure you want to remove this variable?')) return;
		
	ajxpgn('templatetypetemplatevars_'+templatetypeid,document.appsettings.codepage+'?cmd=deltemplatevar&templatetypeid='+templatetypeid+'&templatevarid='+templatevarid,0,0,null,function(){
		marktabsaved('templatetype_'+templatetypeid);
	},null,null,gskey);
		
}

batchsavetemplatevars=function(templatetypeid,gskey){
	var quickvars=encodeHTML(gid('quickvars_'+templatetypeid).value);
	ajxpgn('templatetypetemplatevars_'+templatetypeid,document.appsettings.codepage+'?cmd=batchsavetemplatevars&templatetypeid='+templatetypeid,0,0,'quickvars='+quickvars,function(){
		marktabsaved('templatetype_'+templatetypeid);		
	},null,null,gskey);	
}

updatetemplatetype_rectitle=function(templatetypeid){
	var otitle=gid('dir_templatetypename_'+templatetypeid);
	if (!valstr(otitle)) return;
	
	if (gid('templatetypename_'+templatetypeid)) gid('templatetypename_'+templatetypeid).value=otitle.value;
	
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=updatetemplatetype_rectitle&templatetypeid='+templatetypeid+'&templatetypename='+encodeHTML(otitle.value),0,0,null,function(rq){
		marktabsaved('templatetype_'+templatetypeid,rq.responseText);
		gid('vrectitle_templatetypename_'+templatetypeid).style.display='inline';
		gid('mrectitle_templatetypename_'+templatetypeid).style.display='none';
		var newtitle=rq.getResponseHeader('newtitle');
		if (newtitle==null||newtitle=='') newtitle=otitle.value; else newtitle=decodeHTML(newtitle);
		gid('vrectitle_templatetypename_'+templatetypeid).innerHTML=newtitle+' <span class="edithover"></span>';
		settabtitle('templatetype_'+templatetypeid,newtitle);
	});
}
