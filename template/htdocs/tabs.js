document.tabcount=0;
document.tabviews=[];
document.tabkeys=[];
document.tabtitles=[];

document.currenttab=-1;
document.flashcolor='#ffffc0';

gettabid=function(key){
  var i;
  for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) return i;
  
  return -1;	
}


showtab=function(key){
  var i;
  var tabid=gettabid(key);
  if (tabid==-1) return;

  document.currenttab=tabid;

  if (!document.tabhistory) document.tabhistory=[];
  
  var lasttab=null;
  if (document.tabhistory.length>0) lasttab=document.tabhistory[document.tabhistory.length-1];
  
  if (lasttab!=key) document.tabhistory.push(key);
  
  for (i=0;i<document.tabcount;i++){
	  if (i==tabid) continue;
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab';
  }	
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab';
//wrapping
  var t=document.tabtitles[document.tabcount-1];
  var topmargin=0; //change this if changing tab style

      document.rowcount=(t.offsetTop-topmargin)/38+1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=38*document.rowcount+'px';
        gid('tabviews').style.top=122+38*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
		gid('tabviews').scalech=147+38*(document.rowcount-1);
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
  if (opts&&opts.fastlane) scn=document.appsettings.fastlane+'?cmd=';
  
  if (document.wssid) params=params+'&wssid_='+document.wssid;
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
  
	var ct=document.tabviews[tabid];
	ct.slowtimer=setTimeout(function(){ct.innerHTML='<image class="hourglass" src="imgs/hourglass.gif" style="margin:5px;">';},800);

	
    
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	    
	  if (ct.slowtimer) clearTimeout(ct.slowtimer);
	    
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||(xtatus|0)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }
	   
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
		
		if (document.tabhistory){
			for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) {console.warn('key collision; new key ignored');newkey=key;}
			
			for (var i=0;i<document.tabhistory.length;i++){
				if (document.tabhistory[i]==key) document.tabhistory[i]=newkey;
			}
		}
		key=newkey;
		
	}  
	
	var newtitle=rq.getResponseHeader('newtitle');
	if (newtitle!=null&&newtitle!=''){
		title=newtitle;	
	}	       
	
	if (opts&&opts.persist) document.tabtitles[tabid].reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};
	
	var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
	if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	if (title) document.tabtitles[tabid].innerHTML=tabhtml;
	
      document.tabviews[tabid].innerHTML=rq.responseText;
      if (loadfunc!=null) loadfunc(rq);
	}
  }
  rq.send(data);
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

function addtab(key,title,params,loadfunc,data,opts){
  //bounce keys
  var i;
  
  if (document.tablock!=null) return;
  document.tablock=true;
  
  for (i=0;i<document.tabcount;i++) {

	if (document.tabkeys[i]==key) {
        showtab(key);
        document.tablock=null;
		return;
	}
  }



  gid('tabviews').style.background=document.flashcolor;
  setTimeout(function(){gid('tabviews').style.background='#ffffff';},500);      

  var rq=xmlHTTPRequestObject();
  var scn=document.appsettings.codepage+'?cmd=';
  if (opts&&opts.fastlane) scn=document.appsettings.fastlane+'?cmd=';
  if (document.wssid) params=params+'&wssid_='+document.wssid;
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
  
  var c=document.createElement('div');
  c.style.display='none';
  c.style.width="100%";
  c.style.height="100%";
  c.style.overflow="auto";
  
  c.slowtimer=setTimeout(function(){c.innerHTML='<image class="hourglass" src="imgs/hourglass.gif" style="margin:5px;">';},800);

  var t=document.createElement('span');
  var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
  if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  if (opts!=null&&opts.closeall) tabhtml="<nobr><a title=\"close all tabs\" onclick=\"resettabs('"+key+"');\" class=\"closeall\"></a><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" style=\"padding-left:1px;\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  
  t.innerHTML=tabhtml;
  gid('tabtitles').appendChild(t);
  gid('tabviews').appendChild(c);

  t.reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};

  document.tabviews[document.tabcount]=c;
  document.tabtitles[document.tabcount]=t;
  document.tabkeys[document.tabcount]=key;
  document.tabcount++;
  showtab(key);
  
  if (document.tabcount>2&&gid('closeall')) gid('closeall').style.display='block';  
    
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (c.slowtimer) clearTimeout(c.slowtimer);
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||(xtatus|0)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }
      c.innerHTML='<input id="rightview_'+key+'" style="position:absolute;top:-60px;left:0;" title='+encodeHTML(title)+'>'+rq.responseText;

      document.tablock=null;
      if (loadfunc!=null) loadfunc(rq);
      
    }
  }
  rq.send(data);
}

function resettabs(key){
	if (!confirm('Are you sure you want to close all the tabs?')) return;
	if (gid('closeall')) gid('closeall').style.display='none';
	
	var tabid=gettabid(key);
	for (var i=0;i<document.tabcount;i++){
		if (tabid==i) continue;
				
		if (document.tabtitles[i]!=null) gid('tabtitles').removeChild(document.tabtitles[i]);
		if (document.tabviews[i]!=null) gid('tabviews').removeChild(document.tabviews[i]);
		
		document.tabtitles[i]=null;
		document.tabviews[i]=null;
		document.tabkeys[i]=null;
	}
	
	document.tabcount=1;
	document.currenttab=tabid;
	
	document.tabhistory=[];	
	
	showtab(key);
	
}

closetab=function(key){
  var tabid=gettabid(key);
  if (tabid==-1) return;
    
  gid('tabtitles').removeChild(document.tabtitles[tabid]);
  gid('tabviews').removeChild(document.tabviews[tabid]);
  document.tabtitles[tabid]=null;
  document.tabviews[tabid]=null;  

  var i;
  for (i=tabid;i<document.tabcount-1;i++){
	  document.tabtitles[i]=document.tabtitles[i+1];
	  document.tabviews[i]=document.tabviews[i+1];
	  document.tabkeys[i]=document.tabkeys[i+1];	  
  }
  document.tabcount--;
  
  if (document.tabcount<=2&&gid('closeall')) gid('closeall').style.display='none';
  
	if (document.tabhistory){
		for (var i=0;i<document.tabhistory.length;i++) if (document.tabhistory[i]==key) document.tabhistory[i]=null;
	}
		
	if (document.currenttab==tabid) {
		document.currenttab=0;
		var lasttab=null;
		while (lasttab==null&&document.tabhistory.length>0){
			lasttab=document.tabhistory.pop();	
		}  
		  
		if (lasttab!=null) {
			showtab(lasttab);
			return;	
		}
	}
	
	if (document.tabcount==0) {document.currenttab=-1; return;}
	showtab(document.tabkeys[document.currenttab]);	
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
