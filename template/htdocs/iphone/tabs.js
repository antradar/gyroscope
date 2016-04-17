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
	if (document.tabseq.length<=1) return;
	document.tabseq.pop();

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
  if (self.onrotate) onrotate();
  currenttab=tabid;
  //if (!backnav) 
  document.tabseq.push(key);
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
  var topmargin=0;
  
      document.rowcount=(t.offsetTop-topmargin)/28+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        //gid('tabtitles').style.height=30*document.rowcount+'px';
        //gid('tabviews').style.top=80+30*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
        gid('tabviews').scalech=105+28*(document.rowcount-1);
        
        scaleall(document.body);
      }
      document.lastrowcount=document.rowcount;
}

tablock=false;

function settabtitle(key,title,opts){
	var tabid=gettabid(key);
	if (tabid==-1) return;
	
	var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
    if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	if (title) document.tabtitles[tabid].innerHTML=tabhtml;		

}

function reloadtab(key,title,params,loadfunc,data,opts){

	//if tab doesn't exist, ignore it
	var tabid=gettabid(key);
	if (tabid==-1) return;
	
	if (document.tabtitles[tabid].tablock) return;
	document.tabtitles[tabid].tablock=1;
	
	var rq=xmlHTTPRequestObject();
	
	var scn=document.appsettings.codepage+'?cmd=';
	
  	if (document.wssid) params=params+'&wssid_='+document.wssid;
  	
	rq.open('POST',scn+params+'&hb='+hb(),true);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	
	var ct=document.tabviews[tabid];
	ct.slowtimer=setTimeout(function(){ct.innerHTML='<image class="hourglass" src="imgs/hourglass.gif" style="margin:5px;">';},800);
  

  

  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (ct.slowtimer) clearTimeout(ct.slowtimer);
	    
      document.tabtitles[tabid].tablock=null;
      
	var apperror=rq.getResponseHeader('apperror');
	if (apperror!=null&&apperror!=''){
		alert('Error: '+apperror);
		
		return;	
	}       

	var newkey=rq.getResponseHeader('newkey');

	if (newkey!=null&&newkey!='') {
		var newparams=rq.getResponseHeader('newparams');
		if (newparams==null||newparams==''){
			alert('Incomplete key change');
			return;	
		}
		document.tabtitles[tabid].reloadinfo={params:newparams,loadfunc:loadfunc,data:null,opts:null};

		document.tabkeys[tabid]=newkey;
		
		if (document.tabseq){
			for (i=0;i<tabcount;i++) if (document.tabkeys[i]==key) {console.warn('key collision; new key ignored');newkey=key;}
			
			for (var i=0;i<document.tabseq.length;i++){
				if (document.tabseq[i]==key) document.tabseq[i]=newkey;
			}
		}
		key=newkey;
		
	}   
	
	var newtitle=rq.getResponseHeader('newtitle');
	if (newtitle!=null&&newtitle!=''){
		title=newtitle;	
	}	       
		
	if (opts&&opts.persist) document.tabtitles[tabid].reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};

	var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
	if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  
    if (title) document.tabtitles[tabid].innerHTML=tabhtml;
	
  	  var reloader="<div class=\"reloader\"><a onclick=\"refreshtab('"+key+"');\">reload view</a></div>";
      document.tabviews[tabid].innerHTML=reloader+rq.responseText;
      if (loadfunc!=null) loadfunc(rq);
	}
  }
  rq.send(data);
}

function addtab(key,title,params,loadfunc,data,opts){
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
  
  var c=document.createElement('div');
  c.style.display='none';
  
  var t=document.createElement('span');
  var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
  if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  if (title) t.innerHTML=tabhtml;
  gid('tabtitles').appendChild(t);
  gid('tabviews').appendChild(c);
  c.slowtimer=setTimeout(function(){c.innerHTML='<image class="hourglass" src="imgs/hourglass.gif" style="margin:5px;">';},800);

  t.reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};

  document.tabviews[tabcount]=c;
  document.tabtitles[tabcount]=t;
  document.tabkeys[tabcount]=key;
  tabcount++;
  showtab(key);
  

  var rq=xmlHTTPRequestObject();
  var scn=document.appsettings.codepage+'?cmd=';
  
  if (document.wssid) params=params+'&wssid_='+document.wssid;  
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (c.slowtimer) clearTimeout(c.slowtimer);
	  var reloader="<div class=\"reloader\"><a onclick=\"refreshtab('"+key+"');\">reload view</a></div>";
      c.innerHTML=reloader+rq.responseText;

      document.tablock=null;
      if (loadfunc!=null) loadfunc();
    }
  }
  rq.send(data);
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

	if (document.tabseq){
		for (var i=0;i<document.tabseq.length;i++) if (document.tabseq[i]==key) document.tabseq[i]=null;
	}
		
	if (currenttab==tabid) {
		currenttab=0;
		var lasttab=null;
		while (lasttab==null&&document.tabseq.length>0){
			lasttab=document.tabseq.pop();	
		}  
		  
		if (lasttab!=null) {
			showtab(lasttab);
			return;	
		}
	}
  
  if (tabcount==0) {currenttab=-1; return;}
  showtab(document.tabkeys[currenttab]);	
}

function refreshtab(key){
	
  //if tab doesn't exist, ignore it
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (!confirm('Are you sure you want to refresh this tab?')) return;
 
  var tab=document.tabtitles[tabid];
  if (!tab.reloadinfo) return;
  reloadtab(key,null,tab.reloadinfo.params,tab.reloadinfo.loadfunc,tab.reloadinfo.data,tab.reloadinfo.opts);
}

function showhelp(topic,title){
	addtab('help_'+topic,'<img src="imgs/t.gif" width="12" height="12" class="img-help"> '+title,'showhelp&topic='+topic+'&title='+encodeHTML(title));	
}
