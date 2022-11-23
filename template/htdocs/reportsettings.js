showreportsetting=function(reportid,name){
	addtab('reportsetting_'+reportid,'<img src="imgs/t.gif" class="ico-setting">'+name,'showreportsetting&reportid='+reportid);	
}

_inline_lookupreportsetting=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';

	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
			
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('reportsettinglist',document.appsettings.codepage+'?cmd=slv_core__reportsettings&mode=embed&key='+encodeHTML(d.value)+soundex);
	},200
	);	
}


addreportsetting=function(gskey){

	var suffix='new';
	var oreportname=gid('reportname_'+suffix);
	var oreportgroup=gid('reportgroup_'+suffix);
	var oreportfunc=gid('reportfunc_'+suffix);
	var oreportkey=gid('reportkey_'+suffix);
	var oreportdesc=gid('reportdesc_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(oreportname)) {valid=0; offender=offender||oreportname;}
	if (!valstr(oreportkey)) {valid=0; offender=offender||oreportkey;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}

	var reportname=encodeHTML(oreportname.value);
	var reportgroup=encodeHTML(oreportgroup.value);
	var reportfunc=encodeHTML(oreportfunc.value);	
	var reportkey=encodeHTML(oreportkey.value);
	var reportdesc=encodeHTML(oreportdesc.value);
	
	var params=[];
	params.push('reportname='+reportname);
	params.push('reportgroup='+reportgroup);
	params.push('reportfunc='+reportfunc);
	params.push('reportkey='+reportkey);
	params.push('reportdesc='+reportdesc);

	
	reloadtab('reportsetting_new','','addreportsetting',function(req){
		var reportid=req.getResponseHeader('newrecid');		
		reloadview('core.reportsettings','reportsettinglist');
	},params.join('&'),null,gskey);
	
}

updatereportsetting=function(reportid,roles,gskey){
	var suffix=reportid;
	var oreportname=gid('reportname_'+suffix);
	var oreportgroup=gid('reportgroup_'+suffix);
	var oreportfunc=gid('reportfunc_'+suffix);
	var oreportkey=gid('reportkey_'+suffix);
	var oreportdesc=gid('reportdesc_'+suffix);

	
	var valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(oreportname)) {valid=0; offender=offender||oreportname;}
	if (!valstr(oreportkey)) {valid=0; offender=offender||oreportkey;}

	//add more validation rules
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}
	
	var reportname=encodeHTML(oreportname.value);
	var reportgroup=encodeHTML(oreportgroup.value);
	var reportfunc=encodeHTML(oreportfunc.value);
	var reportkey=encodeHTML(oreportkey.value);
	var reportdesc=encodeHTML(oreportdesc.value);
	
	var reportgroupnames=[];
	for (var i=0;i<roles.length;i++){
		var role=roles[i];
		if (gid('reportrole_'+role+'_'+reportid).checked) reportgroupnames.push(role);
	}
	
	reportgroupnames=reportgroupnames.join('|');
	
	var params=[];
	params.push('reportname='+reportname);
	params.push('reportgroup='+reportgroup);
	params.push('reportfunc='+reportfunc);
	params.push('reportkey='+reportkey);
	params.push('reportdesc='+reportdesc);
	params.push('reportgroupnames='+reportgroupnames);

	
	reloadtab('reportsetting_'+reportid,'','updatereportsetting&reportid='+reportid,function(){
		reloadview('core.reportsettings','reportsettinglist');
		flashstatus(document.dict['statusflash_updated']+oreportname.value,5000);
	},params.join('&'),null,gskey);
	
}


delreportsetting=function(reportid,gskey){
	if (!sconfirm(document.dict['confirm_reportsetting_delete'])) return;
	
	reloadtab('reportsetting_'+reportid,null,'delreportsetting&reportid='+reportid,function(){
		closetab('reportsetting_'+reportid);
		reloadview('core.reportsettings','reportsettinglist');
	},null,null,gskey);
}
