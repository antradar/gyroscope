tabcount=0;
document.tabviews=[];
document.tabkeys=[];
document.tabtitles=[];
document.tabseq=[];

currenttab=-1;
gettabid=function(key){
  var i;
  for (i=0;i<tabcount;i++) if (document.tabkeys[i]==key) return i;
  
  return -1;	
}

navback=function(){
	var obj=document.tabseq.pop();
	if (obj==null||obj==0) {
		document.viewmode=1;
		showdeck();
		return;
	}
	showtab(obj,true);
	
}

showtab=function(key,backnav){
  var i;
  //showpanel(2);
  rotate();
  var tabid=gettabid(key);
  if (tabid==-1) return;
  currenttab=tabid;
  if (!backnav) document.tabseq.push(key);
  document.viewmode=2;
  
  for (i=0;i<tabcount;i++){
	  if (i==tabid) continue;
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab';
  }	
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab';

//wrapping
  var t=document.tabtitles[tabcount-1];

      document.rowcount=(t.offsetTop-6)/24+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        //gid('tabtitles').style.height=30*document.rowcount+'px';
        //gid('tabviews').style.top=80+30*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
        //scaleall(document.body);
      }
      document.lastrowcount=document.rowcount;
}

tablock=false;

function reloadtab(key,title,params,loadfunc){

  //if tab doesn't exist, ignore it
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  var rq=xmlHTTPRequestObject();

  var scn=document.appsettings.codepage+'?cmd=';
  rq.open('GET',scn+params+'&hb='+hb(),true);
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
      document.tabtitles[tabid].innerHTML="<nobr><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"><img src=\"iphone/n.gif\"></span></a><a onclick=\"showtab('"+key+"');\">"+title+"</a></nobr>";
      document.tabviews[tabid].innerHTML=rq.responseText;
      if (loadfunc!=null) loadfunc();
	}
  }
  rq.send(null);
}
function addtab(key,title,params,loadfunc){
  //bounce keys
  //showpanel(2);
  document.viewmode=2;
  rotate();
  var i;
  if (document.tablock!=null) return;
  document.tablock=true;
  
  for (i=0;i<tabcount;i++) {
	if (document.tabkeys[i]==key) {
        showtab(key);
        document.tablock=null;
		return;
	}
  }

  var rq=xmlHTTPRequestObject();
  var scn=document.appsettings.codepage+'?cmd=';
  rq.open('GET',scn+params+'&hb='+hb(),true);
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
      var c=document.createElement('div');
      c.style.display='none';
      c.innerHTML=rq.responseText;
      var t=document.createElement('div');
      t.innerHTML="<nobr><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"><img src=\"iphone/n.gif\"></span></a><a onclick=\"showtab('"+key+"');\">"+title+"</a></nobr> ";
      gid('tabtitles').appendChild(t);
      gid('tabviews').appendChild(c);

      document.tabviews[tabcount]=c;
      document.tabtitles[tabcount]=t;
      document.tabkeys[tabcount]=key;
      tabcount++;
      showtab(key);

      if (loadfunc!=null) loadfunc();
      document.tablock=null;
    }
  }
  rq.send(null);
}

closetab=function(key){
  var tabid=gettabid(key);
  if (tabid==-1) return;
    
  gid('tabtitles').removeChild(document.tabtitles[tabid]);
  gid('tabviews').removeChild(document.tabviews[tabid]);

  var i;
  for (i=tabid;i<tabcount-1;i++){
	  document.tabtitles[i]=document.tabtitles[i+1];
	  document.tabviews[i]=document.tabviews[i+1];
	  document.tabkeys[i]=document.tabkeys[i+1];	  
  }
  tabcount--;
  if (currenttab==tabid) currenttab=0;
  if (tabcount==0) {currenttab=-1; return;}
  showtab(document.tabkeys[currenttab]);	
}
