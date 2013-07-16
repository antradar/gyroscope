deltenantfromlease=function(lstnid,lsid,prid){
	if (!confirm('are you sure to remove this tenant from the lease?')) return;

	ajxb(document.appsettings.codepage+'?cmd=dlstn&lstnid='+lstnid);
	
	var listid='ls'+lsid+'_tenantlist_'+prid;
	
	//reload lease-tenant list
	ajxpgn(listid,document.appsettings.codepage+'?cmd=llstn&lsid='+lsid+'&prid='+prid);
	
	var list=gid(listid);
	var tnids=list.getElementsByTagName('span');
	var i;
	for (i=0;i<tnids.length;i++){
		var o=tnids[i];
		var tnid=o.attributes.tnid.value;
		if (gid('tenantleases_'+tnid)!=null) ajxpgn('tenantleases_'+tnid,document.appsettings.codepage+'?cmd=ltnls&tnid='+tnid);
	}
}

addtenanttolease=function(lsid,prid){
	var listid='ls'+lsid+'_tenantlist_'+prid;
	
	var tenant=gid('ls'+lsid+'_newitem_'+prid);
	
	if (tenant.value2==null) return;
	if (tenant.value2=='') return;
	
	var tnid=document.hotspot.value2;
	ajxb(document.appsettings.codepage+'?cmd=alstn&lsid='+lsid+'&tnid='+tnid);
	
	//reload lease-tenant list
	ajxpgn(listid,document.appsettings.codepage+'?cmd=llstn&lsid='+lsid+'&prid='+prid);
	
	//reload all tenant-lease lists
	var list=gid(listid);
	var tnids=list.getElementsByTagName('span');
	var i;
	for (i=0;i<tnids.length;i++){
		var o=tnids[i];
		var tnid2=o.attributes.tnid.value;
		if (gid('tenantleases_'+tnid2)!=null) ajxpgn('tenantleases_'+tnid2,document.appsettings.codepage+'?cmd=ltnls&tnid='+tnid2);
	}
	
	//also reload the current tenant-lease list
	if (gid('tenantleases_'+tnid)!=null) ajxpgn('tenantleases_'+tnid,document.appsettings.codepage+'?cmd=ltnls&tnid='+tnid);
}

lookuplease=function(){
  var search=gid('keylease');
  var key=encodeHTML(search.value);
  if (search.timer!=null) {
    clearTimeout(search.timer);
    search.timer=null;
    if (search.reqobj!=null) search.reqobj.abort();
  }
  search.timer=setTimeout(function(){
    ajxpgn('leaselist',document.appsettings.codepage+'?cmd=slv3&mode=lk&key='+key);
  },500);
}

addlease=function(prid,lsid){
	//get values
	var pfx='nls';
	if (lsid!=null) pfx='ls'+lsid;
	
	var mrent=gid(pfx+'_mrent_'+prid).value;
	var deposit=gid(pfx+'_deposit_'+prid).value;
	
	var datea=gid(pfx+'_datea_'+prid).value;
	var dateb=gid(pfx+'_dateb_'+prid).value;
	
	//validate dates
	if (!valdate(gid(pfx+'_datea_'+prid))) return;
	if (!valdate(gid(pfx+'_dateb_'+prid))) return;
	
	//validate rents
	if (!valfloat(gid(pfx+'_mrent_'+prid))) return;
	if (!valfloat(gid(pfx+'_deposit_'+prid))) return;
	
	//create record
	var params=document.appsettings.codepage+'?cmd=als';
	if (lsid!=null) params=document.appsettings.codepage+'?cmd=uls';
	
	params+='&mrent='+mrent;
	params+='&deposit='+deposit;
	
	params+='&datea='+datea;
	params+='&dateb='+dateb;
	params+='&prid='+prid;
	
	var nlsid;
	
	if (lsid==null) {
		nlsid=ajxb(params);
		//clear existing form
		closetab('new_lease_'+prid);
		//reload lease list
		ajxpgn('lv3',document.appsettings.codepage+'?cmd=slv3');
		//display record
		addtab('lease_'+nlsid,'Lease #'+nlsid,'dt3&lsid='+nlsid);
	} else {
		params+='&lsid='+lsid;
		//reload landlord list
		ajxb(params);
		ajxpgn('lv3',document.appsettings.codepage+'?cmd=slv3');
		//display record
		reloadtab('lease_'+lsid,'Lease #'+lsid,'dt3&lsid='+lsid);	
	}
	
	//update property leases
	if (gid('propertyleases_'+prid)!=null) ajxpgn('propertyleases_'+prid,document.appsettings.codepage+'?cmd=prls&prid='+prid);
	
	//update tenant leases
	if (lsid!=null){//scan id list	
		var listid='ls'+lsid+'_tenantlist_'+prid;
		
		//reload lease-tenant list
		ajxpgn(listid,document.appsettings.codepage+'?cmd=llstn&lsid='+lsid+'&prid='+prid);
		
		var list=gid(listid);
		var tnids=list.getElementsByTagName('span');
		var i;
		for (i=0;i<tnids.length;i++){
			var o=tnids[i];
			if (!o.attributes||!o.attributes.tnid) continue;
			var tnid=o.attributes.tnid.value;
			if (gid('tenantleases_'+tnid)!=null) ajxpgn('tenantleases_'+tnid,document.appsettings.codepage+'?cmd=ltnls&tnid='+tnid);
		}	  
	}//tenant leases update
}

lookuptenant=function(d){
	listlookup(d,'Tenant Search','lktn&key='+encodeHTML(d.value));		
}

_lookuptenant=function(d){
  d.value2=null;

  if (d.timer) clearTimeout(d.timer);
  var f=function(d){
    return function(){
      lookuptenant(d);
    }
  }
  d.timer=setTimeout(f(d),200);
}
