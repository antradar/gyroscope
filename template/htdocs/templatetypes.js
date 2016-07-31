showtemplatetype=function(templatetypeid,name){
	addtab('templatetype_'+templatetypeid,name,'showtemplatetype&templatetypeid='+templatetypeid);	
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


addtemplatetype=function(){

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

	
	reloadtab('templatetype_new',otemplatetypename.value,'addtemplatetype',function(req){
		var templatetypeid=req.getResponseHeader('newrecid');		
		reloadview('core.templatetypes','templatetypelist');
	},params.join('&'));
	
}

updatetemplatetype=function(templatetypeid){
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

	
	reloadtab('templatetype_'+templatetypeid,otemplatetypename.value,'updatetemplatetype&templatetypeid='+templatetypeid,function(){
		reloadview('core.templatetypes','templatetypelist');
		flashstatus(document.dict['statusflash_updated']+otemplatetypename.value,5000);
	},params.join('&'));
	
}


deltemplatetype=function(templatetypeid){
	if (!confirm(document.dict['confirm_templatetype_delete'])) return;
	
	reloadtab('templatetype_'+templatetypeid,null,'deltemplatetype&templatetypeid='+templatetypeid,function(){
		closetabtree('templatetype_'+templatetypeid);
		reloadview('core.templatetypes','templatetypelist');
	});
}

addtemplatevar=function(templatetypeid){
	var ovarname=gid('templatevarname_'+templatetypeid);
	var ovardesc=gid('templatevardesc_'+templatetypeid);
	
	if (!valstr(ovarname)) return;
	if (!valstr(ovardesc)) return;
	
	var varname=encodeHTML(ovarname.value);
	var vardesc=encodeHTML(ovardesc.value);
	
	ajxpgn('templatetypetemplatevars_'+templatetypeid,document.appsettings.codepage+'?cmd=addtemplatevar&templatetypeid='+templatetypeid+'&varname='+varname+'&vardesc='+vardesc);
		
}

deltemplatevar=function(templatevarid,templatetypeid){
	if (!confirm('Are you sure you want to remove this variable?')) return;
		
	ajxpgn('templatetypetemplatevars_'+templatetypeid,document.appsettings.codepage+'?cmd=deltemplatevar&templatetypeid='+templatetypeid+'&templatevarid='+templatevarid);
		
}