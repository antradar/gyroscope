
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

toggle_bustype=function(d,p){
  if (d.checked) {
    gid(p+'_'+'tfname').innerHTML='Company:';
    gid(p+'_'+'tlname').innerHTML='B/N:';
    gid(p+'_'+'doby').style.display='none';
    gid(p+'_'+'dobm').style.display='none';
    gid(p+'_'+'dobd').style.display='none';
    gid(p+'_'+'dobt').style.display='none';
  } else {
    gid(p+'_'+'tfname').innerHTML='First name:';
    gid(p+'_'+'tlname').innerHTML='Last name:';
    gid(p+'_'+'doby').style.display='inline';
    gid(p+'_'+'dobm').style.display='inline';
    gid(p+'_'+'dobd').style.display='inline';
    gid(p+'_'+'dobt').style.display='inline';
  }
  
}

addlandlord=function(llid){
	//get values
	var pfx='nll';
	if (llid!=null) pfx='ll'+llid;

	var fname=encodeHTML(gid(pfx+'_fname').value);
	var lname=encodeHTML(gid(pfx+'_lname').value);
	var addr=encodeHTML(gid(pfx+'_addr').value);
	var city=encodeHTML(gid(pfx+'_city').value);
	var prov=encodeHTML(gid(pfx+'_prov').value);
	var country=encodeHTML(gid(pfx+'_country').value);
	var zip=encodeHTML(gid(pfx+'_zip').value);

	if (llid==null){
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
	var params=document.appsettings.codepage+'?cmd=al';
	if (llid!=null) params=document.appsettings.codepage+'?cmd=ul';

	params+='&fname='+fname;
	params+='&lname='+lname;
	params+='&addr='+addr;
	params+='&city='+city;
	params+='&prov='+prov;
	params+='&country='+country;
	params+='&zip='+zip;

	
	var nllid;

	if (llid==null) {
		params+='&phones='+phones;
		params+='&cells='+cells;
		params+='&emails='+emails;
		nllid=ajxb(params);
		//clear existing form
		closetab('new_landlord');
		
		//reload landlord list
		ajxpgn('lv0',document.appsettings.codepage+'?cmd=slv0');
		//display record
		addtab('landlord_'+nllid,unescape(fname),'dt0&llid='+nllid);
	} else {
		params+='&llid='+llid;
		//reload landlord list
		ajxb(params);
		ajxpgn('lv0',document.appsettings.codepage+'?cmd=slv0');
		//display record
		reloadtab('landlord_'+llid,unescape(fname),'dt0&llid='+llid);	
	}
}

