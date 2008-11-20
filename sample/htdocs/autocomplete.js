hashotspot=function(t){
	if (t) return (document.hotspot&&document.hotspot.attributes['hst']&&document.hotspot.attributes['hst'].value==t);
	else return document.hotspot;
}

picklookup=function(val,val2){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;
	}
}

picklookup3=function(val,val2,val3){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;
		document.hotspot.value3=val3;
	}
}

lookupcity=function(d){
	var key=encodeHTML(d.value);
	document.hotspot=d;
	gid('tooltitle').innerHTML='<a>Cities</a>';
	showview(2,true);
	ajxpgn('lv2',document.appsettings.codepage+'?cmd=lkcity&key='+key);
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
	showview(2,true);
	ajxpgn('lv2',document.appsettings.codepage+'?cmd=lkprov&key='+key);
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
	showview(2,true);
	ajxpgn('lv2',document.appsettings.codepage+'?cmd=pkd&key='+key);
}

_pickdate=function(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d);
	}}
	d.timer=setTimeout(f(d),200);
}
