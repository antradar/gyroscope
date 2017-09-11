setaccountpass=function(){
	var ooldpass=gid('accountpass');
	var opass1=gid('accountpass1');
	var opass2=gid('accountpass2');
	
	if (!valstr(ooldpass)) return;
	if (!valstr(opass1)) return;
	if (!valstr(opass2)) return;

	var oldpass=encodeHTML(ooldpass.value);
	var pass1=encodeHTML(opass1.value);
	var pass2=encodeHTML(opass2.value);
	
	var needkeyfile=0;
	if (gid('myaccount_needkeyfile').checked) needkeyfile=1;

	if (pass1!=pass2){
		salert(document.dict['mismatching_password']);
		return;
	}
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.fastlane+'?cmd=setaccount&needkeyfile='+needkeyfile,true);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			salert(rq.responseText);	
		}	
	}
	
	rq.send('oldpass='+oldpass+'&pass='+pass1);
}

trackkeyfilepad=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	var pad=gid('keyfilepad_'+container);
	var w=pad.offsetWidth;
	var h=pad.offsetHeight;
	var box=gid('keyfilebox_'+container);
	
	if (box.stopped) {
		d.onmousemove=null;
		return;
	}
	
	if (!d.inited){
		d.inited=1;
		d.lastx=0; d.lasty=0;
		box.style.left='100px';
		box.idx=1;
		
		d.onmousemove=function(e){
			var x,y; if (e){x=e.clientX; y=e.clientY;} else {x=event.clientX;y=event.clientY;}
			if (Math.abs(d.lastx-x)>5&&Math.abs(d.lasty-y)>5){
				 ta.value=ta.value+x+','+y+',';
				 d.lastx=x;
				 d.lasty=y;
			}//abs
		}	
	}
}

keyfileboxover=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	if (d.stopped) {
		d.onmouseout=null;
		return;
	}
	
	d.style.backgroundColor='#ffab00';
	d.onmouseout=function(e){
		d.style.backgroundColor='#848cf7';
			var x,y; if (e){x=e.clientX; y=e.clientY;} else {x=event.clientX;y=event.clientY;}
			ta.value=ta.value+'H'+hb()+'-'+x+','+y+',';			
	}
}

keyfileboxclick=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	var pad=gid('keyfilepad_'+container);
	var w=pad.offsetWidth;
	var h=pad.offsetHeight;
	
	if (d.stopped) return;
	
	if (d.idx==null) d.idx=1;
	d.idx++;
	
	if (d.idx>5){
		pad.style.backgroundColor='#efffef';
		d.stopped=1;
		gid('keyfilepadview_'+container).style.display='none';
		gid('keyfiledownloader_'+container).style.display='block';
		return;
	}
	
	ta.value=ta.value+'X'+hb();
		
	d.style.backgroundColor='#848cf7';
	d.innerHTML=d.idx;
	
	d.style.left=Math.floor(Math.random()*(w-40))+'px';
	d.style.top=Math.floor(Math.random()*(h-40))+'px';
		
}