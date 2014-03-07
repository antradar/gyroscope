
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

listlookup=function(d,title,command){
	
	if (document.iphone_portrait&&!document.portraitlock){
		if (gid('rotate_indicator')){
			gid('rotate_indicator').style.display='block';
			setTimeout(function(){
				gid('rotate_indicator').style.display='none';
			},1000);	
		}
		return;	
	}
	
	document.hotspot=d;
	if (gid('lkv')){
		gid('lkvt').innerHTML=title;
		ajxpgn('lkvc',document.appsettings.codepage+'?cmd='+command,true,true,'<input style="position:absolute;top:-60px;left:0;" id="lvtab_lookup">',showlookup);
	} else {	
		var view;
		gid('tooltitle').innerHTML='<a>'+title+'</a>';
		if (document.viewindex!=null){
			stackview();
			view=document.appsettings.viewcount-1;
		} else {
			view=1;
			showview(1);
		}
		
		ajxpgn('lv'+view,document.appsettings.codepage+'?cmd='+command,true,true,'<input style="position:absolute;top:-60px;left:0;" id="lvtab_'+view+'">');
		
	}	
		
}

pickdate=function(d,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	
	if (self.portrait_ignore) portrait_ignore();
		
	listlookup(d,'Calendar','pkd&key='+key);
}

_pickdate=function(d){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d);
	}}
	d.timer=setTimeout(f(d),200);
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

