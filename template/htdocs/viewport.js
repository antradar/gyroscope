function ch(){
  var w=cw();
  //if (w*0.85<=485) return 270/0.85;
  if (window.innerHeight) return window.innerHeight;
  if (document.documentElement.clientHeight) return document.documentElement.clientHeight;
  return document.body.clientHeight;
}

function cw(){
  if (window.innerWidth) return window.innerWidth;
  if (document.documentElement.clientWidth) return document.documentElement.clientWidth;
  return document.body.clientWidth;
}

function tabw(){
	var idw=cw();
	if (!document.tabafloat&&!document.widen) return idw-gid('tabviews').offsetLeft-40;
	return idw-40;	
}

function tabh(){
	var idh=ch();
	if (!document.tabafloat) return idh-gid('tabtitles').offsetTop;
	return idh;	
		
}

function scaleall(root){
	
	//defer
  if (document.scalealllock) {return;}
  document.scalealllock=true;
  if (document.scalealltimer) clearTimeout(document.scalealltimer);
  document.scalealltimer=setTimeout(function(){
	delete document.scalealllock; 
  },10);  
    
  var i,j;
  var idh=ch();
  var idw=cw();
  
  var os=root.getElementsByTagName('div');

  var tabviewheight=147;
  
  if (document.appsettings.uiconfig.toolbar_position=='left') tabviewheight=82;
    
  gid('lefticons').style.width=idw+'px';
  gid('leftview').style.height=(idh-147)+'px';
  gid('leftview_').style.height=(idh-147)+'px';
  gid('vsptr').style.height=idh-146+'px';
  
  if (gid('tabexpander')&&document.currenttab&&!document.tabafloat) gid('tabexpander').style.top=document.tabviews[document.currenttab].offsetParent.offsetTop+'px';
  
  if (document.tabafloat||!document.appsettings.quicklist||document.fsshowing){
	  var w=idw;
	  if (w>400) w=400;
	  gid('lkv').style.width=w+'px';
	  if (!gid('lkv').moved){
	  	gid('lkv').style.left=(idw-w)/2+'px';
  	  } else {
	  	if (gid('lkv').offsetLeft+w+5>idw) gid('lkv').style.left=idw-w-5+'px';	  
  	  }
	  gid('lkv').style.height=(idh-40)+'px';
      gid('lkvc').style.height=(idh-40-33)+'px';
      
      if (gid('lkv').showing) {
	      if (!gid('lkv').moved){
		      gid('lkv').style.top='20px';
	      } else {
		      
	      }
  	  } else {
		  gid('lkv').style.top=-1*(idh-40)-20+'px';
      }
      
  } else {
	  gid('lkv').style.height=(idh-187)+'px';
      gid('lkvc').style.height=(idh-187-33)+'px';
  }
  
  if (document.appsettings.uiconfig.toolbar_position=='top') {
	  if (document.appsettings.quicklist) {
		  gid('tabtitles').style.left='295px';
		  gid('tabtitles').style.width=(idw-296)+'px';
  	  } else {
		  gid('tabtitles').style.left='20px';
	  	  gid('tabtitles').style.width=(idw-20)+'px';
  	  }
  }
  
  if (!document.widen) {
	  if (document.appsettings.uiconfig.toolbar_position=='left') gid('tabviews').style.width=(idw-261)+'px';
	  else {
		  if (document.appsettings.quicklist) gid('tabviews').style.width=(idw-296)+'px';
		  else gid('tabviews').style.width=(idw-20)+'px';
	  }
 } else {
	 gid('tabviews').style.width=(idw-1)+'px';
 }
  

  gid('tabviews').style.height=(idh-tabviewheight)+'px';
  gid('mainmenu').style.height=(idh-tabviewheight)+'px';
  gid('bookmarkview').style.height=(idh-tabviewheight)+'px';
  
  gid('statusinfo').style.top=(idh-25)+'px';
  gid('statusinfo').style.width=idw+'px';
  
  gid('fsmask').style.width=idw+'px';
  gid('fsmask').style.height=idh-25+'px';
  gid('fsview').style.width=idw-20+'px';
  gid('fsview').style.height=idh-70+'px';
  
  gid('fstitlebar').style.width=idw-20+'px';
  
  gid('iconbelt').style.width=idw-gid('applogo').offsetWidth-gid('logoutlink').offsetWidth-120+'px';
  
  var beltwidth=0;
  var items=gid('topicons').getElementsByTagName('a');
  for (var i=0;i<items.length;i++) beltwidth+=items[i].offsetWidth+22;
  gid('topicons').style.width=beltwidth+'px';
  
  if (gid('topicons').offsetWidth>gid('iconbelt').offsetWidth) {
	gid('beltprev').style.display='block';
	gid('beltnext').style.display='block';	  
  } else {
	gid('beltprev').style.display='none';
	gid('beltnext').style.display='none';
	gid('topicons').style.left=0;
	gid('topicons').beltidx=0;
	gid('beltprev').style.visibility='hidden';	  
	gid('beltnext').style.visibility='visible';	
	  
  }

  if (gid('topicons').offsetWidth+parseInt(gid('topicons').style.left.replace('px',''),10)>gid('iconbelt').offsetWidth) gid('beltnext').style.visibility='visible'; 
  else gid('beltnext').style.visibility='hidden';
  
  if (gid('mastersearch')){
    //sync with autocomplete.js
    w=(idw-gid('logoutlink').offsetWidth)*3/4;
    if (w>520) w=520;
    if (!document.mainsearch) w=gid('mastersearchshadow').offsetWidth;	
	if (w<gid('mastersearchshadow').offsetWidth) w=gid('mastersearchshadow').offsetWidth;
	gid('mastersearch').style.width=w+'px';
	
	gid('mainsearchview_').style.right=gid('logoutlink').offsetWidth-gid('mastersearchshadow').offsetWidth+26+'px';
	gid('mainsearchview_').style.width=(gid('mastersearch').offsetWidth-12)+'px';
	gid('mainsearchview_').style.maxHeight=(idh-150)+'px';
  }	  

  var menutop=0;
  if (document.tabviews[document.currenttab].offsetParent) menutop=document.tabviews[document.currenttab].offsetParent.offsetTop;
  
  //also in tabs.js: toggletabdock
  for (var i=0;i<os.length;i++){
    var node=os[i];
    if (node.scalech) node.style.height=(idh-node.scalech)+'px';
    if (node.scalerch){
	 	node.style.height=(idh-menutop-node.scalerch)+'px';   
    }
  }
  
  if (gid('gschat_chatbox')){
		var uptake=170; //space taken by other components
		gid('gschat_chatbox').style.maxHeight=(idh-uptake)+'px';
  }
  
  rescaletabs();
  
}

function rescaletabs(){
  if (!document.resizefuncs) return;
  for (func in document.resizefuncs){
	var f='tabresizefunc_'+func;
	if (self[f]) self[f](document.resizefuncs[func]);	  
  }	
}

function warnsyslow(syslow){
	if (!gid('sysreswarn')) return;
	if (syslow) gid('sysreswarn').style.display='inline'; else gid('sysreswarn').style.display='none';
	
	//console.log("System resource critical low: "+document.nanoavg);	
}

function scrollcoldash(container,colkey){
	gid(container+'_view').scrollLeft=gid(container+'_'+colkey).offsetLeft;	
}

function beltprev(){
	var topicons=gid('topicons');
	if (!topicons.beltidx) topicons.beltidx=0;
	topicons.beltidx--;
	if (topicons.beltidx<0) topicons.beltidx=0;
	if (topicons.beltidx==0) gid('beltprev').style.visibility='hidden';
	topicons.style.left=-100*topicons.beltidx+'px';
	gid('beltnext').style.visibility='visible';	
}

function beltnext(){
	var topicons=gid('topicons');
	if (!topicons.beltidx) topicons.beltidx=0;
	topicons.beltidx++;
	topicons.style.left=-100*topicons.beltidx+'px';	
	gid('beltprev').style.visibility='visible';
	if (topicons.offsetWidth+parseInt(topicons.style.left.replace('px',''),10)>gid('iconbelt').offsetWidth) gid('beltnext').style.visibility='visible';
	else gid('beltnext').style.visibility='hidden';

}

function showfs(func,initfunc){
	document.fsshowing=true;
	scaleall(document.body);
	gid('fsmask').style.display='block';
	gid('fstitlebar').style.display='block';
	gid('fsview').style.display='block';
	lkv_dismount();
	gid('fsclose').closeaction=func;
	if (initfunc) initfunc();
}

function closefs(){
	document.fsshowing=null;
	gid('fsview').style.display='none';
	gid('fstitlebar').style.display='none';
	gid('fsmask').style.display='none';
	
	if (!document.tabafloat&&document.appsettings.quicklist) lkv_remount();

	if (gid('fsclose').closeaction) gid('fsclose').closeaction();	
}

function loadfs(title,cmd,func,initfunc,bingo){
	var codepage=document.appsettings.codepage;
	if (bingo) codepage=document.appsettings.binpages[bingo+''];
	ajxpgn('fsview',codepage+'?cmd='+cmd,1,0,'',function(){
		gid('fstitle').innerHTML=title;	
		showfs(func,initfunc);	
	});
}


function autosize(){
  if (document.autosizing) clearTimeout(document.autosizing);
  document.autosizing=setTimeout(function(){
	  scaleall(document.body);
	  var caleview=gid('caleview');
	  if (caleview){
	
	  }
	  if (document.tabcount>0){
	  var t=document.tabtitles[document.tabcount-1];
	  var topmargin=0; //change this if changing tab style
	  var tabbase=122;
	  var tabviewheight=147;
	  if (document.appsettings.uiconfig.toolbar_position=='left'){
		  tabbase=57;
		  tabviewheight=82;
	  }
	  
	  if (document.appsettings.uiconfig.toolbar_position=='left') tab_reflow();  
	  
	  
	//wrapping
	      document.rowcount=(t.offsetTop-topmargin)/38+1;
	      if (!document.lastrowcount) document.lastrowcount=1;
	      if (document.lastrowcount!=document.rowcount) {
	        gid('tabtitles').style.height=38*document.rowcount+'px';
	        gid('tabviews').style.top=tabbase+38*(document.rowcount-1)+'px';
	        gid('tabviews').scalech=tabviewheight+38*(document.rowcount-1);

	        if (document.appsettings.uiconfig.toolbar_position=='left'){
	         gid('mainmenu').style.top=tabbase+38*(document.rowcount-1)+'px';
   		     gid('bookmarkview').style.top=tabbase+38*(document.rowcount-1)+'px';
		   	 gid('mainmenu').style.height=38*document.rowcount+'px';
		   	 gid('bookmarkview').style.height=38*document.rowcount+'px';
	         gid('mainmenu').scalech=tabviewheight+38*(document.rowcount-1);
	         gid('bookmarkview').scalech=tabviewheight+38*(document.rowcount-1);
	    	}
	        	       
	      }
	      scaleall(document.body);
	      document.lastrowcount=document.rowcount;
	  }
  },50);

}

function hintstatus(d,t){
  if (document.hinttimer) clearTimeout(document.hinttimer);
  gid('statusc').innerHTML='<a>'+t+'</a>';
  d.onmouseout=function(){
    gid('statusc').innerHTML='';
  }
}

function flashstatus(t,l){
	if (l){
		if (document.hinttimer) clearTimeout(document.hinttimer);
		gid('statusc').innerHTML=t;
		document.hinttimer=setTimeout(function(){gid('statusc').innerHTML='';},l);
	}

	if (window.Notification){
		if (document.lastnotification==t) return;
		document.lastnotification=t;
		var n=new Notification('Gyroscope',{body:t});
		if (l) setTimeout(function(){n.close();document.lastnotification=null;},l); 
		setTimeout(function(){document.lastnotification=null;},10000);
	}
}

function flashsticker(msg,sec){
	if (document.stickertimer) clearTimeout(document.stickertimer);
	gid('gsstickercontent').innerHTML=msg;
	gid('gsstickerview').style.display='table';
	if (sec!=null){
		document.stickertimer=setTimeout(function(){
			gid('gsstickerview').style.display='none';
		},sec*1000);	
	}
		
}

function callout_section(d,keepstyle){
	if (d==null||!d) return;
	var callout=gid('callout');
	if (!callout) return;
	var rect=d.getBoundingClientRect();

	var h=ch()-100;
	
	var y=rect.y+10;
	if (y>h) y=h;
		
	callout.style.opacity=1;
	callout.style.filter='alpha(opacity=100)';
	callout.style.top=y+'px';
	callout.style.left=(rect.x-56)+'px';
		
	var cls=d.className;
	
	if (!keepstyle) d.className='calledout';
	setTimeout(function(){
		if (!keepstyle) d.className=cls;	
		callout.style.opacity=0;
	callout.style.filter='alpha(opacity=0)';
		callout.style.left=0;
		callout.style.top=h+'px';
	},500);
	
	
}

function reloadview(idx,listid,submenu){
	if (document.appsettings.uiconfig.toolbar_position=='left'){
		refreshtab('dash_'+idx.replace(/\./g,'__'),1);
		return;	
	}
	
	hidelookup();
	if (document.viewindex!=idx) return;
	
	var params='';
	if (gid('lv'+document.viewindex)) params=gid('lv'+document.viewindex).params;
	var bingo=gid('lv'+document.viewindex).bingo;

	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx,0,0,params,null,bingo);
}

function showview(idx,lazy,force,params,func,bingo,submenu){
	
  if (gid('defleftview')&&document.appsettings.quicklist) gid('defleftview').style.display='none';
  var codepage=document.appsettings.codepage;
  if (bingo>0) codepage=document.appsettings.binpages[bingo+''];
  if (!params) params='';
      
  if (document.appsettings.uiconfig.toolbar_position=='left'){
	  if (!submenu) reloadtab('welcome','','dash_'+idx.replace(/\./g,'__')+'&'+params,func);
	  else addtab('dash_'+idx.replace(/\./g,'__'),idx,'dash_'+idx.replace(/\./g,'__')+'&'+params,func);
	  return; 
  }
  
  
  if (!document.appsettings.quicklist||document.tabafloat){
	  addtab('dash_'+idx.replace(/\./g,'__'),idx,'dash_'+idx.replace(/\./g,'__')+'&'+params,func);
	  
		gid('lv'+idx).viewloaded=null;
		gid('lv'+idx).innerHTML='';
			
		if (document.viewindex==idx){		
			resetleftviews();
		}
	  
	  return;
  }
  
  //closetab('dash_'+idx.replace(/\./g,'__'));
  //instead of closing, keep the tab, skip the list view, and call out the tab
  var dashtabid=gettabid('dash_'+idx.replace(/\./g,'__'));
  if (dashtabid>=0){
	showtab(document.tabkeys[dashtabid]);
	callout_section(document.tabtitles[dashtabid],true);
	return;	  
  }
  
  gid('leftviewcloser').style.display='block';
  
  
  
  if (gid('gamepadspot')) gid('gamepadspot').vidx=null;
 	
  hidelookup();
    
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }
  
  
  if (gid('lv'+idx)) {
	  gid('lv'+idx).params=params;
	  gid('lv'+idx).bingo=bingo;
  }
 
 
  for (var k=0; k<document.appsettings.views.length;k++){
	var i=document.appsettings.views[k];
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
		if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded){
			if (document.lvxhr&&document.lvxhr.reqobj){
				document.lvxhr.abortflag=1;document.lvxhr.reqobj.abort(); document.lvxhr.reqobj=null;cancelgswi(document.lvxhr);
			}
			document.lvxhr=gid('lv'+i);
			ajxpgn('lv'+i,codepage+'?cmd=slv_'+i.replace(/\./g,'__')+'&'+params,true,true,'nocache=1',function(rq){
				var title=rq.getResponseHeader('listviewtitle');
				if (title!=null&&title!='') gid('tooltitle').innerHTML='<a>'+decodeHTML(title)+'</a>';
				var flag=rq.getResponseHeader('listviewflag');
				var js=rq.getResponseHeader('listviewjs');
				if (flag!=null&&js!=null&&js!=''){
					ajxjs(self[flag],js);
					//sajxjs(flag,js);
				}
				if (func) func();	
			});
		} else {
	      gid('lv'+idx).style.display='block';
	      if (gid('lv'+idx).tooltitle!=null&&gid('lv'+idx).tooltitle!='') gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;      
		}
    }
  }
  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
  
  if (document.appsettings.uiconfig.toolbar_position=='left'){
    gid('leftview').className='promoted bgflash';
    setTimeout(function(){gid('leftview').className='promoted bgready';},250);  
  } else {
    gid('leftview').className='bgflash';
    setTimeout(function(){gid('leftview').className='bgready';},250);  
  }
  
  if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();	
  
  if (document.widen) showtab('welcome');
  
}

function showlookup(){
	
	var lkv=gid('lkv');
	if (lkv.showing) return;
	
	
	if (gid('gamepadspot')) gid('gamepadspot').lookupview=true;
	
	lkv.showing=true;

	//gid('lkvc').style.background='#ffffc0';
	//setTimeout(function(){gid('lkvc').style.background='#ffffff';},200);
	
	if (document.tabafloat||document.fsshowing||!document.appsettings.quicklist){
		if (!lkv.moved){
			var w=400;
			if (w>cw()) w=cw();
			lkv.style.left=(cw()-w)/2+'px';
		}
		lkv.style.top='20px';
		lkv.style.height=ch()-40+'px';	
		gid('lkvc').style.height=ch()-40-33+'px';
	} else {
		lkv.style.width='258px';
		lkv.style.top='40px';
		lkv.style.left='0px';	
	}				
}

function hidelookup(keep_position){
	var lkv=gid('lkv');
	if (!lkv.showing) return;
	if (gid('gamepadspot')) gid('gamepadspot').lookupview=null;
	
	lkv.showing=null;
	if (!keep_position) lkv.moved=null;
	if (document.tabafloat||document.fsshowing||!document.appsettings.quicklist){
		h=ch()-40;
		lkv.style.top=-1*h-20+'px';
	} else {
		lkv.style.left='-280px';
	}	
}

function stackview(){ //used by auto-completes
	gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
	gid('lv'+document.viewindex).style.display='none';
	gid('lv'+(document.appsettings.views[document.appsettings.views.length-1])).style.display='block';
	document.viewindex=document.appsettings.views[document.appsettings.views.length-1];
}

function authpump(){
  var stamp=hb();
  var rq=xmlHTTPRequestObject();
  var f=function(){
    if (rq.readyState==4){
	    var xtatus=rq.getResponseHeader('X-STATUS');	    
	    if (rq.status==200||rq.status==304||rq.status==403||(xtatus|0)==403){
		     if (stamp!=rq.responseText){
			   if (self.skipconfirm) skipconfirm();  
		       window.location.reload();
		       return;
		     }
		     if (rq.getResponseHeader('wsskey')) document.wsskey=rq.getResponseHeader('wsskey');
 		}
 		
 		
    }
  }
  rq.open('POST',document.appsettings.codepage+'?cmd=pump&hb='+stamp,true);
  rq.onreadystatechange=f;
  rq.send('nocache='+stamp);
}



function sv(d,v,r){gid(d).value=v;if (r&&gid(d).onchange) gid(d).onchange();}

if (document.createEvent){
	document.keyboard=[];
	
	window.onblur=function(){
		document.keyboard=[];
		document.gamepadlock=true;	
	}	
	
	document.onkeyup=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;	
		document.keyboard['key_'+keycode]=null;
		delete document.keyboard['key_'+keycode];
	}
	
	document.onkeydown=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;	
		document.keyboard['key_'+keycode]=1;
		
		var metakey=0;
		if (document.keyboard['key_17']||document.keyboard['key_91']||document.keyboard['key_224']) metakey=1;
		
		if (document.keyboard['key_13']&&metakey){
			picktop();	
		}
			
		if (document.keyboard['key_83']&&metakey){
			savecurrenttab();
			return false;
		}
		
		if (document.keyboard['key_16']&&document.keyboard['key_70']&&document.keyboard['key_55']&&!document.fleetview){
			document.fleetview=true;
			updategyroscope();	
		}		
		
		if (metakey&&document.keyboard['key_190']&&document.keyboard['key_188']) toggletabdock();
		
		if (metakey&&document.keyboard['key_16']&&document.keyboard['key_82']) {refreshtab(document.tabkeys[document.currenttab]);return false;}
		if (!document.fsshowing&&metakey&&document.keyboard['key_16']&&document.keyboard['key_52']&&document.tabtitles[document.currenttab]!=null&&!document.tabtitles[document.currenttab].noclose) {
			if (!sconfirm('Are you sure you want to CLOSE the current tab?')) return;
			closetab(document.tabkeys[document.currenttab]);
		}
		if (document.fsshowing&&metakey&&document.keyboard['key_16']&&document.keyboard['key_52']) {
			closefs();
		}
		
		
	}

	function savecurrenttab(){
		if (document.currenttab==null||document.currenttab==-1) return;
		if (!document.tabviews[document.currenttab]) return;
		var bts=document.tabviews[document.currenttab].getElementsByTagName('button');
		var bt=null;
		for (var i=0;i<bts.length;i++){
			if (bts[i].className&&bts[i].className=='changebar_button'){
				bt=bts[i];	
			}
		}
		if (!bt) return;
		
		var event=document.createEvent('Events');
		event.initEvent('click',true,false);
		bt.dispatchEvent(event);		
		
	}
	
	function picktop(){
		document.keyboard=[];
		if (!document.hotspot) return;
		if (!gid('lkvc')) return;
		var os=gid('lkvc').getElementsByTagName('a');
		var target=null;
		for (var i=0;i<os.length;i++){
			var o=os[i];
			if (o.parentNode&&(o.parentNode.className=='listitem'||o.attributes.pickable)&&o.onclick!=null){
				target=o;
				break;	
			}
		}//for
		if (!target) return;
		
		var event=document.createEvent('Events');
		event.initEvent('click',true,false);
		target.dispatchEvent(event);
		
	}
}

function toggle_easyread(){
	if (!document.easyreading){
		ajxcss(null,'easyon.css','easyon','easyoff');
		document.easyreading=true;	
	} else {
		ajxcss(null,'easyoff.css','easyoff','easyon');
		document.easyreading=null;	
	}
}

function showhelpspot(id,once){
	showhide('helpspot_'+id);
	if (gid('helpspot_'+id).showing){
		gid('phelpspot_'+id).style.width='100%';
	} else {
		gid('phelpspot_'+id).style.width='auto';		
	}
}

function hidehelpspot(id,topic,once,gskey){
	gid('helpspot_'+id).style.display='none';
	if (once){
		gid('helpanchor_'+id).style.display='none';	
		ajxpgn('statusc',document.appsettings.codepage+'?cmd=ackhelpspot&topic='+encodeHTML(topic),0,0,null,null,null,null,gskey);
		var os=document.getElementsByTagName('span');
		for (var i=0;i<os.length;i++) if (os[i].attributes&&os[i].attributes.helptopic&&os[i].attributes.helptopic.value==topic) os[i].style.display='none';
	}
}

function showmainmenu(){
	gid('tooltitle').style.display='none';
	gid('leftview').style.display='none';
	gid('mainmenu').style.visibility='visible';
	
	if (!document.mainmenuloaded){	
		ajxpgn('mainmenu',document.appsettings.codepage+'?cmd=listwelcome');
		document.mainmenuloaded=true;
	}
	//reloadtab('welcome','','dash_default');

}

function loaddash(tabkey,title,cmd){
	if (document.appsettings.uiconfig.toolbar_position=='left'){
		reloadtab('welcome','',cmd);
	} else {
		addtab(tabkey,title,cmd);
	}
}

function resetleftviews(){
	if (!document.viewindex) return;
	if (!gid('lv'+document.viewindex)) return;
	if (!gid('defleftview')) return;
	
	gid('leftviewcloser').style.display='none';
	
	gid('lv'+document.viewindex).style.display='none';
	gid('lv'+document.viewindex).viewloaded=null;
	gid('defleftview').style.display='block';
	
	gid('tooltitle').innerHTML='';
	document.viewindex=null;
}

function resetdarkmode(darkmode){
	//reloading the desktop css
	
	if (gid('ajxcss_gyroscope')){
		ajxcss(null,'gyroscope_css.php?dark='+darkmode,'gyroscope','gyroscope');
		hdpromote('gyroscope_hd_css.php?dark='+darkmode);
	}
	
	if (gid('ajxcss_toolbar')){
		ajxcss(null,'toolbar_css.php?dark='+darkmode,'toolbar','toolbar');
		hdpromote('toolbar_hd_css.php?dark='+darkmode);
	}		

	
}

function setquicklist(quicklist,noupdate){
	//if (document.lastquicklist!=null&&document.lastquicklist==quicklist) return;
	//if (!document.lastquicklist) document.lastquicklist=quicklist;
	document.appsettings.quicklist=quicklist;
	if (!quicklist) {
		lkv_dismount();
		
		if (document.appsettings.uiconfig.toolbar_position=='top'){
			gid('tooltitle').style.left='-261px';
			gid('leftview').style.left='-261px';
			
			gid('tabtitles').style.left='20px';
			gid('tabviews').style.left='20px';
			
			gid('vsptr').style.left='0px';
			gid('vsptr').style.width='16px';
			gid('vsptr').className='rexpand';
		}
				
	} else {
		if (document.appsettings.uiconfig.toolbar_position=='top'){
			gid('tooltitle').style.left='20px';
			gid('leftview').style.left='20px';
	
			gid('tabtitles').style.left='295px';
			gid('tabviews').style.left='295px';
			gid('vsptr').style.left='280px';
			gid('vsptr').style.width='12px';
			gid('vsptr').className='';
		}
				
		if (!document.tabafloat&&!document.fsshowing) lkv_remount();
		
		/*
		//reclaiming clashing tabs
		for (var i=0;i<document.appsettings.views.length;i++){
			var tabkey='dash_'+document.appsettings.views[i].replace(/\./g,'__');
			if (gid('lv'+document.appsettings.views[i]).innerHTML!='') closetab(tabkey);
		}
		*/
	}
	
	if (!noupdate) ajxpgn('statusc',document.appsettings.codepage+'?cmd=setmyquicklist&silent=1&quicklist='+(quicklist?1:0));
	
	autosize();
	
	setTimeout(autosize,50);
	
}

function quicklist_to_dash(){
	if (document.viewindex==null||document.viewindex=='') return;
	var idx=document.viewindex;
	var dashkey='dash_'+idx.replace(/\./g,'__');
	
	var params=gid('lv'+idx).params;
	gid('lv'+idx).innerHTML='';
	resetleftviews();		
	addtab('dash_'+idx.replace(/\./g,'__'),idx,'dash_'+idx.replace(/\./g,'__')+'&'+params);  
}

function dash_to_quicklist(){
	var tabid=document.currenttab;
	if (tabid==null||tabid==-1) return;
	
	var tabkey=document.tabkeys[tabid];
	var idx=tabkey.replace('dash_','').replace('__','.');
	
	var found=false;
	for (var i=0;i<document.appsettings.views.length;i++){
		if (document.appsettings.views[i]==idx){
			found=true;
			break;
		}
	}
	
	if (!found){
		salert('The current tab cannot be pushed to the QuickList view');
		return;	
	}
	
	closetab(tabkey);
	if (!document.appsettings.quicklist) setquicklist(true);
	showview(idx);
			
}

