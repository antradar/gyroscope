
picklookup=function(val,val2){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;

		if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';		
		if (document.hotspot.id) {
			var v2c=gid(document.hotspot.id+'_val2');
			if (v2c){
					gid(document.hotspot.id).disabled='disabled';
					v2c.innerHTML='<a class="labelbutton" onclick="cancelpickup(\''+document.hotspot.id+'\');">edit</a>';
			}
		}
	}
}

cancelpickup=function(c){
	if (gid(c)) {gid(c).disabled=''; gid(c).value='';gid(c).focus();}
	if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
	gid(c).value2=null;
}

picklookup3=function(val,val2,val3){
	if (document.hotspot){
		document.hotspot.value=val;
		document.hotspot.value2=val2;
		document.hotspot.value3=val3;

		if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';		
		if (document.hotspot.id) {
			var v2c=gid(document.hotspot.id+'_val2');
			if (v2c){
					gid(document.hotspot.id).disabled='disabled';
					v2c.innerHTML='<a class="labelbutton" onclick="cancelpickup(\''+document.hotspot.id+'\');">edit</a>';
			}
		}
		
	}
}


listlookup=function(d,title,command,mini){
	if (document.iphone_portrait) mini=1;
	if (mini&&d.id&&gid(d.id+'_lookup')){
		if (document.hotspot&&document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';
		gid(d.id+'_lookup').style.display='block';
		ajxpgn(d.id+'_lookup_view',document.appsettings.codepage+'?cmd='+command);	
		d.lookupview=gid(d.id+'_lookup');
		
		document.hotspot=d;
		return;	
	}

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

pickdate=function(d,def,mini){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	
	if (self.portrait_ignore&&!mini) portrait_ignore();
		
	listlookup(d,'Calendar','pkd&key='+key+'&mini='+(mini?'1':'0'),mini);
}

_pickdate=function(d,mini){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d,null,mini);
	}}
	d.timer=setTimeout(f(d),200);
}
