tabcount=0;
document.tabviews=[];
document.tabkeys=[];
document.tabtitles=[];

currenttab=-1;
gettabid=function(key){
  var i;
  for (i=0;i<tabcount;i++) if (document.tabkeys[i]==key) return i;
  
  return -1;	
}


showtab=function(key){
  var i;
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  currenttab=tabid;

  if (!document.tabhistory) document.tabhistory=[];
  
  var lasttab=null;
  if (document.tabhistory.length>0) lasttab=document.tabhistory[document.tabhistory.length-1];
  
  if (lasttab!=key) document.tabhistory.push(key);
  
  for (i=0;i<tabcount;i++){
	  if (i==tabid) continue;
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab';
  }	
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab';

//wrapping
  var t=document.tabtitles[tabcount-1];
  var topmargin=0; //change this if changing tab style

      document.rowcount=(t.offsetTop-topmargin)/24+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=30*document.rowcount+'px';
        gid('tabviews').style.top=80+30*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
		gid('tabviews').scalech=105+30*(document.rowcount-1);
        scaleall(document.body);
      }
      document.lastrowcount=document.rowcount;
      
  //if (gid('rightview_'+key)) gid('rightview_'+key).focus();
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
  rq.setRequestHeader('Content-Type','text/xml; charset=utf-8;');
  
	var ct=document.tabviews[tabid];
	ct.slowtimer=setTimeout(function(){ct.innerHTML='<image src="imgs/hourglass.gif" style="margin:5px;">';},800);

	if (opts!=null&&opts.newkey){
		if (document.tabhistory){
			for (i=0;i<tabcount;i++) if (document.tabkeys[i]==opts.newkey) {console.warn('key collision; new key ignored');opts.newkey=key;}
			
			for (var i=0;i<document.tabhistory.length;i++){
				if (document.tabhistory[i]==key) document.tabhistory[i]=opts.newkey;
			}
			
			key=opts.newkey;	    
		}
	}
	
	var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
	if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	if (title) document.tabtitles[tabid].innerHTML=tabhtml;
	if (opts!=null&&opts.newkey) {document.tabkeys[tabid]=opts.newkey;showtab(opts.newkey);}
    
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	    
	  if (ct.slowtimer) clearTimeout(ct.slowtimer);
	    
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||parseInt(xtatus,10)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }
	    
      document.tabviews[tabid].innerHTML=rq.responseText;
      document.tabtitles[tabid].tablock=null;
      if (loadfunc!=null) loadfunc(rq);
	}
  }
  rq.send(data);
}

function addtab(key,title,params,loadfunc,data,opts){
  //bounce keys
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
  if (document.wssid) params=params+'&wssid_='+document.wssid;
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  rq.setRequestHeader('Content-Type','text/xml; charset=utf-8;');
  
  var c=document.createElement('div');
  c.style.display='none';
  c.style.width="100%";
  c.style.height="100%";
  c.style.overflow="auto";
  
  c.slowtimer=setTimeout(function(){c.innerHTML='<image src="imgs/hourglass.gif" style="margin:5px;">';},800);

  var t=document.createElement('span');
  var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
  if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  t.innerHTML=tabhtml;
  gid('tabtitles').appendChild(t);
  gid('tabviews').appendChild(c);

  document.tabviews[tabcount]=c;
  document.tabtitles[tabcount]=t;
  document.tabkeys[tabcount]=key;
  tabcount++;
  showtab(key);
    
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (c.slowtimer) clearTimeout(c.slowtimer);
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||parseInt(xtatus,10)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }
      c.innerHTML='<input id="rightview_'+key+'" style="position:absolute;top:-60px;left:0;" title='+encodeHTML(title)+'>'+rq.responseText;

      if (loadfunc!=null) loadfunc(rq);
      document.tablock=null;
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
  
	if (document.tabhistory){
		for (var i=0;i<document.tabhistory.length;i++) if (document.tabhistory[i]==key) document.tabhistory[i]=null;
	}
		
	if (currenttab==tabid) {
		currenttab=0;
		var lasttab=null;
		while (lasttab==null&&document.tabhistory.length>0){
			lasttab=document.tabhistory.pop();	
		}  
		  
		if (lasttab!=null) {
			showtab(lasttab);
			return;	
		}
	}
	
	if (tabcount==0) {currenttab=-1; return;}
	showtab(document.tabkeys[currenttab]);	
}

function showhelp(topic,title){
	addtab('help_'+topic,'<img src="imgs/t.gif" width="12" height="12" class="img-help"> '+title,'showhelp&topic='+topic+'&title='+encodeHTML(title));	
}

Array.prototype.push = function() {
    var n = this.length >>> 0;
    for (var i = 0; i < arguments.length; i++) {
	this[n] = arguments[i];
	n = n + 1 >>> 0;
    }
    this.length = n;
    return n;
};

Array.prototype.pop = function() {
    var n = this.length >>> 0, value;
    if (n) {
	value = this[--n];
	delete this[n];
    }
    this.length = n;
    return value;
};
