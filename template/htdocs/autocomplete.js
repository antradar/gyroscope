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
		if (v2c){
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
	
	if (document.hotspot){
		document.onclick=document.hotspot.lastonclick;
		document.hotspot.lastonclick=null;
	}	

	var d=document.hotspot;
		
	var sels=[]
	var os=gid('lkvc').getElementsByTagName('input');
	
	if (document.iphone_portrait&&d.id&&gid(d.id+'_lookup')) os=gid(d.id+'_lookup').getElementsByTagName('input');
	
	if (!sf.allchecked){
		for (var i=0;i<os.length;i++) if (os[i].className=='lksel') {os[i].checked='checked';sels.push(os[i].value);}
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
		if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
		if (gid(c).valuecount) for (var i=0;i<gid(c).valuecount;i++) delete gid(c)['value'+(i+2)];
		return;	
	}
	
	if (gid(c)) {gid(c).disabled=''; gid(c).value='';gid(c).focus();}
	if (gid(c+'_val2')) gid(c+'_val2').innerHTML='';
	if (gid(c).valuecount) for (var i=0;i<gid(c).valuecount;i++) delete gid(c)['value'+(i+2)];
	
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&document.hotspot.onchange) document.hotspot.onchange();
		
}

listlookup=function(d,title,command,mini,data){
	if (document.iphone_portrait) mini=1;
	if (document.tabafloat) mini=1;
	if (document.widen) mini=1;
	if (document.appsettings.uiconfig.force_inline_lookup) mini=1;
	if (document.hotspot&&document.hotspot.id) document.hotspot=gid(document.hotspot.id);
	if (document.hotspot&&!d) d=document.hotspot;
	
	if (!document.appsettings.quicklist) mini=1;
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
		ajxpgn(d.id+'_lookup_view',document.appsettings.codepage+'?cmd='+command,0,0,data);	
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
		ajxpgn('lkvc',document.appsettings.codepage+'?cmd='+command,0,0,data);
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
		
		ajxpgn('lv'+view,document.appsettings.codepage+'?cmd='+command,true,true,data);
		
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
		
		console.log('lkv dismounted');
	}	
}

lkv_remount=function(){
	if (gid('lkv_origin')){
		gid('lkv_origin').innerHTML=gid('lkv').innerHTML;
		gid('lkv').parentNode.removeChild(gid('lkv'));
		gid('lkv_origin').id='lkv';
		gid('lkvc').style.height=(ch()-187-33)+'px';
		document.lkvdismounted=null;
		console.log('lkv remounted');
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

lookupentity=function(d,entity,title,data,mini){
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

	listlookup(d,title,'lookup'+entity+'&key='+gval,mini,data);	
}

_lookupentity=function(d,entity,title,data,mini){
	if (d.disabled) return;
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookupentity(d,entity,title,data,mini);
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

nav_multiorids=function(fieldname){
	var os=gid('navfilters_'+fieldname).getElementsByTagName('input');
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
	var resids=nav_multiorids(fieldname);
	var ids=resids.ids;
	var ocount=resids.ocount;
	
	if (gid('multior_'+fieldname).oidlen==null) {
		gid('multior_'+fieldname).oidlen=ids.length;
		if (d.checked) gid('multior_'+fieldname).oidlen--;
	}
	if (ids.length>0) gid('multior_'+fieldname).style.visibility='visible'; else gid('multior_'+fieldname).style.visibility='hidden';
	if (ids.length==0&&(gid('multior_'+fieldname).oidlen>0||ocount==1)) nav_applymultior(container,fieldname,keyid,cmd,filter,bingo);	
}

nav_applymultior=function(container,fieldname,keyid,cmd,filter,bingo){
	var resids=nav_multiorids(fieldname);
	var ids=resids.ids;
	filter+='&multior_'+fieldname+'='+ids.join('||');
	nav_setfilter(container,keyid,cmd,filter,bingo);
}

nav_loadcharts=function(container,keyid,cmd,bingo){

	xajxjs('google.charts','https://www.gstatic.com/charts/loader.js?',function(){
		
	
		
		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(function(){
			
			var cf=function(c,d){return function(){
				var sel=c.getSelection()[0];
				if (sel){
					nav_setfilter(container,keyid,cmd,d[sel.row]['f'],bingo);
				}
								
			}};				
			
			var charts=eval('('+gid(container+'_chartdata').value+')');
			if (charts==null) return;
			for (var i=0;i<charts.length;i++){
				if (!gid(container+'_chartview_'+charts[i].fieldname)) continue;
				gid(container+'_chartview_'+charts[i].fieldname).style.display='block';
				switch (charts[i].type){
				case 'pie':
					var data = new google.visualization.DataTable();
					data.addColumn('string','Type');
					data.addColumn('number','Count');
					var rows=[];
					for (var j=0;j<charts[i].counts.length;j++){
						rows.push([charts[i].counts[j]['n'],charts[i].counts[j]['a']]);
					}//for each count
										
					data.addRows(rows); 				
					var chart = new google.visualization.PieChart(gid(container+'_chart_'+charts[i].fieldname));
					

					
					google.visualization.events.addListener(chart, 'select', cf(chart,charts[i].counts));
					
		        	chart.draw(data, 
		        	{
			        'title':charts[i].title,
			        'sliceVisibilityThreshold':0.02
		        	});
	        	break;
	        	case 'column':
	        	
					var rows=[];
					
					rows.push(['Amount','Count']);
					
					if (charts[i].counts.length==0){
						gid(container+'_chartview_'+charts[i].fieldname).style.display='none';						
						continue;
					}
										
					for (var j=0;j<charts[i].counts[0].length;j++){
						var xlabel=charts[i].counts[0][j]['min']+' to '+charts[i].counts[0][j]['max'];
						if (charts[i].counts[0][j]['xlabel']&&charts[i].counts[0][j]['xlabel']!='') xlabel=charts[i].counts[0][j]['xlabel'];
						if (xlabel!='null to null') rows.push([xlabel,charts[i].counts[0][j]['count']]);
					}//for each count
						        	
					var data = new google.visualization.arrayToDataTable(rows);
					
					
					var chart = new google.visualization.ColumnChart(gid(container+'_chart_'+charts[i].fieldname));
										
					
					google.visualization.events.addListener(chart, 'select', cf(chart,charts[i].counts[0]));					
					
		        	chart.draw(data, 
		        	{
			        'title':charts[i].title,
			        'legend':{position:'none'}
		        	});
	        	
	        	break;
	        	
        		}//switch chart type
        	
    		}//for each chart
        				
		});
	});	
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

/*

// highcharts.js is not bundled
// use the following version for a standalone product, i.e. no dependency on Google
// additional licencing may be necessary

nav_loadcharts=function(container,keyid,cmd,bingo){
	
	xajxjs('Highcharts.BubbleLegend','highcharts.js?cv=8',function(){ //use Highcharts.chart for simpler versions
	
			var cf=function(d){return function(){
				var sel=this.index;
				if (sel!=null){
					nav_setfilter(container,keyid,cmd,d[sel]['f'],bingo);
				}
								
			}};		
							
			var charts=eval('('+gid(container+'_chartdata').value+')');
			if (charts==null) return;
			for (var i=0;i<charts.length;i++){
				if (!gid(container+'_chartview_'+charts[i].fieldname)) continue;
				gid(container+'_chartview_'+charts[i].fieldname).style.display='block';
				
				var subdims=charts[i].subdims;
													
				switch (charts[i].type){
				case 'pie':
				
					var rows=[];
					var amounts=[];
					var allsame=1;
					
					for (var j=0;j<charts[i].counts.length;j++){
						var row=[];
						var arow=[];
						
						row={name:charts[i].counts[j]['n'],y:charts[i].counts[j]['c']};
						arow={name:charts[i].counts[j]['n'],y:charts[i].counts[j]['a']};
						if (charts[i].counts[j]['c']!=charts[i].counts[j]['a']) allsame=0;
						rows.push(row);
						amounts.push(arow);
					}//for each count
										
					
					var series=[];
					
					series.push(
							{name: 'Count', data: rows,
							size: allsame?'55%':'40%',
							dataLabels: {enabled:allsame?true:false},
							point:{events:{click: cf(charts[i].counts)}}
							}					
					);
					
					if (!allsame){
						series.push(
							{
							name: 'Amount',
							data: amounts,
							size: '50%',
							innerSize: '65%',
							point:{events:{click: cf(charts[i].counts)}}
							}
						);	
					}
					
					Highcharts.chart(container+'_chartview_'+charts[i].fieldname,{
						chart: {
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false,
							type: 'pie'
						},
						title: {text: charts[i].title},
					    tooltip: {
					      pointFormat: '<b>{series.name}</b><br>{point.y} ({point.percentage:.1f}%)'
					    },						
						plotOptions: {
							pie: {
								shadow: false,
								center: ['50%', '50%']
							},
							series:{
								animation:false	
							}
						},											
						series: series					
					});
										
	        	break;
	        	case 'column':
	        	
					var series=[];
					var xses=[];
					var xaxis;
										
					if (charts[i].counts.length==0){
						gid(container+'_chartview_'+charts[i].fieldname).style.display='none';						
						continue;
					}
													
					charts[i].counts=charts[i].counts.sort(function(a,b){
						var suma=0; for (var ia=0;ia<a.length;ia++) suma+=a[ia].count;
						var sumb=0; for (var ib=0;ib<b.length;ib++) sumb+=b[ib].count;
						if (suma==sumb) return 0;
						if (suma<sumb) return 1; else return -1;
					});	
							
					for (var sidx=0;sidx<charts[i].counts.length;sidx++){
						if (sidx>10) break;
						var rows=[];
			        	var xs=[];
						
						for (var j=0;j<charts[i].counts[sidx].length;j++){
							var xlabel=charts[i].counts[sidx][j]['min']+' to '+charts[i].counts[sidx][j]['max'];
							if (charts[i].counts[sidx][j]['xlabel']&&charts[i].counts[sidx][j]['xlabel']!='') xlabel=charts[i].counts[sidx][j]['xlabel'];
							if (xlabel!='null to null') {
								xs.push(xlabel);
								rows.push(charts[i].counts[sidx][j]['count']);
							}
						}//for each count
						
						if(charts[i].counts[sidx][0]) {
							series.push(
								{
								name: charts[i].counts[sidx][0]['kn'],
								data: rows,
								point:
									{
									events:{
										click: cf(charts[i].counts[sidx])
									}
									}						
								}						
							);
							xses.push(
								{visible:true,categories:xs}
							);
						}
					}//for each series
					
					if (charts[i].counts.length<=1){
						xaxis={
							categories: xs,
							crosshair: true
						};
					} else {
						xaxis=xses;						
					}				
										
					var dimlabel='';
					switch (charts[i].dimmode){
						case 's': dimlabel='Sum'; break;
						case 'a': dimlabel='Average'; break;
						default: dimlabel='Count';
					}
					
					var charttype='column';
					if (charts[i].dimkey!=null&&charts[i].dimkey!='') charttype='area';
										
					Highcharts.chart(container+'_chartview_'+charts[i].fieldname,{
						chart: {
							type: charttype,
						},
						title: {text: charts[i].title},
						
						xAxis: xaxis,
						yAxis: {
							min: 0,
							title: {
								text: dimlabel
							}
						},		
					    plotOptions: {
					        area: {
					            stacking: 'percent', //normal
					            lineColor: '#666666',
					            lineWidth: 1,
					            marker: {
					                lineWidth: 1,
					                lineColor: '#666666'
					            }
					        },
							series:{
								animation:false	
							}
					    },
					    legend:{ enabled:false },								
						series: series					
					});		
					
					//if (subdims!=null){
						var chartview=gid(container+'_chartview_'+charts[i].fieldname);
						var toolanchor=document.createElement('span');
						toolanchor.innerHTML='<a onclick="showhide(\''+container+'_charttoolbar_'+charts[i].fieldname+'\');">..</a>';
						toolanchor.style.position='absolute'; toolanchor.style.top='10px';toolanchor.style.left='40px';
						toolanchor.style.background='#444444'; toolanchor.style.color='#ffffff'; toolanchor.style.borderRadius='3px';
						toolanchor.style.padding='1px 4px';
						
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
														
						thtml.push('</div>');	
						toolbar.innerHTML=thtml.join('');
						if (charts[i].dimmode!=null&&charts[i].dimmode!='') chartview.appendChild(toolanchor);
						chartview.appendChild(toolbar);
					//}						        	
											        	
	        	break;
	        	
       	
	        	
	        	case 'bubble':
	        											
					if (charts[i].counts.length==0){
						gid(container+'_chartview_'+charts[i].fieldname).style.display='none';						
						continue;
					}
					
					var xs=[];
					var rows=[];
										
					for (var j=0;j<charts[i].counts.length;j++){
						var xlabel=charts[i].counts[j]['x'];
						if (charts[i].counts[j]['xlabel']&&charts[i].counts[j]['xlabel']!='') xlabel=charts[i].counts[j]['xlabel'];
						if (xlabel!='null') {
							xs.push(xlabel);			
							rows.push([charts[i].counts[j]['x'],charts[i].counts[j]['y'],charts[i].counts[j]['z']]);
						}
					}//for each count
															
					Highcharts.chart(container+'_chartview_'+charts[i].fieldname,{
						chart: {
							type: 'bubble',
							zoomType: 'xy'
						},
						title: {text: charts[i].title},
						xAxis: {
							//categories: xs,
							crosshair: true,
							title: {
								text: charts[i].title
							}						
						},
						yAxis: {
					        startOnTick: false,
					        endOnTick: false,
							title: {
								text: charts[i].title2
							}
						},						
						series: [
							{
							showInLegend: false,
							data: rows,
							point:
								{
								events:{
									click: cf(charts[i].counts)
								}
								}						
							}
						]					
					});					
						        	
											        	
	        	break;	        	
	        	
        		}//switch chart type
        	
    		}//for each chart
        				
	});
	
}

*/
