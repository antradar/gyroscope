function ch(){	
  var w=cw();
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
  
  var os=root.getElementsByTagName('div'); //AKB#2
  
	gid('tabviews').style.height=(idh-105)+'px';
	gid('lvviews').style.height=(idh-105)+'px';

  if (document.rowcount){
		gid('tabtitleshadow').style.height=(28*document.rowcount-1)	  
  }

  gid('lkv').style.height=(idh-145)+'px';
  gid('lkvc').style.height=(idh-150)+'px';
  
  gid('fsmask').style.width=idw+'px';
  gid('fsmask').style.height=idh+'px';

  gid('fsview').style.width=idw-20+'px';
  gid('fsview').style.height=idh-50+'px';
  
  gid('fstitlebar').style.width=idw-20+'px';  
  	   
}

function showlookup(){
	var lkv=gid('lkv');
	if (lkv.showing) return;
	
	lkv.showing=true;
	lkv.style.left='10px';		
}

function hidelookup(){
	var lkv=gid('lkv');
	if (!lkv.showing) return;
	
	lkv.showing=null;
	lkv.style.left='-230px';	
}

function setnosleep(mode){
	var ua = {android: /Android/ig.test(navigator.userAgent),ios: /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent)};
	if (ua.android&&gid('nosleepvideo')){
		if (mode) gid('nosleepvideo').play(); else gid('nosleepvideo').pause();
	}

	if (ua.ios){
		if (mode){
			document.nosleeptimer=setInterval(function(){window.location.href='/';window.setTimeout(window.stop,0);},10000);
		} else {
			if (document.nosleeptimer) clearInterval(document.nosleeptimer);

		}
	}
}


function showfs(func,initfunc){
	document.fsshowing=true;
	gid('fsmask').style.display='block';
	gid('fstitlebar').style.display='block';
	gid('fsview').style.display='block';
	gid('fsclose').closeaction=func;
	if (initfunc) initfunc();
}

function closefs(){
	document.fsshowing=null;
	setnosleep(false);
	gid('fsview').style.display='none';
	gid('fstitlebar').style.display='none';
	gid('fsmask').style.display='none';

	if (gid('fsclose').closeaction) gid('fsclose').closeaction();	
}

function loadfs(title,cmd,func,initfunc){
	setnosleep(true);
	ajxpgn('fsview',document.appsettings.codepage+'?cmd='+cmd,1,0,'',function(){
		gid('fstitle').innerHTML=title;	
		showfs(func,initfunc);	
	});
}

hinttimer=-2;

function autosize(){

  scaleall(document.body);
  var caleview=gid('caleview');
  if (caleview){

  }
  if (tabcount>0){
  var t=document.tabtitles[tabcount-1];
//wrapping
      document.rowcount=(t.offsetTop-6)/28+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=28*document.rowcount+'px';
        gid('tabviews').style.top=80+28*(document.rowcount-1)+'px';
        gid('tabviews').setAttribute("scale:ch",105+28*(document.rowcount-1));
      }
      scaleall(document.body);
      document.lastrowcount=document.rowcount;
  }

}

function hintstatus(t,d){
  if (hinttimer!=-2) clearTimeout(hinttimer);
  gid('statusinfo').innerHTML='<a>'+t+'</a>';
  d.onmouseout=function(){
    gid('statusinfo').innerHTML='';
  }
}

function flashstatus(t,l){
	if (l){
		gid('statusc').innerHTML=t;
		hinttimer=setTimeout(function(){gid('statusc').innerHTML='';},l);
	}
	
	if (window.Notification){
		if (document.lastnotification==t) return;
		document.lastnotification=t;
		var n=new Notification('Gyroscope',{body:t}); 
		setTimeout(function(){document.lastnotification=null;},10000);
	}

}


function reloadview(idx,listid){
	hidelookup();
	if (document.viewindex!=idx) return;

	if (self.onrotate) onrotate();
	
	var params='';
	if (gid('lv'+document.viewindex)) params=gid('lv'+document.viewindex).params;
		
	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx,0,0,params);
}

function showview(idx,lazy,force,params,func){
	if (!params) params='';
	
	if (!force&&document.viewmode!=1&&document.iphone_portrait) return;	
	document.viewmode=1;
	rotate();
	hidelookup();
  
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }

  if (gid('lv'+idx)) gid('lv'+idx).params=params;
  
  for (var k=0;k<document.appsettings.views.length;k++){
	var i=document.appsettings.views[k];
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
		if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded){
			if (document.lvxhr&&document.lvxhr.reqobj) {document.lvxhr.abortflag=1;document.lvxhr.reqobj.abort();document.lvxhr.reqobj=null;cancelgswi(document.lvxhr);}
			document.lvxhr=gid('lv'+i);			
			ajxpgn('lv'+i,document.appsettings.codepage+'?cmd=slv_'+i.replace(/\./g,'__')+'&'+params,true,true,'',function(rq){
				var title=rq.getResponseHeader('listviewtitle');
				if (title!=null&&title!='') gid('tooltitle').innerHTML='<a>'+decodeURIComponent(title)+'</a>';
				var flag=rq.getResponseHeader('listviewflag');
				var js=rq.getResponseHeader('listviewjs');
				if (flag!=null&&js!=null&&js!=''){
					ajxjs(self[flag],js);
					//sajxjs(flag,js);	
				}				
				if (func!=null) func();
			});
		} else {
			gid('lv'+idx).style.display='block';
			if (gid('lv'+idx).tooltitle!=null&&gid('lv'+idx).tooltitle!='') gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;
		}
    }
  }
  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
  if (force&&self.onrotate) onrotate();
  if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();
  
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
	    if (rq.status==200||rq.status==304){
		     if (stamp!=rq.responseText){
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

function toggle_easyread(){
	if (!document.easyreading){
		ajxcss(null,'easyon.css','easyon','easyoff');
		document.easyreading=true;	
	} else {
		ajxcss(null,'easyoff.css','easyoff','easyon');
		document.easyreading=null;	
	}
}

function toggle_easyread_start(){
	if (document.easyreadswitcher) clearTimeout(document.easyreadswitcher);
	document.easyreadswitcher=setTimeout(function(){
		toggle_easyread();
	},1200);	
}

function toggle_easyread_end(){
	if (document.easyreadswitcher) clearTimeout(document.easyreadswitcher);	
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
