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
  
  /*
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
  */
  gid('lefticons').style.width=idw+'px';
  gid('leftview').style.height=(idh-105)+'px';
  gid('lkv').style.height=(idh-145)+'px';
  gid('lkvc').style.height=(idh-176)+'px';
  
  gid('tabtitles').style.width=(idw-225)+'px';
  gid('tabviews').style.width=(idw-225)+'px';
  gid('tabviews').style.height=(idh-105)+'px';
  gid('sptr').style.height=(idh-104)+'px';
  gid('statusinfo').style.top=(idh-25)+'px';
  gid('statusinfo').style.width=idw+'px';

  for (i=0;i<os.length;i++){
    var node=os[i];
    if (node.scalech) node.style.height=(idh-node.scalech)+'px';
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
  var topmargin=0; //change this if changing tab style
//wrapping
      document.rowcount=(t.offsetTop-topmargin)/24+1;
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

function reloadview(idx,listid){
	hidelookup();
	if (document.viewindex!=idx) return;
	
	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx);
}

function showview(idx,lazy){
  var i;

  hidelookup();
    
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }
  
  var callback=function(id){return function(){
	//gid('lvtab_'+id).focus();  
  }}

  for (i=0;i<viewcount;i++){
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
      if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded)
      
	      ajxpgn('lv'+i,document.appsettings.codepage+'?cmd=slv'+i+'&hb='+hb(),true,true,'<input style="position:absolute;top:-60px;left:0;" id="lvtab_'+i+'">',callback(i));
      else {
	      gid('lv'+idx).style.display='block';
	      gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;
	      
      }
    }
  }
  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
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
	    var xtatus=rq.getResponseHeader('X-STATUS');	    
	    if (rq.status==200||rq.status==304||rq.status==403||parseInt(xtatus,10)==403){
		     if (stamp!=rq.responseText){
			   if (self.skipconfirm) skipconfirm();  
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
