function ch(){

	//if (document.viewheight) return document.viewheight+105;
	
  var w=cw();
  if (w*0.85<=485) return 270/0.85;
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

  if (document.rowcount){
		gid('tabtitleshadow').style.height=(25*document.rowcount-1)	  
  }

  gid('lkv').style.height=(idh-145)+'px';
  gid('lkvc').style.height=(idh-150)+'px';
  	   
}

function showlookup(){
	var lkv=gid('lkv');
	if (lkv.showing) return;
	
	lkv.showing=true;
	lkv.style.left='0px';		
}

function hidelookup(){
	var lkv=gid('lkv');
	if (!lkv.showing) return;
	
	lkv.showing=null;
	lkv.style.left='-220px';	
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
      document.rowcount=(t.offsetTop-6)/24+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=30*document.rowcount+'px';
        gid('tabviews').style.top=80+30*(document.rowcount-1)+'px';
        gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
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
}

viewcount=document.appsettings.viewcount;

function showpanel(idx){
	for (var i=0;i<5;i++) gid('panel'+i).style.display='none';
	gid('panel'+idx).style.display='block';	
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
  var i;
  
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }

  for (i=0;i<viewcount;i++){
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
      if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded)
	      ajxpgn('lv'+i,document.appsettings.codepage+'?cmd=slv'+i+'&hb='+hb(),true,true);
      else {
	      gid('lv'+idx).style.display='block';
	      gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;
      }
    }
  }
  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
}

function stackview(){ //used by auto-completes
	gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
	gid('lv'+document.viewindex).style.display='none';
	gid('lv'+(document.appsettings.viewcount-1)).style.display='block';
	document.viewindex=document.appsettings.viewcount-1;

}

function authpump(){
  var stamp=hb();
  var rq=xmlHTTPRequestObject();
  var f=function(){
    if (rq.readyState==4){
	    if (rq.status==200||rq.status==304){
		     if (stamp!=rq.responseText){
		       window.location.reload();
		     }
 		}
    }
  }
  rq.open('GET',document.appsettings.codepage+'?cmd=pump&hb='+stamp,true);
  rq.onreadystatechange=f;
  rq.send(null);
}

function sv(d,v){gid(d).value=v;}
