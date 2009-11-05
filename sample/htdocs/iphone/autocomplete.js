hashotspot=function(t){
	if (t) return (document.hotspot&&document.hotspot.attributes['hst']&&document.hotspot.attributes['hst'].value==t);
	else return document.hotspot;
}

picklookup=function(val,val2){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;
	}
	showpanel(2);
}

picklookup3=function(val,val2,val3){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;
		document.hotspot.value3=val3;
	}
	showpanel(2);

}

lookupcity=function(d){
	var key=encodeHTML(d.value);
	document.hotspot=d;
	gid('tooltitle').innerHTML='<a>Cities</a>';
	//showview(2,true);
	ajxpgn('views',document.appsettings.codepage+'?cmd=lkcity&key='+key);
}


function _lookupcity(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		lookupcity(d);
	}}
	d.timer=setTimeout(f(d),200);  
}


lookupprov=function(d){
	var key=encodeHTML(d.value);
	document.hotspot=d;
	gid('tooltitle').innerHTML='<a>Provinces</a>';
	//showview(2,true);
	ajxpgn('views',document.appsettings.codepage+'?cmd=lkprov&key='+key);
}


function _lookupprov(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		lookupprov(d);
	}}
	d.timer=setTimeout(f(d),200);  
}

pickdate=function(d,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	
	document.hotspot=d;
	gid('tooltitle').innerHTML='<a>Calendar</a>';
	//showview(2,true);
	ajxpgn('views',document.appsettings.codepage+'?cmd=pkd&key='+key);
}

_pickdate=function(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d);
	}}
	d.timer=setTimeout(f(d),200);
}

//show the calendar without erasing the entire left panel
picklocaldate=function(did,d,def){
	var key='';
	if (d) key=encodeHTML(d.value);
		else key=def;
	document.hotspot=d;
	ajxpgn(did,document.appsettings.codepage+'?cmd=pkd&key='+key+'&did='+did);
}

_picklocaldate=function(did,d){

	if (d.timer) clearTimeout(d.timer);
	var f=function(did,d){
		return function(){
		  picklocaldate(did,d);
		}
	}
  d.timer=setTimeout(f(did,d),200);
}
