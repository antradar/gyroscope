autopickentity=function(d,entity){
	if (d.disabled||d.value=='') return;
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=autopick'+entity+'&key='+encodeHTML(d.value),0,0,null,function(rq){
		var recid=rq.getResponseHeader('recid');
		var recid3=rq.getResponseHeader('recid3');
		var recid4=rq.getResponseHeader('recid4');
		if (recid!=null&&parseInt(recid,10)>0){
			var recname=decodeHTML(rq.getResponseHeader('recname'));
			picklookup(recname,recid,recid3,recid4);
		}
	});	
}

picklookup=function(val){
	if (document.hotspot==null) return;
	
	var args=arguments;

	if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);
		
	for (var i=0;i<args.length;i++){
		if (i==0) continue;
		document.hotspot['value'+(i+1)]=args[i];		
	}
	document.hotspot['valuecount']=args.length-1;
		
	document.hotspot.value=val;

	if (document.hotspot.lookupview) document.hotspot.lookupview.style.display='none';		
	if (document.hotspot.id) {
		var v2c=gid(document.hotspot.id+'_val2');
		if (v2c&&document.hotspot.value2!=null){
				gid(document.hotspot.id).disabled='disabled';
				v2c.innerHTML='<a class="labelbutton" href=# onclick="cancelpickup(\''+document.hotspot.id+'\');return false;">'+document.dict['edit']+'</a>';
		}
	}
	if (gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
	if (document.hotspot.attributes['keeplookup']==null) hidelookup(true); //place "keeplookup" on the trigger
	if (document.hotspot.onchange) document.hotspot.onchange();
	
	if (document.hotspot){
		document.onclick=document.hotspot.lastonclick;
		document.hotspot.lastonclick=null;
	}		
}

selectpickup=function(sf,title){
	if (!document.hotspot) return;
	if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	
	if (document.hotspot){
		document.onclick=document.hotspot.lastonclick;
		document.hotspot.lastonclick=null;
	}
	
	var d=document.hotspot;
	
	if (sf!=null){
	
		sf.seltitle=title;
		
		if (!document.hotspot.picks) document.hotspot.picks={};
		if (sf.checked) document.hotspot.picks[sf.value]=sf.value;
		else delete document.hotspot.picks[sf.value];
	}
	
	var os=gid('lkvc').getElementsByTagName('input');
	
	if (document.iphone_portrait&&d.id&&gid(d.id+'_lookup')) os=gid(d.id+'_lookup').getElementsByTagName('input');
	
	var dtitle='';
	
	for (var i=0;i<os.length;i++) {
		if (os[i].className=='lksel'){
			if (document.hotspot.picks&&document.hotspot.picks[os[i].value]!=null) {
				os[i].checked='checked';
				dtitle=os[i].seltitle;
				
			} else {
				os[i].checked='';
			}
		}
	}
	
	if (sf==null) return;
		
	var sels=[];
	for (var k in document.hotspot.picks) sels.push(k);
		
	if (sels.length==0) {cancelpickup(d.id,true);return;}
		
	if (sels.length==1) d.value=dtitle; else d.value='('+sels.length+' items selected)';
	d.value2=sels.join(',');
	
	if (document.hotspot.id) {
		gid(document.hotspot.id).valuecount=1;
		var v2c=gid(document.hotspot.id+'_val2');
		if (v2c){
			gid(document.hotspot.id).disabled='disabled';
			v2c.innerHTML='<a class="labelbutton" href=# onclick="cancelpickup(\''+document.hotspot.id+'\');return false;">'+document.dict['edit']+'</a>';
		}
	}
		
	if (sf!=null&&d.onchage) d.onchange();
}

pickupalllookups=function(sf,subgroup){
	if (!document.hotspot) return;

	if (document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	
	if (document.hotspot){
		document.onclick=document.hotspot.lastonclick;
		document.hotspot.lastonclick=null;
	}	

	var d=document.hotspot;
		
	var sels=[]
	var os=gid('lkvc').getElementsByTagName('input');
	
	if (document.iphone_portrait&&d.id&&gid(d.id+'_lookup')) os=gid(d.id+'_lookup').getElementsByTagName('input');
	
	if (!sf.allchecked){
		for (var i=0;i<os.length;i++) if (os[i].className=='lksel') {if (subgroup==null||os[i].attributes.subsel.value==subgroup) os[i].checked='checked'; if (os[i].checked) sels.push(os[i].value);}
		sf.allchecked=true;
		sf.innerHTML='unselect all items';
		if (subgroup!=null) sf.innerHTML='uncheck';
	} else {
		sf.allchecked=null;
		for (var i=0;i<os.length;i++) if (os[i].className=='lksel') {if (subgroup==null||os[i].attributes.subsel.value==subgroup) os[i].checked=''; if (os[i].checked) sels.push(os[i].value);}
		sf.innerHTML='select all items';
		if (subgroup!=null) sf.innerHTML='check';
	}
	
	if (sels.length==0) {cancelpickup(d.id,true);return;}
	
	d.value='('+sels.length+' items selected)';
	d.value2=sels.join(',');
	
	if (!document.hotspot.picks) document.hotspot.picks={};
	for (var i=0;i<sels.length;i++) document.hotspot.picks[sels[i]]=sels[i];
	
	if (document.hotspot.id) {
		gid(document.hotspot.id).valuecount=1;
		var v2c=gid(document.hotspot.id+'_val2');
		if (v2c){
			gid(document.hotspot.id).disabled='disabled';
			v2c.innerHTML='<a class="labelbutton" style="color:#ffffff;" onclick="cancelpickup(\''+document.hotspot.id+'\');">'+document.dict['edit']+'</a>';
		}
	}
		
	if (d.onchage) d.onchange();
}


cancelpickup=function(c,unlockonly){
	if (gid(c).valuecount) {for (var i=0;i<gid(c).valuecount;i++) delete gid(c)['value'+(i+2)];gid(c).valuecount=0;}
	if (document.hotspot&&document.hotspot.picks) delete document.hotspot.picks;
	if (unlockonly) {
		gid(c).disabled='';
		gid(c).value='';
		if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
		if (gid(c).valuecount) for (var i=0;i<gid(c).valuecount;i++) delete gid(c)['value'+(i+2)];
		return;	
	}
	
	if (gid(c)) {gid(c).disabled=''; gid(c).value='';gid(c).focus();}
	if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
	
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();
		
}

listlookup=function(d,title,command,mini,data,callback){
	if (document.iphone_portrait) mini=1;
	if (document.tabafloat) mini=1;
	if (document.widen) mini=1;
	if (document.appsettings.uiconfig.force_inline_lookup) mini=1;
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&!d) d=document.hotspot;
	
	if (!document.appsettings.quicklist&&!document.appsettings.uiconfig.vertical_nav_persist) mini=1;
	if (mini&&!d) return;
			
	var lookupdismiss=function(e){
		if (e==null||e.target==null) return;
		var p=e.target;
		var isself=0;
		while (p!=null&&p!=document.body){
			if ((p==d||p==gid(d.id+'_lookup')||p==document.hotspotref)&&p!=gid(d.id+'_lookup_closer')) isself=1;
			p=p.parentNode;	
		}
		if (!isself){
			document.onclick=d.lastonclick;
			d.lastonclick=null;
			gid(d.id+'_lookup').style.display='none';
		}
	}
	
	
	if (mini&&d.id&&gid(d.id+'_lookup')){
		if (document.hotspot&&gid(document.hotspot.id)&&gid(document.hotspot.id+'_lookup')) gid(document.hotspot.id+'_lookup').style.display='none';
		if (document.hotspot&&document.hotspot.lookupview) {
			document.hotspot.lookupview.style.display='none';
			if (d!=document.hotspot) document.hotspot.lookupview.innerHTML='';
		}
		gid(d.id+'_lookup').style.display='block';
		gid(d.id+'_lookup_view').style.display='block';
		ajxpgn(d.id+'_lookup_view',document.appsettings.codepage+'?cmd='+command,0,0,data,callback);	
		d.lookupview=gid(d.id+'_lookup_view');
		
		document.hotspot=d;
		if (d.lastonclick==null){
			setTimeout(function(){
				d.lastonclick=document.onclick;
				document.onclick=lookupdismiss;
			},100);
		}
		return;	
	}

	/*
	if (document.iphone_portrait&&!document.portraitlock){
		if (gid('rotate_indicator')){
			gid('rotate_indicator').style.display='block';
			setTimeout(function(){
				gid('rotate_indicator').style.display='none';
			},1000);	
		}
		return;	
	}
	*/
	
	document.hotspot=d;

	if (gid('lkv')){
		gid('lkvt').innerHTML=title;
		gid('lkvc').innerHTML='';
		showlookup();
		ajxpgn('lkvc',document.appsettings.codepage+'?cmd='+command,0,0,data,callback);
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
		
		ajxpgn('lv'+view,document.appsettings.codepage+'?cmd='+command,true,true,data,callback);
		
	}	
	
		
}

lkv_dismount=function(){
	if (!document.lkvdismounted){
		gid('lkv').id='lkv_origin';
		document.lkvdismounted=true;
		var lkv=document.createElement('div');
		lkv.id='lkv';
		lkv.innerHTML=gid('lkv_origin').innerHTML;
		gid('lkv_origin').innerHTML='';
		lkv.className='dismounted';
		
		if (!document.iphone_portrait) lkv.style.position='absolute';
		
		lkv.style.zIndex=3010;
		
		lkv.style.transition='top 100ms';
		var w=cw();
		if (w>400) w=400;
		var left=(cw()-w)/2;
		
		lkv.style.width=w+'px';
		lkv.style.left=left+'px';
		
		var h=ch()-40;
		
		lkv.style.top=-1*h-20+'px';
		
		document.body.appendChild(lkv);
		gid('lkvc').style.height=(h-33)+'px';
		
		var lkvt=gid('lkvtitle');
		
		if (!lkvt.moveable){
			lkvt.onmousedown=function(e){
				var idw=cw();
				var idh=ch();
				var ox,oy;
				if (e) {ox=e.clientX; oy=e.clientY;}
				else {ox=window.event.clientX; oy=window.event.clientY;}
				var posx=lkv.offsetLeft;
				var posy=lkv.offsetTop;
				
				lkvt.onmousemove=function(e){
					var x,y;
					if (e) {x=e.clientX; y=e.clientY;}
					else {x=window.event.clientX;y=window.event.clientY;}
					
					var nx=posx+x-ox;
					var ny=posy+y-oy;
					
					if (nx>idw-405) nx=idw-405;
					if (nx<5) nx=5;
					if (ny>idh-lkv.offsetHeight-5) ny=idh-lkv.offsetHeight-5+'px';
					if (ny<5) ny=5;
					
					lkv.style.left=nx+'px';
					lkv.style.top=ny+'px';
					
					if (nx!=ox||ny!=oy) lkv.moved=true;
					
				}
				lkv.onmousemove=lkvt.onmousemove;
				document.onmousemove=lkvt.onmousemove;
				
				lkvt.moveable=true;			
			}//mousedown
		}
		lkvt.onmouseup=function(){
			lkvt.onmousemove=null;
			lkv.onmousemove=null;
			document.onmousemove=null;
			document.onmouseup=null;
			lkvt.moveabe=null;
		}
		lkv.onmouseup=lkvt.onmouseup;
		document.onmouseup=lkvt.onmouseup;
		
		//console.log('lkv dismounted');
	}	
}

lkv_remount=function(){
	if (gid('lkv_origin')){
		gid('lkv_origin').innerHTML=gid('lkv').innerHTML;
		gid('lkv').parentNode.removeChild(gid('lkv'));
		gid('lkv_origin').id='lkv';
		
		gid('lkvc').style.height=(ch()-187-33)+'px';
		if (document.appsettings.uiconfig.vertical_nav_persist){
			gid('lkvc').style.height=(ch()-187+90-33)+'px';		
		}
		gid('lkv').style.width='258px';
		gid('lkv').style.top='40px';
		
		if (gid('lkv').showing) gid('lkv').style.left='0px';	
		else gid('lkv').style.left='-280px';
		
		document.lkvdismounted=null;
		//console.log('lkv remounted');
	}	
}

showrelrec=function(id,showfunc,defid){
	var d=gid(id);
	if (d.disabled) showfunc(d.value2?d.value2:defid,d.value,arguments);
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
	
	if (document.appsettings.uiconfig.force_inline_lookup) mini=1;
	
	if (!opts.tz) opts.tz='';
	if (!opts.params) opts.params='';
	
	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
		
	listlookup(d,'Calendar','pkd&key='+key+'&tz='+opts.tz+'&mini='+(opts.mini?'1':'0')+'&'+opts.params,opts.mini);
}

_pickdate=function(d,opts){
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
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
	if (opts.start==null) opts.start=8;
	if (opts.end==null) opts.end=22; 
	if (!opts.mini) opts.mini=null;
	if (!opts.tz) opts.tz='';
	if (!opts.params) opts.params='';

	if (self.portrait_ignore&&!opts.mini) portrait_ignore();
	
	listlookup(d,'Calendar','pkd&mode=datetime&key='+key+'&hstart='+opts.start+'&hend='+opts.end+'&tz='+opts.tz+'&mini='+(opts.mini?'1':'0')+'&'+opts.params,opts.mini);
}

_pickdatetime=function(d,opts,def){
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
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
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
	if (d.timer) clearTimeout(d.timer);
	var f=function(d,opts){return function(){
		picktime(d,null,opts);
	}}
	d.timer=setTimeout(f(d,opts),200);
}

lookupentity=function(d,entity,title,data,mini,callback){
	if (!d.value) d.value='';
	if (d.disabled) return;
			
	var gval=encodeHTML(d.value);
	if (d.type=='textarea'){
		if (data==null) { 
			data='key='+gval;
		} else {
			data+='&key='+gval;	
		}
		gval='';
	}

	listlookup(d,title,'lookup'+entity+'&key='+gval,mini,data,callback);	
}

lookupentity_completed=function(d){
	d.removeAttribute('readonly');
	if (d.oclassname!=null) d.className=d.oclassname;	
}

_lookupentity=function(d,entity,title,data,mini,throttle,blockinput){
	if (throttle==null) throttle=400;
	if (d.disabled) return;
	
	if (blockinput==null) blockinput=0;
	
	if (!d.oclassname) d.oclassname=d.className;
	
	if (d.lastkey!=null&&d.lastkey==d.value) {
		lookupentity_completed(d);
		return;
	}
	d.lastkey=d.value;
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		if (!d.disabled){
			if (blockinput) d.setAttribute('readonly','');
			d.className=d.oclassname+' busy';
		}
		lookupentity(d,entity,title,data,mini,function(){	
			lookupentity_completed(d);
		});
	},throttle);
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

pastetotextarea=function(id,text){
	
	var oobj=gid(id);
	var ovalue=oobj.value;
	
	var sela=oobj.selectionStart;
	var selb=oobj.selectionEnd;
	if (selb!=null&&sela!=null&&selb-sela>1){
		var sel=oobj.value.substring(sela,selb);
		if (sel!='') {
			oobj.value=ovalue.substring(0,sela)+text+ovalue.substring(selb);
		}
	} else {
		oobj.value=ovalue.substring(0,sela)+text+ovalue.substring(sela);
	}
	
}

nav_setfilter=function(container,keyid,cmd,filter,bingo){
	var codepage=document.appsettings.codepage;
	if (bingo) codepage=document.appsettings.binpages[bingo+''];
	
	ajxpgn(container,codepage+'?cmd='+cmd+'&mode=embed&key='+encodeHTML(gid(keyid).value)+filter,0,0,null,function(){
		if (gid(container+'_chartrelay')) {
			gid(container+'_chartrelay').value=filter;
			if (gid(container+'_chartrelay').onchange) gid(container+'_chartrelay').onchange();
		}
		
		if (gid(container+'_extrakey_relay')) {
			if (gid(container+'_extrakey_relay').onchange) gid(container+'_extrakey_relay').onchange();
		}
		
	});
	if (gid(container+'_relay')) {
		gid(container+'_relay').value=filter;
		if (gid(container+'_relay').onchange) gid(container+'_relay').onchange();
	}
}

nav_multiorids=function(container,fieldname){
	var os=gid(container+'_navfilters_'+fieldname).getElementsByTagName('input');
	var ids=[];
	var ocount=0;
	for (var i=0;i<os.length;i++){
		var o=os[i];
		if (o.type==null||o.type!='checkbox') continue;
		ocount++;
		if (o.checked) ids.push(o.value);
	}
	return {"ids":ids,"ocount":ocount};
}

nav_selectfilter=function(d,container,fieldname,keyid,cmd,filter,bingo){
	var resids=nav_multiorids(container,fieldname);
	var ids=resids.ids;
	var ocount=resids.ocount;

	if (gid(container+'_multior_'+fieldname).oidlen==null) {
		gid(container+'_multior_'+fieldname).oidlen=ids.length;
		if (d.checked) gid(container+'_multior_'+fieldname).oidlen--;
	}
	if (ids.length>0) gid(container+'_multior_'+fieldname).style.visibility='visible'; else gid(container+'_multior_'+fieldname).style.visibility='hidden';
	if (ids.length==0&&(gid(container+'_multior_'+fieldname).oidlen>0||ocount==1)) nav_applymultior(container,fieldname,keyid,cmd,filter,bingo);
}

nav_applymultior=function(container,fieldname,keyid,cmd,filter,bingo){
	var resids=nav_multiorids(container,fieldname);
	var ids=resids.ids;
	filter+='&multior_'+fieldname+'='+ids.join('||');
	nav_setfilter(container,keyid,cmd,filter,bingo);
}


showmastersearch=function(){
	var w;
	
	//sync with viewport.js
	w=(cw()-gid('logoutlink').offsetWidth)*3/4;
	if (w>520) w=520;
	
	if (w<gid('mastersearchshadow').offsetWidth) w=gid('mastersearchshadow').offsetWidth;
	gid('mastersearch').style.width=w+'px';
	gid('mastersearch').className='expanded';
	
	gid('mainsearchview_').style.right=gid('logoutlink').offsetWidth-gid('mastersearchshadow').offsetWidth+26+'px';
	gid('mainsearchview_').style.width=w-12+'px';	
	
	document.mainsearch=true;
	
	if (gid('mastersearch').value!='') showmainsearchview();
}

hidemastersearch=function(){
	gid('mastersearch').style.width=gid('mastersearchshadow').offsetWidth+'px';
	gid('mastersearch').className='';
	
	document.mainsearch=null;
	hidemainsearchview();
}

showmainsearchview=function(){
	gid('mainsearchview_').style.display='block';
	if (gid('mainsearchview_').timer) clearTimeout(gid('mainsearchview_').timer);
	gid('mainsearchview_').timer=setTimeout(function(){
		gid('mainsearchview_').style.opacity=1;
	},30);	
}

hidemainsearchview=function(){
	setTimeout(function(){
		gid('mainsearchview_').style.opacity=0;
		if (gid('mainsearchview_').timer) clearTimeout(gid('mainsearchview_').timer);
		gid('mainsearchview_').timer=setTimeout(function(){
			gid('mainsearchview_').style.display='none';
		},100);	
	},80);	
}

clearmainsearch=function(){
	gid('mastersearch').value='';
	gid('mastersearch').lastkey=null;	
}

mastersearch=function(){
	var key=encodeHTML(gid('mastersearch').value);
	if (key=='') return;
	ajxpgn('mainsearchview',document.appsettings.codepage+'?cmd=lookupall&key='+key,0,0,null,function(rq){
		showmainsearchview();
	});
}

_mastersearch=function(){
	var d=gid('mastersearch');
	var key=d.value;
	
	if (key==''){
		hidemainsearchview();
		return;	
	}
	
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
	
	if (d.timer) clearTimeout(d.timer);	
	
	d.timer=setTimeout(function(){
		mastersearch();	
	},500);
	
}



// highcharts.js is not bundled
// use the following version for a standalone product, i.e. no dependency on Google
// additional licencing may be necessary

// Chart.js-based chart loader (replacing Highcharts)
// Maintains double ring feature: outer ring = average/amount, inner ring = count

// Animation switch: set to false to disable all chart animations
var NAV_CHART_ANIMATE=true;

// Inject fullscreen CSS once
if (!document.getElementById('chart-fullscreen-style')){
	var fsStyle=document.createElement('style');
	fsStyle.id='chart-fullscreen-style';
	fsStyle.textContent='.chart-fullscreen{display:flex;align-items:center;justify-content:center;}.chart-fullscreen .chart-wrapper{width:90vw!important;height:90vh!important;}';
	document.head.appendChild(fsStyle);
}

nav_loadcharts=function(container,keyid,cmd,bingo,liveDataSource,liveFields){

	// Load Chart.js UMD build (works with regular script tags)
	xajxjs('Chart','chart.umd.min.js',function(){

			// Store chart instances for cleanup
			if (!window._navChartInstances) window._navChartInstances={};

			// Subtle animation config (or false if disabled)
			var animConfig=NAV_CHART_ANIMATE?{duration:400,easing:'easeOutQuart'}:false;

			// Click handler factory
			var cf=function(d,chartInstance){
				return function(evt,elements){
					if (elements&&elements.length>0){
						var idx=elements[0].index;
						if (idx!=null&&d[idx]&&d[idx]['f']){
							nav_setfilter(container,keyid,cmd,d[idx]['f'],bingo);
						}
					}
				};
			};

			// Generate colors for chart segments
			// Checks document.chartPalettes[chartKey] for custom palette first
			var genColors=function(count,chartKey){
				var colors=[];
				var baseColors=['#7cb5ec','#434348','#90ed7d','#f7a35c','#8085e9','#f15c80','#e4d354','#2b908f','#f45b5b','#91e8e1',
					'#6c5ce7','#00b894','#fd79a8','#0984e3','#e17055','#74b9ff','#a29bfe','#55efc4','#fab1a0','#81ecec',
					'#636e72','#d63031','#00cec9','#fdcb6e'];
				// Use custom palette if set for this chart
				if (chartKey && document.chartPalettes && document.chartPalettes[chartKey]){
					baseColors=document.chartPalettes[chartKey];
				}
				for (var c=0;c<count;c++){
					colors.push(baseColors[c%baseColors.length]);
				}
				return colors;
			};

			// Use live data source if provided, otherwise use main chartdata
			var dataSourceId=liveDataSource?liveDataSource:(container+'_chartdata');
			var dataEl=gid(dataSourceId);
			if(!dataEl) return;

			var charts=eval('('+dataEl.value+')');
			if (charts==null) return;

			for (var i=0;i<charts.length;i++){
				// If liveFields is provided, only process charts in that list
				if(liveFields && liveFields.indexOf(charts[i].fieldname)===-1) continue;

				if (!gid(container+'_chartview_'+charts[i].fieldname)) continue;
				gid(container+'_chartview_'+charts[i].fieldname).style.display='block';

				var subdims=charts[i].subdims;
				var chartKey=container+'_chartview_'+charts[i].fieldname;
				var chartType=charts[i].type;

				// Destroy existing chart instance first
				if (window._navChartInstances[chartKey]){
					window._navChartInstances[chartKey].destroy();
					delete window._navChartInstances[chartKey];
				}

				// Clear placeholder content and create wrapper with fullscreen button
				var chartContainer=gid(chartKey);
				var canvas=chartContainer.querySelector('canvas');
				var wrapper=chartContainer.querySelector('.chart-wrapper');

				if (!canvas){
					chartContainer.innerHTML='';

					// Remove any old overlay buttons that were parented to chartview on a
					// previous render (they live outside chartContainer so innerHTML='' won't
					// have cleared them).
					var _oldLive=gid(chartKey+'_livebtn');
					if (_oldLive) _oldLive.parentNode.removeChild(_oldLive);
					var _oldFs=gid(chartKey+'_fsbtn');
					if (_oldFs) _oldFs.parentNode.removeChild(_oldFs);

					// Create wrapper div for positioning
					wrapper=document.createElement('div');
					wrapper.style.position='relative';
					wrapper.style.width='100%';
					wrapper.className='chart-wrapper';
					// Use CSS class for height control (stateless fullscreen)
					wrapper.setAttribute('data-charttype',chartType);

					// Overlay buttons are parented to chartview (position:relative, no overflow)
					// so they stay pinned to the top-right corner even when scroll mode widens
					// the inner wrapper and enables horizontal scrolling on chartContainer.
					var _chartview=gid(container+'_chartview_'+charts[i].fieldname);

					// Live refresh button
					var liveBtn=document.createElement('a');
					liveBtn.id=chartKey+'_livebtn';
					liveBtn.className='labelbutton live-refresh-btn';
					liveBtn.innerHTML='?';
					liveBtn.title='Live Refresh (1s)';
					liveBtn.style.cssText='position:absolute;top:2px;right:24px;z-index:10;opacity:0.5;';
					liveBtn.setAttribute('data-fieldname',charts[i].fieldname);
					liveBtn.onclick=(function(container,key,fieldname,btn){
						return function(){
							var chartEl=gid(key);
							if(!chartEl) return;

							// Toggle live refresh flag
							if(chartEl.live_refresh){
								chartEl.live_refresh=false;
								btn.style.opacity='0.5';
								btn.style.color='';
								btn.title='Live Refresh (1s)';
							} else {
								chartEl.live_refresh=true;
								btn.style.opacity='1.0';
								btn.style.color='#4CAF50';
								btn.title='Live Refresh ON';
							}

							// Start global interval if not already running
							nav_ensureLiveRefreshInterval(container,keyid,cmd,bingo);
						};
					})(container,chartKey,charts[i].fieldname,liveBtn);
					_chartview.appendChild(liveBtn);

					// Fullscreen button
					var fsBtn=document.createElement('a');
					fsBtn.id=chartKey+'_fsbtn';
					fsBtn.className='labelbutton';
					fsBtn.innerHTML='?';
					fsBtn.title='Fullscreen';
					fsBtn.style.cssText='position:absolute;top:2px;right:2px;z-index:10;';
					fsBtn.onclick=(function(container,key){
						return function(){
							var el=container;
							if (document.fullscreenElement){
								document.exitFullscreen();
							} else {
								el.classList.add('chart-fullscreen');
								el.requestFullscreen().then(function(){
									// Chart.js will auto-resize due to responsive:true
									if(window._navChartInstances[key]){
										setTimeout(function(){
											window._navChartInstances[key].resize();
										},100);
									}
								}).catch(function(e){console.log(e);});
							}
						};
					})(chartContainer,chartKey);

					// Stateless fullscreen exit - just remove class, chart auto-resizes
					chartContainer.addEventListener('fullscreenchange',function(){
						var self=this;
						if(!document.fullscreenElement){
							self.classList.remove('chart-fullscreen');
							// Find chart and resize
							for(var k in window._navChartInstances){
								if(k.indexOf(self.id)>=0 && window._navChartInstances[k]){
									setTimeout(function(){
										window._navChartInstances[k].resize();
									},100);
									break;
								}
							}
						}
					});
					_chartview.appendChild(fsBtn);

					canvas=document.createElement('canvas');
					// No inline max-height - use CSS instead
					canvas.style.width='100%';
					wrapper.appendChild(canvas);

					// chart-scroll-wrapper sits between chartContainer (navchartview) and
					// chart-wrapper.  Scroll mode applies overflow-x:auto here, so only
					// the chart content scrolls; the overlay buttons (which are children of
					// chartContainer, not scrollWrapper) remain pinned.
					var scrollWrapper=document.createElement('div');
					scrollWrapper.className='chart-scroll-wrapper';
					scrollWrapper.style.cssText='width:100%;overflow-x:hidden;';
					scrollWrapper.appendChild(wrapper);
					chartContainer.appendChild(scrollWrapper);

					// Apply height via wrapper class-based styling
					if (chartType=='pie' || chartType=='bubble'){
						wrapper.style.height='280px';
					} else {
						wrapper.style.height='400px';
					}
				}

				// Apply stored background color if any
				if (document.chartBackgrounds && document.chartBackgrounds[chartKey]){
					chartContainer.style.backgroundColor=document.chartBackgrounds[chartKey];
				}

				switch (chartType){
				case 'pie':

					var labels=[];
					var countData=[];
					var amountData=[];
					var allsame=1;

					for (var j=0;j<charts[i].counts.length;j++){
						labels.push(charts[i].counts[j]['n']);
						countData.push(charts[i].counts[j]['c']);
						amountData.push(charts[i].counts[j]['a']);
						if (charts[i].counts[j]['c']!=charts[i].counts[j]['a']) allsame=0;
					}

					var colors=genColors(labels.length,chartKey);
					var datasets=[];

					// For dual ring: Inner=Count (thick), Outer=Avg (thinner)
					// Chart.js renders datasets[0] as innermost
					// Use explicit radius percentages via circumference isn't available per-dataset
					// So we reverse order: Avg first (inner but we'll swap visually), Count second
					// Actually, to make Count thick inner and Avg thin outer:
					// - Count = inner (datasets[0])
					// - Avg = outer (datasets[1])
					// The trick is cutout controls the hole, not ring thickness
					// Ring thickness is divided equally among datasets
					// To make inner thicker, we need Avg ring to be thinner
					// This requires a workaround: use smaller cutout and add Avg as thin outer

					if (!allsame){
						// Inner ring: Count (will be thick due to low cutout)
						datasets.push({
							label:'Count',
							data:countData,
							backgroundColor:colors,
							borderWidth:1
						});
						// Outer ring: Avg (thin ring on outside)
						datasets.push({
							label:'Avg',
							data:amountData,
							backgroundColor:colors.map(function(c){return c+'cc';}),
							borderWidth:1,
							borderColor:'#fff'
						});
					} else {
						// Single dataset - just count as solid pie
						datasets.push({
							label:'Count',
							data:countData,
							backgroundColor:colors,
							borderWidth:1
						});
					}

					// Plugin to draw labels directly on slices (name + percentage)
					var pieLabelsPlugin={
						id:'pieLabels',
						afterDraw:function(chart){
							var ctx=chart.ctx;
							// Draw labels on the outer dataset (larger visible area)
							var dsIdx=chart.data.datasets.length>1?1:0;
							var meta=chart.getDatasetMeta(dsIdx);
							var dataset=chart.data.datasets[dsIdx];
							var chartLabels=chart.data.labels;

							meta.data.forEach(function(arc,idx){
								var data=dataset.data[idx];
								var total=dataset.data.reduce(function(a,b){return a+b;},0);
								if (total==0) return;
								var pct=((data/total)*100).toFixed(0);
								if (pct<3) return; // skip very small slices

								// Get slice name and truncate if needed
								var sliceName=chartLabels[idx]||'';
								if (sliceName.length>12) sliceName=sliceName.substring(0,10)+'..';

								var centerAngle=(arc.startAngle+arc.endAngle)/2;
								var outerR=arc.outerRadius;
								var innerR=arc.innerRadius;
								var midR=(innerR+outerR)/2;

								// For small slices, draw label outside
								var labelRadius=pct<10?(outerR+15):midR;
								var x=arc.x+Math.cos(centerAngle)*labelRadius;
								var y=arc.y+Math.sin(centerAngle)*labelRadius;

								ctx.save();
								// Use dark text for outside labels, white for inside
								if (pct<10){
									ctx.fillStyle='#333';
									ctx.shadowBlur=0;
								} else {
									ctx.fillStyle='#fff';
									ctx.shadowColor='rgba(0,0,0,0.6)';
									ctx.shadowBlur=3;
								}
								ctx.font='bold 10px sans-serif';
								ctx.textAlign='center';
								ctx.textBaseline='middle';

								// Draw name and percentage
								if (pct>=10){
									// Inside: name on top line, % below
									ctx.fillText(sliceName,x,y-6);
									ctx.fillText(pct+'%',x,y+6);
								} else {
									// Outside: single line
									ctx.fillText(sliceName+' '+pct+'%',x,y);
								}
								ctx.restore();
							});
						}
					};

					// cutout percentage: higher = bigger hole = thinner inner ring
					// For dual: 60% cutout makes inner ring thinner, outer takes more space
					// We want opposite: inner thick, outer thin
					// So use LOW cutout (small hole) = thick inner
					// But Chart.js divides remaining space equally...
					// Workaround: use radius option per dataset (Chart.js 4+)

					window._navChartInstances[chartKey]=new Chart(canvas,{
						type:'doughnut',
						data:{
							labels:labels,
							datasets:datasets
						},
						options:{
							responsive:true,
							maintainAspectRatio:false,
							animation:animConfig,
							cutout:allsame?'0%':'5%',  // very small center hole
							radius:allsame?'90%':'90%',
							plugins:{
								legend:{display:false},
								title:{display:true,text:charts[i].title},
								tooltip:{
									callbacks:{
										label:function(context){
											var label=context.dataset.label||'';
											var value=context.parsed;
											var total=context.dataset.data.reduce(function(a,b){return a+b;},0);
											var pct=total>0?((value/total)*100).toFixed(1):'0';
											return label+': '+value+' ('+pct+'%)';
										}
									}
								}
							},
							onClick:cf(charts[i].counts)
						},
						plugins:[pieLabelsPlugin]
					});

	        	break;

	        	case 'column':

					if (charts[i].counts.length==0){
						gid(container+'_chartview_'+charts[i].fieldname).style.display='none';
						continue;
					}

					// Tag original server-data order before any sorting (for "by 1st seen" stack order)
					for (var _oi=0;_oi<charts[i].counts.length;_oi++) charts[i].counts[_oi]._oi=_oi;

					// Sort series by total count descending
					charts[i].counts=charts[i].counts.sort(function(a,b){
						var suma=0; for (var ia=0;ia<a.length;ia++) suma+=a[ia].count;
						var sumb=0; for (var ib=0;ib<b.length;ib++) sumb+=b[ib].count;
						if (suma==sumb) return 0;
						if (suma<sumb) return 1; else return -1;
					});

					var datasets=[];
					var xlabels=[];
					var hasTrend=(charts[i].dimkey!=null&&charts[i].dimkey!='');

					// Build x-axis labels from first series
					if (charts[i].counts[0]){
						for (var j=0;j<charts[i].counts[0].length;j++){
							var xlabel=charts[i].counts[0][j]['min']+' to '+charts[i].counts[0][j]['max'];
							if (charts[i].counts[0][j]['xlabel']&&charts[i].counts[0][j]['xlabel']!='') xlabel=charts[i].counts[0][j]['xlabel'];
							if (xlabel!='null to null') xlabels.push(xlabel);
						}
					}

					// For stacked bar charts, rank series by total count (descending)
					// and assign consistent colors based on rank
					var seriesWithTotals=[];
					for (var sidx=0;sidx<charts[i].counts.length;sidx++){
						if (sidx>23) break;
						var total=0;
						for (var j=0;j<charts[i].counts[sidx].length;j++){
							total+=charts[i].counts[sidx][j]['count'];
						}
						seriesWithTotals.push({
							index: charts[i].counts[sidx]._oi!==undefined?charts[i].counts[sidx]._oi:sidx,
							total:total,
							data:charts[i].counts[sidx]
						});
					}

					// Store pre-sort series data on chartview element for stack order live-toggle
					var _ckey=container+'_chartview_'+charts[i].fieldname;
					gid(_ckey)._seriesData=seriesWithTotals.slice();

					// Sort based on stack order setting (default: by weight descending)
					var _stackOrder=chart_getstackorder(_ckey);
					if (_stackOrder=='seen'){
						seriesWithTotals.sort(function(a,b){return a.index-b.index;});
					} else {
						// Sort by total descending (largest at bottom of stack)
						seriesWithTotals.sort(function(a,b){return b.total-a.total;});
					}

					// Generate colors based on number of series
					var colors=genColors(seriesWithTotals.length,chartKey);

					for (var si=0;si<seriesWithTotals.length;si++){
						var series=seriesWithTotals[si];
						var rows=[];

						for (var j=0;j<series.data.length;j++){
							var xlabel=series.data[j]['min']+' to '+series.data[j]['max'];
							if (series.data[j]['xlabel']&&series.data[j]['xlabel']!='') xlabel=series.data[j]['xlabel'];
							if (xlabel!='null to null') {
								rows.push(series.data[j]['count']);
							}
						}

						if(series.data[0]) {
							var dsConfig={
								label:series.data[0]['kn'],
								data:rows,
								backgroundColor:colors[si],
								borderColor:colors[si],
								borderWidth:hasTrend?0:1
							};
							datasets.push(dsConfig);
						}
					}

					var dimlabel='';
					switch (charts[i].dimmode){
						case 's': dimlabel='Sum'; break;
						case 'a': dimlabel='Average'; break;
						default: dimlabel='Count';
					}

					// Use 'bar' for both single and stacked (trend) charts
					var chartType='bar';

					// Build click handler data (flatten for first series)
					var clickData=charts[i].counts[0]||[];

					// Plugin to draw labels on stacked bars
					var barLabelsPlugin={
						id:'barLabels',
						afterDatasetsDraw:function(chart){
							var ctx=chart.ctx;
							var isStacked=chart.data.datasets.length>1;

							if(!isStacked) return; // Only label stacked charts
							if(chart._animRunning) return; // Hide labels during frame animation

							chart.data.datasets.forEach(function(dataset,dsIdx){
								var meta=chart.getDatasetMeta(dsIdx);
								if(meta.hidden) return;

								var labelText=dataset.label||'';
								if(labelText.length>15) labelText=labelText.substring(0,13)+'..';

								// Find the bar with largest height for this dataset
								var bestBar=null;
								var bestHeight=0;

								meta.data.forEach(function(bar,idx){
									// Calculate segment height
									var y0=bar.y;
									var y1=bar.base;
									var h=Math.abs(y1-y0);

									if(h>bestHeight){
										bestHeight=h;
										bestBar={x:bar.x,y:(y0+y1)/2,width:bar.width};
									}
								});

								// Draw label in largest segment if tall enough
								if(bestBar && bestHeight>15){
									ctx.save();
									ctx.fillStyle='#000';  // Black text
									ctx.font='bold 10px sans-serif';
									ctx.textAlign='center';
									ctx.textBaseline='middle';
									ctx.strokeStyle='#fff';  // White outline for contrast
									ctx.lineWidth=3;
									ctx.strokeText(labelText,bestBar.x,bestBar.y);
									ctx.fillText(labelText,bestBar.x,bestBar.y);
									ctx.restore();
								}
							});
						}
					};

					window._navChartInstances[chartKey]=new Chart(canvas,{
						type:chartType,
						data:{
							labels:xlabels,
							datasets:datasets
						},
						options:{
							responsive:true,
							maintainAspectRatio:false,
							animation:animConfig,
							plugins:{
								title:{display:true,text:charts[i].title},
								legend:{display:false}  // hide external legend
							},
							scales:{
								y:{
									beginAtZero:true,
									stacked:hasTrend,
									title:{display:true,text:dimlabel}
								},
								x:{
									stacked:hasTrend
								}
							},
							onClick:cf(clickData)
						},
						plugins:[barLabelsPlugin]
					});

					// Re-apply scroll mode if it was active before this render
					if (chart_getscrollmode(chartKey)) chart_applyscrollmode(chartKey, true);

					// Add toolbar for dim controls
					var chartview=gid(container+'_chartview_'+charts[i].fieldname);
					var toolanchor=document.createElement('span');
					toolanchor.onclick=function(fn){return function(){showhide(container+'_charttoolbar_'+fn);};}(charts[i].fieldname);
					toolanchor.innerHTML='..';
					toolanchor.style.position='absolute'; toolanchor.style.top='2px';toolanchor.style.left='30px';
					toolanchor.style.background='#444444'; toolanchor.style.color='#ffffff'; toolanchor.style.borderRadius='3px';
					toolanchor.style.padding='1px 4px'; toolanchor.style.cursor='pointer';

					// Remove old toolbar if exists
					var oldToolbar=gid(container+'_charttoolbar_'+charts[i].fieldname);
					if (oldToolbar) oldToolbar.parentNode.removeChild(oldToolbar);
					var oldAnchor=chartview.querySelector('span');
					if (oldAnchor) oldAnchor.parentNode.removeChild(oldAnchor);

					var toolbar=document.createElement('div');
					toolbar.id=container+'_charttoolbar_'+charts[i].fieldname;
					toolbar.style.position='absolute';
					toolbar.style.top='32px'; toolbar.style.left='40px'; toolbar.style.width='80%';
					toolbar.className='charttoolbar';
					toolbar.style.display='none';

					var thtml=[];
					thtml.push('<div style="padding:5px;">');
						thtml.push('<input type="radio" '+(charts[i].dimmode=='c'?'checked':'')+' onclick="nav_setfilter(\''+container+'\',\''+keyid+'\',\''+cmd+'\',\''+charts[i].dimmodebase+'&'+charts[i].fieldname+'__dimmode=c\','+bingo+');" id="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_c" name="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode">');
						thtml.push('<label for="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_c">Count</label>&nbsp;&nbsp;');

						thtml.push('<input type="radio" '+(charts[i].dimmode=='s'?'checked':'')+' onclick="nav_setfilter(\''+container+'\',\''+keyid+'\',\''+cmd+'\',\''+charts[i].dimmodebase+'&'+charts[i].fieldname+'__dimmode=s\','+bingo+');" id="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_s" name="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode">');
						thtml.push('<label for="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_s">Sum</label>&nbsp;&nbsp;');

						thtml.push('<input type="radio" '+(charts[i].dimmode=='a'?'checked':'')+' onclick="nav_setfilter(\''+container+'\',\''+keyid+'\',\''+cmd+'\',\''+charts[i].dimmodebase+'&'+charts[i].fieldname+'__dimmode=a\','+bingo+');" id="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_a" name="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode">');
						thtml.push('<label for="'+container+'_charttoolbar_'+charts[i].fieldname+'_dim2mode_a">Avg.</label>&nbsp;&nbsp;');

						if (subdims!=null){
						thtml.push('<div style="padding-top:5px;">');
						thtml.push('Trend: ');
						thtml.push('<select onchange="nav_setfilter(\''+container+'\',\''+keyid+'\',\''+cmd+'\',\''+charts[i].dimkeybase+'&'+charts[i].fieldname+'__dimkey=\'+this.value,'+bingo+');">');
								thtml.push('<option value=""></option>');
							for (dimkey in subdims){
								thtml.push('<option '+(charts[i].dimkey==dimkey?'selected':'')+' value="'+dimkey+'">'+subdims[dimkey]+'</option>');
							}
						thtml.push('</select>');
						thtml.push('</div>');
						}

						// Time mode checkbox for logdate field only
						if (charts[i].fieldname=='logdate' && charts[i].timemode!=null){
						thtml.push('<div style="padding-top:5px;">');
						var timemodeChecked=(charts[i].timemode=='l'?'checked':'');
						thtml.push('<input type="checkbox" '+timemodeChecked+' onclick="nav_setfilter(\''+container+'\',\''+keyid+'\',\''+cmd+'\',\''+charts[i].timemodebase+'&'+charts[i].fieldname+'__timemode=\'+(this.checked?\'l\':\'r\'),'+bingo+');" id="'+container+'_charttoolbar_'+charts[i].fieldname+'_timemode">');
						thtml.push('<label for="'+container+'_charttoolbar_'+charts[i].fieldname+'_timemode">use log time</label>');
						thtml.push('</div>');
						}

						// Stack order (only for stacked bar charts)
						if (hasTrend){
						var _soKey=container+'_chartview_'+charts[i].fieldname;
						var _curOrder=chart_getstackorder(_soKey);
						thtml.push('<div style="padding-top:5px;">');
						thtml.push('<span>stack order: </span>');
						thtml.push('<select onchange="chart_setstackorder(\''+_soKey+'\',this.value);">');
						thtml.push('<option value="weight"'+(_curOrder=='weight'?' selected':'')+'>by weight</option>');
						thtml.push('<option value="seen"'+(_curOrder=='seen'?' selected':'')+'>by 1st seen</option>');
						thtml.push('</select>');
						thtml.push('</div>');
						}

						// Scroll mode checkbox + scale (all column charts)
						{
						var _scrollKey=container+'_chartview_'+charts[i].fieldname;
						var _curScroll=chart_getscrollmode(_scrollKey);
						var _curScale=chart_getscrollscale(_scrollKey);
						thtml.push('<div style="padding-top:5px;">');
						thtml.push('<input type="checkbox" id="'+_scrollKey+'_scroll" '+(_curScroll?'checked':'')+' onchange="chart_setscrollmode(\''+_scrollKey+'\',this.checked);">');
						thtml.push('<label for="'+_scrollKey+'_scroll"> scroll mode</label>');
						thtml.push('</div>');
						thtml.push('<div id="'+_scrollKey+'_scalectl" style="padding-top:3px;'+(!_curScroll?'display:none;':'')+'">');
						thtml.push('<input type="number" id="'+_scrollKey+'_scalepct" value="'+_curScale+'" min="100" max="5000" style="width:55px;" onchange="chart_setscrollscale(\''+_scrollKey+'\',this.value);">');
						thtml.push('%');
						thtml.push('</div>');
						// Animation controls (visible only when scroll mode is active)
						var _animRunning=chart_getanimrunning(_scrollKey);
						thtml.push('<div id="'+_scrollKey+'_animctl" style="padding-top:5px;'+(!_curScroll?'display:none;':'')+'">');
						thtml.push('<div>timing: <span id="'+_scrollKey+'_animtimingpicker"></span></div>');
						thtml.push('<div style="padding-top:3px;"><button id="'+_scrollKey+'_animbtn" onclick="chart_animtoggle(\''+_scrollKey+'\');">'+(_animRunning?'&#9646;':'&#9654;')+'</button></div>');
						thtml.push('</div>');
						}

						// Chart palette customization
						var paletteContainerId=container+'_chartpalette_'+charts[i].fieldname;
						var chartKey=container+'_chartview_'+charts[i].fieldname;
						thtml.push('<div style="padding-top:5px;">');
						thtml.push('<a class="hovlink" onclick="showhide(\''+paletteContainerId+'\');">colors</a>');
						thtml.push('<div id="'+paletteContainerId+'" style="display:none;padding-top:5px;">');
						thtml.push('<span id="'+paletteContainerId+'_swatches">');
						// Render initial swatches - will be populated by chart_renderpaletteswatches
						thtml.push('</span>');
						thtml.push(' <a class="hovlink" onclick="chart_resetpalette(\''+chartKey+'\');" style="font-size:10px;">reset</a>');
						// Background color picker
						thtml.push('<div style="padding-top:5px;">');
						thtml.push('<span style="font-size:10px;">bg: </span>');
						thtml.push('<span id="'+paletteContainerId+'_bg">');
						thtml.push('</span>');
						thtml.push(' <a class="hovlink" onclick="chart_resetbackground(\''+chartKey+'\');" style="font-size:10px;">reset</a>');
						thtml.push('</div>');
						thtml.push('</div>');
						thtml.push('</div>');

					thtml.push('</div>');
					toolbar.innerHTML=thtml.join('');
					if ((charts[i].dimmode!=null&&charts[i].dimmode!='') || hasTrend) chartview.appendChild(toolanchor);
					chartview.appendChild(toolbar);

					// Render palette swatches after toolbar is added to DOM
					chart_renderpaletteswatches(chartKey);
					chart_renderbackgroundswatch(chartKey);
					chart_renderanimtimingpicker(chartKey);

	        	break;

	        	case 'bubble':

					if (charts[i].counts.length==0){
						gid(container+'_chartview_'+charts[i].fieldname).style.display='none';
						continue;
					}

					var bubbleData=[];
					var bubbleLabels=[];

					for (var j=0;j<charts[i].counts.length;j++){
						if (charts[i].counts[j]['x']!=null) {
							bubbleData.push({
								x:charts[i].counts[j]['x'],
								y:charts[i].counts[j]['y'],
								r:Math.min(Math.max(Math.sqrt(charts[i].counts[j]['z'])*2,3),30)
							});
							bubbleLabels.push(charts[i].counts[j]['n']||'');
						}
					}

					// Plugin to draw labels on bubbles
					var bubbleLabelsPlugin={
						id:'bubbleLabels',
						afterDatasetsDraw:function(chart){
							var ctx=chart.ctx;
							var meta=chart.getDatasetMeta(0);
							var labels=chart.data.datasets[0]._labels||[];
							meta.data.forEach(function(bubble,idx){
								var label=labels[idx];
								if(!label) return;
								// Only draw label if bubble is large enough
								var r=bubble.options.radius||bubble.r||5;
								if(r<8) return;
								ctx.save();
								ctx.fillStyle='#333';
								ctx.font='9px sans-serif';
								ctx.textAlign='center';
								ctx.textBaseline='middle';
								// Truncate long labels
								if(label.length>10) label=label.substring(0,8)+'..';
								ctx.fillText(label,bubble.x,bubble.y);
								ctx.restore();
							});
						}
					};

					window._navChartInstances[chartKey]=new Chart(canvas,{
						type:'bubble',
						data:{
							datasets:[{
								label:charts[i].title,
								data:bubbleData,
								_labels:bubbleLabels,  // store labels for plugin
								backgroundColor:'rgba(124,181,236,0.6)',
								borderColor:'rgba(124,181,236,1)',
								borderWidth:1
							}]
						},
						options:{
							responsive:true,
							maintainAspectRatio:false,
							animation:animConfig,
							plugins:{
								title:{display:true,text:charts[i].title},
								legend:{display:false}
							},
							scales:{
								x:{
									title:{display:true,text:charts[i].title}
								},
								y:{
									title:{display:true,text:charts[i].title2||''}
								}
							},
							onClick:cf(charts[i].counts)
						},
						plugins:[bubbleLabelsPlugin]
					});

	        	break;

        		}//switch chart type

    		}//for each chart

	});

}

// Global live refresh interval manager
// Single interval that checks all charts for live_refresh flag
if (!window._navLiveRefreshInterval) window._navLiveRefreshInterval=null;
if (!window._navLiveRefreshContext) window._navLiveRefreshContext={};

nav_ensureLiveRefreshInterval=function(container,keyid,cmd,bingo){
	// If interval already running, just return
	if(window._navLiveRefreshInterval) return;

	// Store context for this container
	window._navLiveRefreshContext[container]={
		keyid:keyid,
		cmd:cmd,
		bingo:bingo
	};

	// Start interval
	window._navLiveRefreshInterval=setInterval(function(){
		nav_processLiveRefresh();
	},1000);
}

nav_processLiveRefresh=function(){
	// Scan all containers for charts with live_refresh flag
	var fieldsToRefresh={};

	for(var containerName in window._navLiveRefreshContext){
		var ctx=window._navLiveRefreshContext[containerName];
		var fields=[];

		// Scan all chart elements in this container
		for(var chartKey in window._navChartInstances){
			if(chartKey.indexOf(containerName+'_chartview_')===0){
				var chartEl=gid(chartKey);
				if(chartEl && chartEl.live_refresh){
					// Extract fieldname from chartKey
					var fieldname=chartKey.replace(containerName+'_chartview_','');
					fields.push(fieldname);
				}
			}
		}

		if(fields.length>0){
			fieldsToRefresh[containerName]={
				fields:fields,
				ctx:ctx
			};
		}
	}

	// If no charts need refresh, stop interval
	if(Object.keys(fieldsToRefresh).length===0){
		clearInterval(window._navLiveRefreshInterval);
		window._navLiveRefreshInterval=null;
		return;
	}

	// Make batch requests for each container
	for(var containerName in fieldsToRefresh){
		var data=fieldsToRefresh[containerName];
		nav_refreshCharts(containerName,data.fields,data.ctx);
	}
}

nav_refreshCharts=function(container,fields,ctx){
	// Get codepage from appsettings
	var codepage=document.appsettings.codepage;
	if(ctx.bingo) codepage=document.appsettings.binpages[ctx.bingo+''];

	// Get current filter from searchfilter element
	var currentFilters='';
	var searchFilterEl=gid('searchfilter_'+container);
	if(searchFilterEl) currentFilters=searchFilterEl.value;

	// Add fields parameter
	var fieldsParam='&fields='+fields.join(',');

	// Build URL with proper format: codepage?cmd=command&filters&fields
	var url=codepage+'?cmd='+ctx.cmd+'&mode=embed&submode=liverefresh&key='+encodeHTML(gid(ctx.keyid).value)+currentFilters+fieldsParam;

	// Create live feed container if it doesn't exist
	var liveFeedContainer=gid(container+'_livefeed');
	if(!liveFeedContainer){
		liveFeedContainer=document.createElement('div');
		liveFeedContainer.id=container+'_livefeed';
		liveFeedContainer.style.display='none';
		document.body.appendChild(liveFeedContainer);
	}

	// Use live feed container as transport (doesn't disrupt main container DOM)
	ajxpgn(container+'_livefeed',url,0,0,null,function(){
		// Update charts using live feed data
		nav_updateChartsFromLiveData(container,fields,ctx.keyid,ctx.cmd,ctx.bingo);
	});
}

nav_updateChartsFromLiveData=function(container,fields,keyid,cmd,bingo){
	// Store live_refresh flags before updating
	var liveFlags={};
	for(var i=0;i<fields.length;i++){
		var chartKey=container+'_chartview_'+fields[i];
		var chartEl=gid(chartKey);
		if(chartEl && chartEl.live_refresh){
			liveFlags[fields[i]]=true;
		}
	}

	// Temporarily disable animations for live refresh updates
	var originalAnimSetting=NAV_CHART_ANIMATE;
	NAV_CHART_ANIMATE=false;

	// Call nav_loadcharts with live data source and field filter
	// This reuses all the chart rendering logic from the original function
	nav_loadcharts(container,keyid,cmd,bingo,container+'_chartdata_live',fields);

	// Restore animation setting
	NAV_CHART_ANIMATE=originalAnimSetting;

	// Restore live_refresh flags after charts are redrawn
	for(var fieldname in liveFlags){
		var chartKey=container+'_chartview_'+fieldname;
		var chartEl=gid(chartKey);
		if(chartEl){
			chartEl.live_refresh=true;
			// Update button visual state
			var btn=chartEl.querySelector('.live-refresh-btn');
			if(btn){
				btn.style.opacity='1.0';
				btn.style.color='#4CAF50';
				btn.title='Live Refresh ON';
			}
		}
	}
}

// Chart palette registry - persists until page reload
// Initialize registry if not present
if (!document.chartPalettes) document.chartPalettes={};
if (!document.chartBackgrounds) document.chartBackgrounds={};
if (!document.chartStackOrders) document.chartStackOrders={};

/**
 * Set a custom color palette for a specific chart
 * @param {string} chartKey - The chart identifier (e.g., 'nav_1234_chartview_status')
 * @param {Array<string>} colors - Array of hex color codes (e.g., ['#ff0000','#00ff00','#0000ff'])
 * @param {string} [backgroundColor] - Optional background color hex code (e.g., '#ffffff')
 *
 * Usage: chart_setpalette('nav_1234_chartview_status', ['#ff0000','#00ff00','#0000ff']);
 * Usage with background: chart_setpalette('nav_1234_chartview_status', ['#ff0000','#00ff00'], '#222222');
 * The palette persists until the page is reloaded.
 * To clear a palette, call with null or empty array: chart_setpalette('nav_1234_chartview_status', null);
 * To clear background, pass null as third argument: chart_setpalette('nav_1234_chartview_status', null, null);
 */
function chart_setpalette(chartKey, colors, backgroundColor){
	if (!document.chartPalettes) document.chartPalettes={};
	if (!document.chartBackgrounds) document.chartBackgrounds={};

	var baseColors=['#7cb5ec','#434348','#90ed7d','#f7a35c','#8085e9','#f15c80','#e4d354','#2b908f','#f45b5b','#91e8e1',
		'#6c5ce7','#00b894','#fd79a8','#0984e3','#e17055','#74b9ff','#a29bfe','#55efc4','#fab1a0','#81ecec',
		'#636e72','#d63031','#00cec9','#fdcb6e'];

	// Only process colors if explicitly passed (not undefined)
	if (typeof colors !== 'undefined'){
		if (colors && colors.length>0){
			document.chartPalettes[chartKey]=colors;
		} else {
			delete document.chartPalettes[chartKey];
		}

		// Apply colors immediately if chart exists
		var palette=chart_getpalette(chartKey);
		if (window._navChartInstances && window._navChartInstances[chartKey]){
			var chart=window._navChartInstances[chartKey];
			var datasets=chart.data.datasets;
			var chartType=chart.config.type;

			// Generate colors array for the data length
			var dataLen=datasets[0]?datasets[0].data.length:0;
			var newColors=[];
			for (var c=0;c<dataLen;c++){
				newColors.push(palette[c%palette.length]);
			}

			// Update each dataset's backgroundColor based on chart type
			for (var i=0;i<datasets.length;i++){
				if (chartType==='doughnut' || chartType==='pie'){
					if (i===0){
						datasets[i].backgroundColor=newColors;
					} else {
						datasets[i].backgroundColor=newColors.map(function(col){return col+'cc';});
					}
				} else if (chartType==='bubble'){
					datasets[i].backgroundColor=palette[i%palette.length]+'99';
					datasets[i].borderColor=palette[i%palette.length];
				} else {
					if (Array.isArray(datasets[i].backgroundColor)){
						datasets[i].backgroundColor=newColors;
					} else {
						datasets[i].backgroundColor=palette[i%palette.length];
					}
					if (datasets[i].borderColor){
						if (Array.isArray(datasets[i].borderColor)){
							datasets[i].borderColor=newColors;
						} else {
							datasets[i].borderColor=palette[i%palette.length];
						}
					}
				}
			}

			chart.data.datasets=datasets;
			chart.update();
		}
	}

	// Handle background color (only if explicitly passed)
	if (typeof backgroundColor !== 'undefined'){
		if (backgroundColor && backgroundColor !== ''){
			document.chartBackgrounds[chartKey]=backgroundColor;
		} else {
			delete document.chartBackgrounds[chartKey];
			backgroundColor=null;
		}
		// Apply background to chart container
		var chartContainer=gid(chartKey);
		if (chartContainer){
			chartContainer.style.backgroundColor=backgroundColor || '';
		}
	}
}

/**
 * Get the background color for a chart (custom or default)
 * @param {string} chartKey - The chart identifier
 * @returns {string|null} Background color hex code or null if not set
 */
function chart_getbackgroundcolor(chartKey){
	if (document.chartBackgrounds && document.chartBackgrounds[chartKey]){
		return document.chartBackgrounds[chartKey];
	}
	return null;
}

// Kept for backwards compatibility - use chart_setpalette with colors parameter
function chart_setpalette_colors(chartKey, colors){
	chart_setpalette(chartKey, colors, undefined);
}

// Set only background color without affecting palette
function chart_setbackground(chartKey, backgroundColor){
	chart_setpalette(chartKey, undefined, backgroundColor);
}

/**
 * Get all chart keys for the current tab
 * @returns {Array<string>} Array of chart keys in the current tab
 *
 * Usage: var keys = chart_getchartkeys();
 * Returns keys like ['nav_1234_chartview_status', 'nav_1234_chartview_priority']
 */
function chart_getchartkeys(){
	var keys=[];

	// Get current tab view element
	if (document.currenttab==null || document.currenttab<0) return keys;
	var tabview=document.tabviews[document.currenttab];
	if (!tabview) return keys;

	// Find all chart containers within the current tab
	// Charts have IDs matching pattern: *_chartview_*
	var allDivs=tabview.getElementsByTagName('div');
	for (var i=0;i<allDivs.length;i++){
		var div=allDivs[i];
		if (div.id && div.id.indexOf('_chartview_')>0){
			// Check if it has a chart instance
			if (window._navChartInstances && window._navChartInstances[div.id]){
				keys.push(div.id);
			}
		}
	}

	return keys;
}

/**
 * Get the current palette for a chart (custom or default)
 * @param {string} chartKey - The chart identifier
 * @returns {Array<string>} Array of hex color codes
 */
function chart_getpalette(chartKey){
	var baseColors=['#7cb5ec','#434348','#90ed7d','#f7a35c','#8085e9','#f15c80','#e4d354','#2b908f','#f45b5b','#91e8e1',
		'#6c5ce7','#00b894','#fd79a8','#0984e3','#e17055','#74b9ff','#a29bfe','#55efc4','#fab1a0','#81ecec',
		'#636e72','#d63031','#00cec9','#fdcb6e'];
	if (document.chartPalettes && document.chartPalettes[chartKey]){
		return document.chartPalettes[chartKey].slice(); // return copy
	}
	return baseColors.slice();
}

/**
 * Get the stack order setting for a chart
 * @param {string} chartKey - The chart identifier
 * @returns {string} 'weight' (default) or 'seen'
 */
function chart_getstackorder(chartKey){
	if (document.chartStackOrders && document.chartStackOrders[chartKey]){
		return document.chartStackOrders[chartKey];
	}
	return 'weight';
}

/**
 * Set the stack order for a stacked bar chart and re-render immediately
 * @param {string} chartKey - The chart identifier
 * @param {string} order - 'weight' (largest at bottom) or 'seen' (first-seen at bottom)
 */
function chart_setstackorder(chartKey, order){
	if (!document.chartStackOrders) document.chartStackOrders={};
	if (order && order!=''){
		document.chartStackOrders[chartKey]=order;
	} else {
		delete document.chartStackOrders[chartKey];
		order='weight';
	}

	// Re-render chart with new stack order using stored pre-sort series data
	var chartEl=gid(chartKey);
	if (!chartEl || !chartEl._seriesData) return;
	var chart=window._navChartInstances && window._navChartInstances[chartKey];
	if (!chart) return;

	var seriesWithTotals=chartEl._seriesData.slice();
	if (order=='seen'){
		seriesWithTotals.sort(function(a,b){return a.index-b.index;});
	} else {
		seriesWithTotals.sort(function(a,b){return b.total-a.total;});
	}

	var palette=chart_getpalette(chartKey);
	var existingBorderWidth=chart.data.datasets[0]?chart.data.datasets[0].borderWidth:1;
	var newDatasets=[];
	for (var si=0;si<seriesWithTotals.length;si++){
		var series=seriesWithTotals[si];
		var color=palette[si%palette.length];
		var rows=[];
		for (var j=0;j<series.data.length;j++){
			var xlabel=series.data[j]['min']+' to '+series.data[j]['max'];
			if (series.data[j]['xlabel']&&series.data[j]['xlabel']!='') xlabel=series.data[j]['xlabel'];
			if (xlabel!='null to null') rows.push(series.data[j]['count']);
		}
		if (series.data[0]){
			newDatasets.push({
				label:series.data[0]['kn'],
				data:rows,
				backgroundColor:color,
				borderColor:color,
				borderWidth:existingBorderWidth
			});
		}
	}

	chart.data.datasets=newDatasets;
	chart.update();
	chart_renderpaletteswatches(chartKey);
}

/**
 * Get whether scroll mode is enabled for a chart
 * @param {string} chartKey - The chart identifier
 * @returns {boolean}
 */
function chart_getscrollmode(chartKey){
	if (document.chartScrollModes && document.chartScrollModes[chartKey]) return true;
	return false;
}

/**
 * Enable or disable scroll mode for a chart.
 * In scroll mode the chart container gets overflow:auto and the drawing
 * area is widened so bars keep a minimum pixel width rather than squishing.
 * @param {string} chartKey - The chart identifier
 * @param {boolean} enabled
 */
function chart_setscrollmode(chartKey, enabled){
	if (!document.chartScrollModes) document.chartScrollModes={};
	if (enabled){
		document.chartScrollModes[chartKey]=true;
	} else {
		delete document.chartScrollModes[chartKey];
		// Stop any running animation when scroll mode is turned off
		if (chart_getanimrunning(chartKey)) chart_animstop(chartKey);
	}
	// Show/hide the scale and animation controls in the toolbar
	var scaleCtl=gid(chartKey+'_scalectl');
	if (scaleCtl) scaleCtl.style.display=enabled?'':'none';
	var animCtl=gid(chartKey+'_animctl');
	if (animCtl) animCtl.style.display=enabled?'':'none';
	chart_applyscrollmode(chartKey, enabled);
}

/**
 * Get the scroll scale percentage for a chart (default 300)
 * @param {string} chartKey - The chart identifier
 * @returns {number} Scale percentage (e.g. 300 = 3× container width)
 */
function chart_getscrollscale(chartKey){
	if (document.chartScrollScales && document.chartScrollScales[chartKey]){
		return document.chartScrollScales[chartKey];
	}
	return 300;
}

/**
 * Set the scroll scale percentage and immediately re-apply
 * @param {string} chartKey - The chart identifier
 * @param {string|number} scale - Percentage value (e.g. 300 for 300%)
 */
function chart_setscrollscale(chartKey, scale){
	if (!document.chartScrollScales) document.chartScrollScales={};
	var v=parseInt(scale,10);
	if (!isNaN(v) && v>=100){
		document.chartScrollScales[chartKey]=v;
	}
	if (chart_getscrollmode(chartKey)) chart_applyscrollmode(chartKey, true);
}

/**
 * Apply (or remove) scroll mode styling to a chart container.
 * Called both from chart_setscrollmode and after each chart render.
 * @param {string} chartKey - The chart identifier
 * @param {boolean} enabled
 */
function chart_applyscrollmode(chartKey, enabled){
	var chartContainer=gid(chartKey);
	if (!chartContainer) return;
	// .chart-scroll-wrapper is the dedicated scroll container that sits between
	// chartContainer (navchartview) and .chart-wrapper.  Overflow is applied here
	// so the overlay buttons (children of chartContainer, outside scrollWrapper)
	// stay pinned to the top-right corner even when the chart content scrolls.
	var scrollWrapper=chartContainer.querySelector('.chart-scroll-wrapper');
	var wrapper=chartContainer.querySelector('.chart-wrapper');
	if (!scrollWrapper || !wrapper) return;
	var chart=window._navChartInstances && window._navChartInstances[chartKey];

	if (enabled){
		var scale=chart_getscrollscale(chartKey);
		var targetWidth=Math.floor(chartContainer.offsetWidth * scale / 100);
		scrollWrapper.style.overflowX='auto';
		wrapper.style.width=targetWidth+'px';
	} else {
		scrollWrapper.style.overflowX='hidden';
		wrapper.style.width='100%';
	}
	if (chart) chart.resize();
}

// -- Animation functions ----------------------------------------------------

/**
 * Get the dataset index used as the animation timing key (-1 = none)
 */
function chart_getanimtimingkey(chartKey){
	if (document.chartAnimTimingKeys && document.chartAnimTimingKeys[chartKey]!=null)
		return document.chartAnimTimingKeys[chartKey];
	return -1;
}

/**
 * Set the timing key dataset index and refresh the picker UI
 */
function chart_setanimtimingkey(chartKey, dsIndex){
	if (!document.chartAnimTimingKeys) document.chartAnimTimingKeys={};
	document.chartAnimTimingKeys[chartKey]=dsIndex;
	chart_renderanimtimingpicker(chartKey);
}

/**
 * Returns true if animation is currently running for this chart
 */
function chart_getanimrunning(chartKey){
	return !!(document.chartAnimRunning && document.chartAnimRunning[chartKey]);
}

/**
 * Render colored swatches for each dataset so the user can pick the timing series.
 * The selected swatch gets a white border; others have a transparent border.
 */
function chart_renderanimtimingpicker(chartKey){
	var pickerEl=gid(chartKey+'_animtimingpicker');
	if (!pickerEl) return;
	var chart=window._navChartInstances && window._navChartInstances[chartKey];
	if (!chart || !chart.data || !chart.data.datasets){pickerEl.innerHTML=''; return;}
	var curKey=chart_getanimtimingkey(chartKey);
	var html=[];
	for (var di=0; di<chart.data.datasets.length; di++){
		var ds=chart.data.datasets[di];
		var color=ds.backgroundColor||'#888888';
		var selected=(curKey===di);
		html.push('<span title="'+(ds.label||('series '+di))+'" onclick="chart_setanimtimingkey(\''+chartKey+'\','+di+');" '+
			'style="display:inline-block;width:14px;height:14px;background:'+color+';cursor:pointer;'+
			'margin-right:2px;border:2px solid '+(selected?'#ffffff':'transparent')+';vertical-align:middle;box-sizing:border-box;"></span>');
	}
	pickerEl.innerHTML=html.join('');
}

/**
 * Toggle play/stop animation for a chart
 */
function chart_animtoggle(chartKey){
	if (chart_getanimrunning(chartKey)){
		chart_animstop(chartKey);
	} else {
		chart_animstart(chartKey);
	}
}

/**
 * Start animation from frame 0 for the given chart.
 * Requires a timing series to be selected via chart_setanimtimingkey.
 */
function chart_animstart(chartKey){
	var timingKey=chart_getanimtimingkey(chartKey);
	if (timingKey<0) return; // no timing series selected yet
	if (!document.chartAnimRunning) document.chartAnimRunning={};
	if (!document.chartAnimTimers) document.chartAnimTimers={};
	document.chartAnimRunning[chartKey]=true;
	var btn=gid(chartKey+'_animbtn');
	if (btn) btn.innerHTML='&#9646;';
	// Hide bar labels while animating
	var chart=window._navChartInstances && window._navChartInstances[chartKey];
	if (chart){
		chart._animRunning=true;
		chart.update('none');
	}
	chart_animadvance(chartKey, 0);
}

/**
 * Stop the running animation, restore the scroll wrapper, and reset the play button.
 */
function chart_animstop(chartKey){
	if (document.chartAnimTimers && document.chartAnimTimers[chartKey]){
		clearTimeout(document.chartAnimTimers[chartKey]);
		delete document.chartAnimTimers[chartKey];
	}
	if (document.chartAnimRunning) delete document.chartAnimRunning[chartKey];
	// Restore scroll wrapper to normal and remove any transform on the inner wrapper
	var chartContainer=gid(chartKey);
	if (chartContainer){
		var scrollEl=chartContainer.querySelector('.chart-scroll-wrapper');
		if (scrollEl){
			// Commit the current visual position as a real scroll position,
			// then clear the transform so the element is back in the DOM flow
			var inner=scrollEl.querySelector('.chart-wrapper');
			if (inner){
				var tx=inner._animTranslateX||0;
				inner.style.transform='';
				inner._animTranslateX=0;
				scrollEl.style.overflowX='auto';
				scrollEl.scrollLeft=tx;
			} else {
				scrollEl.style.overflowX='auto';
			}
		}
	}
	// Restore bar labels
	var chart=window._navChartInstances && window._navChartInstances[chartKey];
	if (chart){
		chart._animRunning=false;
		chart.update('none');
	}
	var btn=gid(chartKey+'_animbtn');
	if (btn) btn.innerHTML='&#9654;';
}

/**
 * Advance the animation to frame f, scroll the bar into the center of the
 * visible area, read the timing bar's count value (* 10ms) as the delay,
 * then schedule the next frame.  Loops continuously.
 */
function chart_animadvance(chartKey, frame){
	if (!chart_getanimrunning(chartKey)) return;
	var chart=window._navChartInstances && window._navChartInstances[chartKey];
	if (!chart){chart_animstop(chartKey); return;}
	var timingKey=chart_getanimtimingkey(chartKey);
	if (timingKey<0){chart_animstop(chartKey); return;}
	var ds=chart.data.datasets[timingKey];
	if (!ds || !ds.data || ds.data.length===0){chart_animstop(chartKey); return;}
	var numFrames=ds.data.length;
	var f=frame % numFrames;

	// Extract the timing value for this frame.
	// If the timing series has no data here (null/undefined/0) this is not a
	// real frame — skip it immediately without scrolling.
	var countVal=ds.data[f];
	if (countVal!==null && typeof countVal==='object') countVal=countVal.y||countVal.v||0;
	// Values of 0 or 1 are used for series ranking only, not frame timing.
	// Only treat a frame as real if the raw value is > 1.
	var hasData=(countVal!==null && countVal!==undefined && countVal>1);

	if (hasData){
		// Real frame: snap the view to this bar's left edge via CSS transform,
		// then hold for count*10ms before advancing.
		var meta=chart.getDatasetMeta(timingKey);
		if (meta && meta.data && meta.data[f]){
			var barMeta=meta.data[f];
			var barX=barMeta.x;
			var barHalfWidth=(barMeta.width||0)/2;
			var targetLeft=Math.max(0, Math.round(barX - barHalfWidth));
			var chartContainer=gid(chartKey);
			if (chartContainer){
				var scrollEl=chartContainer.querySelector('.chart-scroll-wrapper')||chartContainer;
				var inner=scrollEl.querySelector('.chart-wrapper');
				if (inner){
					scrollEl.style.overflowX='hidden';
					scrollEl.scrollLeft=0;
					inner.style.transition='none';
					inner.style.transform='translateX(-'+targetLeft+'px)';
					inner._animTranslateX=targetLeft;
				}
			}
		}
	}

	if (!document.chartAnimTimers) document.chartAnimTimers={};
	if (hasData){
		// Hold this frame for count*10ms, then advance
		var delay=Math.round(countVal*10);
		document.chartAnimTimers[chartKey]=setTimeout(function(){
			chart_animadvance(chartKey, f+1);
		}, delay);
	} else {
		// No data at this index — skip to next frame immediately
		document.chartAnimTimers[chartKey]=setTimeout(function(){
			chart_animadvance(chartKey, f+1);
		}, 0);
	}
}

// -- End animation functions ------------------------------------------------

/**
 * Render color swatches in the palette container
 * @param {string} chartKey - The chart identifier (e.g., 'nav_1234_chartview_status')
 */
function chart_renderpaletteswatches(chartKey){
	// Derive palette container ID from chartKey
	// chartKey is like: nav_1234_chartview_status
	// container ID should be: nav_1234_chartpalette_status_swatches
	var parts=chartKey.split('_chartview_');
	if (parts.length!==2) return;
	var swatchContainerId=parts[0]+'_chartpalette_'+parts[1]+'_swatches';
	var container=gid(swatchContainerId);
	if (!container) return;

	var palette=chart_getpalette(chartKey);
	var html=[];

	// If chart instance exists, show swatches matching series order with labels
	if (window._navChartInstances && window._navChartInstances[chartKey]){
		var chart=window._navChartInstances[chartKey];
		var datasets=chart.data.datasets;
		var chartType=chart.config.type;

		if (chartType==='doughnut' || chartType==='pie'){
			// For pie/doughnut, colors are per data point in first dataset
			var labels=chart.data.labels || [];
			var colors=datasets[0]?datasets[0].backgroundColor:[];
			for (var i=0;i<labels.length && i<colors.length;i++){
				var color=colors[i];
				var label=labels[i] || ('Color '+(i+1));
				html.push('<img src="imgs/t.gif" style="width:16px;height:16px;background:'+color+';cursor:pointer;margin-right:2px;" onclick="chart_pickcolor(\''+chartKey+'\','+i+');" title="'+label+': '+color+'">');
			}
		} else {
			// For bar/line charts, colors are per dataset (series)
			for (var i=0;i<datasets.length;i++){
				var color=datasets[i].backgroundColor;
				if (Array.isArray(color)) color=color[0]; // take first if array
				var label=datasets[i].label || ('Series '+(i+1));
				html.push('<img src="imgs/t.gif" style="width:16px;height:16px;background:'+color+';cursor:pointer;margin-right:2px;" onclick="chart_pickcolor(\''+chartKey+'\','+i+');" title="'+label+': '+color+'">');
			}
		}
	} else {
		// Fallback: show default palette order
		for (var i=0;i<palette.length;i++){
			html.push('<img src="imgs/t.gif" style="width:16px;height:16px;background:'+palette[i]+';cursor:pointer;margin-right:2px;" onclick="chart_pickcolor(\''+chartKey+'\','+i+');" title="'+palette[i]+'">');
		}
	}
	container.innerHTML=html.join('');
}

/**
 * Reset palette to defaults
 * @param {string} chartKey - The chart identifier
 */
function chart_resetpalette(chartKey){
	chart_setpalette(chartKey, null);
	chart_renderpaletteswatches(chartKey);
}

/**
 * Render background color swatch in the palette container
 * @param {string} chartKey - The chart identifier
 */
function chart_renderbackgroundswatch(chartKey){
	var parts=chartKey.split('_chartview_');
	if (parts.length!==2) return;
	var bgContainerId=parts[0]+'_chartpalette_'+parts[1]+'_bg';
	var container=gid(bgContainerId);
	if (!container) return;

	var bgColor=chart_getbackgroundcolor(chartKey) || 'transparent';
	var displayColor=(bgColor==='transparent')?'#ffffff':bgColor;
	var borderStyle=(bgColor==='transparent')?'1px dashed #999':'none';
	container.innerHTML='<img src="imgs/t.gif" style="width:16px;height:16px;background:'+displayColor+';border:'+borderStyle+';cursor:pointer;" onclick="chart_pickbackground(\''+chartKey+'\');" title="'+(bgColor==='transparent'?'none':bgColor)+'">';
}

/**
 * Reset background color to default (transparent)
 * @param {string} chartKey - The chart identifier
 */
function chart_resetbackground(chartKey){
	chart_setpalette(chartKey, undefined, null);
	chart_renderbackgroundswatch(chartKey);
}

/**
 * Open color picker to change background color
 * @param {string} chartKey - The chart identifier
 */
function chart_pickbackground(chartKey){
	var currentColor=chart_getbackgroundcolor(chartKey) || '#ffffff';
	var _chartKey=chartKey;

	// Load Pickr CSS if not loaded
	if (!document.pickrCssLoaded){
		var link=document.createElement('link');
		link.rel='stylesheet';
		link.href='pickr.nano.css';
		document.head.appendChild(link);
		document.pickrCssLoaded=true;
	}

	// Load Pickr JS on demand and show picker
	xajxjs('Pickr','pickr.min.js',function(){
		var pickerBtn=document.createElement('span');
		pickerBtn.id='_chart_bg_picker_btn';
		pickerBtn.style.position='fixed';
		pickerBtn.style.left='-9999px';
		document.body.appendChild(pickerBtn);

		var pickr=Pickr.create({
			el:'#_chart_bg_picker_btn',
			theme:'nano',
			default:currentColor,
			lockOpacity:true,
			useAsButton:true,
			position:'bottom-middle',
			components:{
				preview:true,
				hue:true,
				interaction:{
					input:true,
					save:true,
					cancel:true
				}
			}
		});

		var cleanup=function(){
			pickr.hide();
			pickr.destroyAndRemove();
			if (pickerBtn.parentNode) pickerBtn.parentNode.removeChild(pickerBtn);
		};

		pickr.on('save',function(color){
			if (color){
				var hex=color.toHEXA().toString();
				chart_setpalette(_chartKey, undefined, hex);
				chart_renderbackgroundswatch(_chartKey);
			}
			cleanup();
		});

		pickr.on('cancel',function(){
			cleanup();
		});

		pickr.show();
	});
}

/**
 * Open color picker to change a specific color in the palette
 * @param {string} chartKey - The chart identifier
 * @param {number} colorIndex - Index of the color to change
 */
function chart_pickcolor(chartKey, colorIndex){
	var palette=chart_getpalette(chartKey);
	var currentColor=palette[colorIndex] || '#7cb5ec';

	// Capture variables for closure
	var _chartKey=chartKey;
	var _colorIndex=colorIndex;

	// Load Pickr CSS if not loaded
	if (!document.pickrCssLoaded){
		var link=document.createElement('link');
		link.rel='stylesheet';
		link.href='pickr.nano.css';
		document.head.appendChild(link);
		document.pickrCssLoaded=true;
	}

	// Load Pickr JS on demand and show picker
	xajxjs('Pickr','pickr.min.js',function(){
		// Create a temporary button element for the picker
		var pickerBtn=document.createElement('span');
		pickerBtn.id='_chart_color_picker_btn';
		pickerBtn.style.position='fixed';
		pickerBtn.style.left='-9999px';
		document.body.appendChild(pickerBtn);

		var pickr=Pickr.create({
			el:'#_chart_color_picker_btn',
			theme:'nano',
			default:currentColor,
			lockOpacity:true,
			useAsButton:true,
			position:'bottom-middle',
			components:{
				preview:true,
				hue:true,
				interaction:{
					input:true,
					save:true,
					cancel:true
				}
			}
		});

		var applyColor=function(color){
			if (color){
				var hex=color.toHEXA().toString();
				// Merge new color into palette
				var newPalette=chart_getpalette(_chartKey);
				newPalette[_colorIndex]=hex;
				chart_setpalette(_chartKey, newPalette);
				chart_renderpaletteswatches(_chartKey);
			}
		};

		var cleanup=function(){
			pickr.hide();
			pickr.destroyAndRemove();
			if (pickerBtn.parentNode) pickerBtn.parentNode.removeChild(pickerBtn);
		};

		pickr.on('save',function(color){
			applyColor(color);
			cleanup();
		});

		pickr.on('cancel',function(){
			cleanup();
		});

		pickr.show();
	});
}

