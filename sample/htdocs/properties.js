// look up properties
_property_lookupproperty=function(){
  var search=gid('keyproperty');
  var key=encodeHTML(search.value);
  if (search.timer!=null) {
    clearTimeout(search.timer);
    search.timer=null;
    if (search.reqobj!=null) search.reqobj.abort();
  }
  search.timer=setTimeout(function(){
    ajxpgn('propertylist',document.appsettings.codepage+'?cmd=slv1&mode=lk&key='+key);
  },500);
}

// create or update property records
addproperty=function(llid,prid){
	//get values
	var pfx='npr';
	
	if (prid!=null) pfx='pr'+prid;
	
	var addr=encodeHTML(gid(pfx+'_addr_'+llid).value);
	var unit=encodeHTML(gid(pfx+'_unit_'+llid).value);
	
	var city=encodeHTML(gid(pfx+'_city_'+llid).value);
	var prov=encodeHTML(gid(pfx+'_prov_'+llid).value);
	var country=encodeHTML(gid(pfx+'_country_'+llid).value);
	var zip=encodeHTML(gid(pfx+'_zip_'+llid).value);
	var nrooms=encodeHTML(gid(pfx+'_nrooms_'+llid).value);
	var nparking=encodeHTML(gid(pfx+'_nparking_'+llid).value);
	var desc=gid(pfx+'_desc_'+llid).value;
	
	//validate
	if (!valstr(gid(pfx+'_addr_'+llid))) return;
	if (!valstr(gid(pfx+'_city_'+llid))) return;
	if (!valstr(gid(pfx+'_prov_'+llid))) return;
	if (!valstr(gid(pfx+'_country_'+llid))) return;
	if (!valstr(gid(pfx+'_zip_'+llid))) return;
	
	//create record
	var params=document.appsettings.codepage+'?cmd=apr';
	if (prid!=null) params=document.appsettings.codepage+'?cmd=upr';
	
	params+='&addr='+addr;
	params+='&unit='+unit;
	params+='&city='+city;
	params+='&prov='+prov;
	params+='&country='+country;
	params+='&zip='+zip;
	params+='&nrooms='+nrooms;
	params+='&nparking='+nparking;
	
	params+='&llid='+llid;
	
	var nprid;
	
	var rq=xmlHTTPRequestObject();
	
	if (prid==null) {
		rq.open('POST',params,true);
		rq.setRequestHeader('Content-Type','text/xml;charset=utf-8');
		rq.onreadystatechange=function(){
		if (rq.readyState==4){
			nprid=rq.responseText;
			//clear existing form
			closetab('new_property_'+llid);
			
			//reload property list
			ajxpgn('lv1',document.appsettings.codepage+'?cmd=slv1');
			//display record
			addtab('property_'+nprid,unescape(addr),'dt1&prid='+nprid);
			//update bridges
			if (gid('propertylist_'+llid)!=null){
				ajxpgn('propertylist_'+llid,document.appsettings.codepage+'?cmd=llpr&llid='+llid);
			}
		}//readyState
		}//rq func
		
		rq.send(desc);
	} else {
		params+='&prid='+prid;
		params+='&hb='+hb();
		//reload landlord list
		
		rq.open('POST',params,false);
		rq.setRequestHeader('Content-Type','text/xml;charset=utf-8');
		rq.send(desc);
		
		ajxpgn('lv1',document.appsettings.codepage+'?cmd=slv1');
		//display record
		reloadtab('property_'+prid,unescape(addr),'dt1&prid='+prid);
		
		if (gid('propertylist_'+llid)!=null){
			ajxpgn('propertylist_'+llid,document.appsettings.codepage+'?cmd=llpr&llid='+llid);
		}
	}
}

