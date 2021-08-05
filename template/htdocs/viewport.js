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

function scaleall(root){
    
  var i,j;
  var idh=ch();
  var idw=cw();
  
  var os=root.getElementsByTagName('div');
  
  gid('lefticons').style.width=idw+'px';
  gid('leftview').style.height=(idh-147)+'px';
  gid('leftview_').style.height=(idh-147)+'px';
  
  gid('lkv').style.height=(idh-187)+'px';
  gid('lkvc').style.height=(idh-220)+'px';
  
  gid('tabtitles').style.width=(idw-296)+'px';
  gid('tabviews').style.width=(idw-296)+'px';
  gid('tabviews').style.height=(idh-147)+'px';
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

  for (var i=0;i<os.length;i++){
    var node=os[i];
    if (node.scalech) node.style.height=(idh-node.scalech)+'px';
  }
  
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
	gid('fsclose').closeaction=func;
	if (initfunc) initfunc();
}

function closefs(){
	document.fsshowing=null;
	gid('fsview').style.display='none';
	gid('fstitlebar').style.display='none';
	gid('fsmask').style.display='none';

	if (gid('fsclose').closeaction) gid('fsclose').closeaction();	
}

function loadfs(title,cmd,func,initfunc){
	ajxpgn('fsview',document.appsettings.codepage+'?cmd='+cmd,1,0,'',function(){
		gid('fstitle').innerHTML=title;	
		showfs(func,initfunc);	
	});
}


function autosize(){

  scaleall(document.body);
  var caleview=gid('caleview');
  if (caleview){

  }
  if (document.tabcount>0){
  var t=document.tabtitles[document.tabcount-1];
  var topmargin=0; //change this if changing tab style
//wrapping
      document.rowcount=(t.offsetTop-topmargin)/38+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=38*document.rowcount+'px';
        gid('tabviews').style.top=122+38*(document.rowcount-1)+'px';
        gid('tabviews').scalech=147+38*(document.rowcount-1);
      }
      scaleall(document.body);
      document.lastrowcount=document.rowcount;
  }

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


function reloadview(idx,listid){
	hidelookup();
	if (document.viewindex!=idx) return;

	var params='';
	if (gid('lv'+document.viewindex)) params=gid('lv'+document.viewindex).params;
		
	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx,0,0,params);
}

function showview(idx,lazy,force,params,func){
  if (!params) params='';
  
  if (gid('gamepadspot')) gid('gamepadspot').vidx=null;
 	
  hidelookup();
    
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }
  
  if (gid('lv'+idx)) gid('lv'+idx).params=params;
 
 
  for (var k=0; k<document.appsettings.views.length;k++){
	var i=document.appsettings.views[k];
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
		if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded){
			if (document.lvxhr&&document.lvxhr.reqobj) {
				document.lvxhr.abortflag=1;document.lvxhr.reqobj.abort();document.lvxhr.reqobj=null;cancelgswi(document.lvxhr);
			}
			document.lvxhr=gid('lv'+i);
			ajxpgn('lv'+i,document.appsettings.codepage+'?cmd=slv_'+i.replace(/\./g,'__')+'&'+params,true,true,'',function(rq){
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
  
  gid('leftview').style.background=document.flashcolor;
  setTimeout(function(){gid('leftview').style.background='#ffffff';},200);  
  
  if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();	
  
}

function showlookup(){
	var lkv=gid('lkv');
	if (lkv.showing) return;
	
	if (gid('gamepadspot')) gid('gamepadspot').lookupview=true;
	
	lkv.showing=true;
	lkv.style.left='0px';

	gid('lkvc').style.background='#ffffc0';
	setTimeout(function(){gid('lkvc').style.background='#ffffff';},200);				
}

function hidelookup(){
	var lkv=gid('lkv');
	if (!lkv.showing) return;
	if (gid('gamepadspot')) gid('gamepadspot').lookupview=null;
	
	lkv.showing=null;
	lkv.style.left='-280px';	
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
  rq.open('GET',document.appsettings.codepage+'?cmd=pump&hb='+stamp,true);
  rq.onreadystatechange=f;
  rq.send(null);
}



function sv(d,v){gid(d).value=v;}

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