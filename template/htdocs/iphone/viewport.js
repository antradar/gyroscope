function ch(){
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
  for (i=0;i<os.length;i++){
    var node=os[i];
    if(node.attributes){
      if(node.attributes['scale:nx']) //AKB#3
        node.style.left=idw-node.attributes['scale:nx'].value+'px';
      if(node.attributes['scale:ny'])
        node.style.top=idh-node.attributes['scale:ny'].value+'px';
      if(node.attributes['scale:cw'])
        node.style.width=(idw-node.attributes['scale:cw'].value)+'px';
      if(node.attributes['scale:ch'])
        node.style.height=(idh-node.attributes['scale:ch'].value)+'px';                        
    }
  }
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

function showview(idx,lazy){
	//showpanel(4);
	
	ajxpgn('views',document.appsettings.codepage+'?cmd=slv'+idx+'&hb='+hb(),true,true);	
	return;
	
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
     if (stamp!=rq.responseText){
       window.location.reload();
     }
    }
  }
  rq.open('GET',document.appsettings.codepage+'?cmd=pump&hb='+stamp,true);
  rq.onreadystatechange=f;
  rq.send(null);
}

function sv(d,v){gid(d).value=v;}

function encodeHTML(code){
	code=escape(code);
	code=code.replace(/\//g,"%2F");
	code=code.replace(/\?/g,"%3F");
	code=code.replace(/=/g,"%3D");
	code=code.replace(/&/g,"%26");
	code=code.replace(/@/g,"%40");
	code=code.replace(/\+/g,"%2B");
	return code;
}
