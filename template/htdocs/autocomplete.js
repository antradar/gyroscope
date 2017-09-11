picklookup=function(val,val2){
	if (document.hotspot){
		if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);
		document.hotspot.value=val;
		document.hotspot.value2=val2;

		if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';		
		if (document.hotspot.id) {
			var v2c=gid(document.hotspot.id+'_val2');
			if (v2c){
					gid(document.hotspot.id).disabled='disabled';
					v2c.innerHTML='<a class="labelbutton" href=# onclick="cancelpickup(\''+document.hotspot.id+'\');return false;">'+document.dict['edit']+'</a>';
			}
		}
		if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
		hidelookup();
		if (document.hotspot.onchange) document.hotspot.onchange();
	}
}

selectpickup=function(sf,title){
	if (!document.hotspot) return;
	if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);

	var d=document.hotspot;
	
	sf.seltitle=title;
	
	var sels=[]
	var os=gid('lkvc').getElementsByTagName('input');
	
	if (document.iphone_portrait&&d.id&&gid(d.id+'_lookup')) os=gid(d.id+'_lookup').getElementsByTagName('input');
	
	var dtitle='';
	for (var i=0;i<os.length;i++) if (os[i].className=='lksel'&&os[i].checked) {sels.push(os[i].value);dtitle=os[i].seltitle;}
	
	if (sels.length==0) {cancelpickup(d.id,true);return;}
		
	if (sels.length==1) d.value=dtitle; else d.value='('+sels.length+' items selected)';
	d.value2=sels.join(',');
	
	if (document.hotspot.id) {
		var v2c=gid(document.hotspot.id+'_val2');
		if (v2c){
				gid(document.hotspot.id).disabled='disabled';
				v2c.innerHTML='<a class="labelbutton" href=# onclick="cancelpickup(\''+document.hotspot.id+'\');return false;">'+document.dict['edit']+'</a>';
		}
	}
		
	if (d.onchage) d.onchange();
}

pickupalllookups=function(sf){
	if (!document.hotspot) return;
	if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);

	var d=document.hotspot;
		
	var sels=[]
	var os=gid('lkvc').getElementsByTagName('input');
	
	if (document.iphone_portrait&&d.id&&gid(d.id+'_lookup')) os=gid(d.id+'_lookup').getElementsByTagName('input');
	
	var dtitle='';
	
	if (!sf.allchecked){
		for (var i=0;i<os.length;i++) if (os[i].className=='lksel') {os[i].checked='checked';sels.push(os[i].value);dtitle=os[i].seltitle;}
		sf.allchecked=true;
		sf.innerHTML='unselect all items';
	} else {
		sf.allchecked=null;
		for (var i=0;i<os.length;i++) if (os[i].className=='lksel') {os[i].checked='';}
		sf.innerHTML='select all items';
	}
	
	if (sels.length==0) {cancelpickup(d.id,true);return;}
	
	
	d.value='('+sels.length+' items selected)';
	d.value2=sels.join(',');
	
	if (document.hotspot.id) {
		var v2c=gid(document.hotspot.id+'_val2');
		if (v2c){
				gid(document.hotspot.id).disabled='disabled';
				v2c.innerHTML='<a class="labelbutton" style="color:#ffffff;" onclick="cancelpickup(\''+document.hotspot.id+'\');">'+document.dict['edit']+'</a>';
		}
	}
		
	if (d.onchage) d.onchange();
}


cancelpickup=function(c,unlockonly){
	if (unlockonly) {
		gid(c).disabled='';
		gid(c).value='';
		gid(c).value2=null;
		gid(c).value3=null;
		return;	
	}
	
	if (gid(c)) {gid(c).disabled=''; gid(c).value='';gid(c).focus();}
	if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
	gid(c).value2=null;
	gid(c).value3=null;
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();
}

picklookup3=function(val,val2,val3){
	if (document.hotspot){
		if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);
		document.hotspot.value=val;
		document.hotspot.value2=val2;
		document.hotspot.value3=val3;

		if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';		
		if (document.hotspot.id) {
			var v2c=gid(document.hotspot.id+'_val2');
			if (v2c){
					gid(document.hotspot.id).disabled='disabled';
					v2c.innerHTML='<a class="labelbutton" onclick="cancelpickup(\''+document.hotspot.id+'\');">'+document.dict['edit']+'</a>';
			}
		}
		if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
		hidelookup();
		if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
		if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();
	}
}


listlookup=function(d,title,command,mini){
	if (document.iphone_portrait) mini=1;
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&!d) d=document.hotspot;
	if (mini&&!d) return;
	
	if (mini&&d.id&&gid(d.id+'_lookup')){
		if (document.hotspot&&gid(document.hotspot.id)) gid(document.hotspot.id+'_lookup').style.display='none';
		if (document.hotspot&&document.hotspot.lookupview) {
			document.hotspot.lookupview.style.display='none';
			document.hotspot.lookupview.innerHTML='';
		}
		gid(d.id+'_lookup').style.display='block';
		gid(d.id+'_lookup_view').style.display='block';
		ajxpgn(d.id+'_lookup_view',document.appsettings.codepage+'?cmd='+command);	
		d.lookupview=gid(d.id+'_lookup_view');
		
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
		gid('lkvc').innerHTML='';
		showlookup();
		ajxpgn('lkvc',document.appsettings.codepage+'?cmd='+command);
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
		
		ajxpgn('lv'+view,document.appsettings.codepage+'?cmd='+command,true,true,'');
		
	}	
	
		
}

showrelrec=function(id,showfunc,defid){
	var d=gid(id);
	if (d.disabled) showfunc(d.value2?d.value2:defid,d.value);
}

pickmonth=function(d,defyear){
	if (!defyear) defyear=d.value;
	listlookup(d,'Calendar','pickdatemonths&defyear='+defyear+'&mode=dir');	
}

pickdate=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;

	if (!opts) opts={mini:0}
	if (!opts.mini) opts.mini=0;
	if (!opts.tz) opts.tz='';
	
	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
		
	listlookup(d,'Calendar','pkd&key='+key+'&tz='+opts.tz+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_pickdate=function(d,opts){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d,opts){return function(){
		pickdate(d,opts,null);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

pickdatetime=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	
	if (!opts) opts={start:8,end:22,mini:null}
	if (!opts.mini) opts.mini=null;
	if (!opts.tz) opts.tz='';

	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
	
	listlookup(d,'Calendar','pkd&mode=datetime&key='+key+'&hstart='+opts.start+'&hend='+opts.end+'&tz='+opts.tz+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_pickdatetime=function(d,opts,def){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d,opts){return function(){
		pickdatetime(d,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

picktime=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;

	if (!opts) opts={start:8,end:22,y:0,m:0,d:0,mini:null}
	if (!opts.mini) opts.mini=null;

	if (self.portrait_ignore) portrait_ignore();
	
	listlookup(d,'Calendar','pkd&mode=datetime&nodate=1&key='+key+'&hstart='+opts.start+'&hend='+opts.end+'&tz='+opts.tz+'&y='+opts.y+'&m='+opts.m+'&d='+opts.d+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_picktime=function(d,opts,def){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d,opts){return function(){
		picktime(d,null,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

lookupentity=function(d,entity,title){
	if (!d.value) d.value='';
	if (d.disabled) return;
	listlookup(d,title,'lookup'+entity+'&key='+encodeHTML(d.value));	
}

_lookupentity=function(d,entity,title){
	if (d.disabled) return;
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookupentity(d,entity,title);
	},200);
}

inpbackspace=function(id){
	var d=gid(id);
	if (!d) return;
	if (d.value=='') return;
	
	var parts=d.value.trim().split(' ');
	var nparts=[];
	for (var i=0;i<parts.length-1;i++){
		nparts.push(parts[i]);
			
	}
	d.value=nparts.join(' ');
	d.focus();
	
}

//hook this event on textarea::onfocus
filterkeys=function(d){
	if (d.onkeydown!=null) return;
		
	d.onkeydown=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;
		if (keycode==9) {
			var start=d.selectionStart;
			var end=d.selectionEnd;
			if (start==null){
				if (document.selection){
					var r=document.selection.createRange();
					if (r==null) return 0;
					var re = d.createTextRange();
					var rc = re.duplicate();
					re.moveToBookmark(r.getBookmark());
					rc.setEndPoint('EndToStart',re);
					start=rc.text.length;
					var lastchar=d.value.substring(start,start+1).replace(/\s/g,'');
					if (lastchar=='') start=start+2;
					end=start;
				}
			}
						
			if (start!=null){
				var val=d.value;
				d.value=val.substring(0,start)+"\t"+val.substring(end);
			}
			
			d.focus();
			if (d.selectionStart) d.setSelectionRange(start+1,start+1);
			return false;	
		}
	}	
}

function nav_setfilter(container,keyid,cmd,filter){
	ajxpgn(container,document.appsettings.codepage+'?cmd='+cmd+'&mode=embed&key='+encodeHTML(gid(keyid).value)+filter);	
}


