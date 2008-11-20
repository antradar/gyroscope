_tenant_lookuptenant=function(){
	var search=gid('keytenant');
	var key=encodeHTML(search.value);
	
	if (search.timer!=null) {
		clearTimeout(search.timer);
		search.timer=null;
		if (search.reqobj!=null) search.reqobj.abort();
	}
	
	search.timer=setTimeout(function(){
		ajxpgn('tenantlist',document.appsettings.codepage+'?cmd=slv2&mode=lk&key='+key);
	},500);
}

deletecontact=function(pcid,pid){
	//confirm
	if (!confirm('Are you sure you want to delete this contact info?')) return;

	//update
	ajxb(document.appsettings.codepage+'?cmd=dct&pcid='+pcid);
	
	//reload
	ajxpgn('contactlist_'+pid,document.appsettings.codepage+'?cmd=lc&pid='+pid);
}

addcontact=function(pid){
	//get values
	var ctname=encodeHTML(gid('nctname_'+pid).value);
	var ctval=encodeHTML(gid('nctval_'+pid).value);

	//validate
	if (!valstr(gid('nctname_'+pid))) return;
	if (!valstr(gid('nctval_'+pid))) return;

	//update
	ajxb(document.appsettings.codepage+'?cmd=act&pid='+pid+'&ctname='+ctname+'&ctval='+ctval);
	//reload
	ajxpgn('contactlist_'+pid,document.appsettings.codepage+'?cmd=lc&pid='+pid);
}

addtenant=function(tnid){
	//get values
	var pfx='ntn';
	if (tnid!=null) pfx='tn'+tnid;

	var fname=encodeHTML(gid(pfx+'_fname').value);
	var lname=encodeHTML(gid(pfx+'_lname').value);
	var addr=encodeHTML(gid(pfx+'_addr').value);
	var city=encodeHTML(gid(pfx+'_city').value);
	var prov=encodeHTML(gid(pfx+'_prov').value);
	var country=encodeHTML(gid(pfx+'_country').value);
	var zip=encodeHTML(gid(pfx+'_zip').value);

	if (tnid==null){
	var phones=encodeHTML(gid(pfx+'_phones').value);
	var cells=encodeHTML(gid(pfx+'_cells').value);
	var emails=encodeHTML(gid(pfx+'_emails').value);	
	}

	//validate
	if (!valstr(gid(pfx+'_fname'))) return;
	if (!valstr(gid(pfx+'_lname'))) return;
	if (!valstr(gid(pfx+'_addr'))) return;
	if (!valstr(gid(pfx+'_city'))) return;
	if (!valstr(gid(pfx+'_prov'))) return;
	if (!valstr(gid(pfx+'_country'))) return;
	if (!valstr(gid(pfx+'_zip'))) return;

	//create record
	var params=document.appsettings.codepage+'?cmd=atn';
	if (tnid!=null) params=document.appsettings.codepage+'?cmd=utn';

	params+='&fname='+fname;
	params+='&lname='+lname;
	params+='&addr='+addr;
	params+='&city='+city;
	params+='&prov='+prov;
	params+='&country='+country;
	params+='&zip='+zip;


	var ntnid;
	
	if (tnid==null) {
		params+='&phones='+phones;
		params+='&cells='+cells;
		params+='&emails='+emails;
		
		ntnid=ajxb(params);
		//clear existing form
		closetab('new_tenant');
	
		//reload tenant list
		ajxpgn('lv2',document.appsettings.codepage+'?cmd=slv2');
		//display record
		addtab('tenant_'+ntnid,unescape(fname),'dt2&tnid='+ntnid);
	} else {
		params+='&tnid='+tnid;
		//reload tenant list
		ajxb(params);
		ajxpgn('lv2',document.appsettings.codepage+'?cmd=slv2');
		//display record
		reloadtab('tenant_'+tnid,unescape(fname),'dt2&tnid='+tnid);
	}

}

