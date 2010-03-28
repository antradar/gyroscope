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
	listlookup(d,'City Lookup','lkcity&key='+key);
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
	listlookup(d,'Province Lookup','lkprov&key='+key);
}


function _lookupprov(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		lookupprov(d);
	}}
	d.timer=setTimeout(f(d),200);  
}

listlookup=function(d,title,command){
	
	document.hotspot=d;
	gid('tooltitle').innerHTML='<a>'+title+'</a>';
	var view;
	if (document.viewindex!=null){
		stackview();
		view=document.appsettings.viewcount-1;
	} else {
		view=1;
		showview(1);
	}
	ajxpgn('lv'+view,document.appsettings.codepage+'?cmd='+command);
		
}

pickdate=function(d,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	listlookup(d,'Calendar','pkd&key='+key);
}

_pickdate=function(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d);
	}}
	d.timer=setTimeout(f(d),200);
}
