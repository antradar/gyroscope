function ch(){

	//if (document.viewheight) return document.viewheight+105;
	
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
	
	gid('fsmask').style.display='block';
	gid('fstitlebar').style.display='block';
	gid('fsview').style.display='block';
	gid('fsclose').closeaction=func;
	if (initfunc) initfunc();
}

function closefs(){
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
  gid('statusinfo').innerHTML=t;
  hinttimer=setTimeout(function(){gid('statusinfo').innerHTML='';},l);
  if (window.Notification){
	var n=new Notification('Gyroscope',{body:t});  
  } 

}


function reloadview(idx,listid){
	hidelookup();
	if (document.viewindex!=idx) return;
	
	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx);
}

function showview(idx,lazy,force){
	if (!force&&document.viewmode!=1&&document.iphone_portrait) return;	
	document.viewmode=1;
	rotate();
	hidelookup();
  
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }

  for (var k=0;k<document.appsettings.views.length;k++){
	var i=document.appsettings.views[k];
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
      if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded)
	      ajxpgn('lv'+i,document.appsettings.codepage+'?cmd=slv_'+i.replace(/\./g,'__')+'&hb='+hb(),true,true);
      else {
	      gid('lv'+idx).style.display='block';
	      gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;
      }
    }
  }
  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
  if (force&&self.onrotate) onrotate();
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

