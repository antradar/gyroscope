
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
		if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
		hidelookup();
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
		if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
		hidelookup();
	}
}


listlookup=function(d,title,command,mini){
	if (document.iphone_portrait) mini=1;
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
		ajxpgn('lkvc',document.appsettings.codepage+'?cmd='+command,true,true,'',showlookup);
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

pickdate=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;

	if (!opts) opts={mini:0}
	if (!opts.mini) opts.mini=0;	

	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
		
	listlookup(d,'Calendar','pkd&key='+key+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_pickdate=function(d,opts){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdate(d,null,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

pickdatetime=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;
	
	if (!opts) opts={start:8,end:22,mini:null}
	if (!opts.mini) opts.mini=null;

	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
	
	listlookup(d,'Calendar','pkd&mode=datetime&key='+key+'&hstart='+opts.start+'&hend='+opts.end+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_pickdatetime=function(d,opts,def){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		pickdatetime(d,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

picktime=function(d,opts,def){
	var key='';
	if (d) key=encodeHTML(d.value);
	else key=def;

	if (!opts) opts={start:8,end:22,mini:null}
	if (!opts.mini) opts.mini=null;

	if (self.portrait_ignore) portrait_ignore();
	
	listlookup(d,'Calendar','pkd&mode=datetime&nodate=1&key='+key+'&hstart='+opts.start+'&hend='+opts.end+'&mini='+(opts.mini?'1':'0'),opts.mini);
}

_picktime=function(d,opts,def){
	if (d.timer) clearTimeout(d.timer);
	var f=function(d){return function(){
		picktime(d,null,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
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

// svn merge boundary 80dd22a0883aaa1f8cd09b09e81bdd9b - 


// svn merge boundary bed99e5db57749f375e738c1c0258047 - 


// svn merge boundary 182eb2eb0c3b7d16cf92c0972fe64bcc - 


// svn merge boundary 4d373b247a04253ee05a972964f7a7f3 -

