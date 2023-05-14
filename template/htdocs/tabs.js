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

tab_reflow=function(){
		
	var idw=cw();
	var closeall_width=gid('closeall').offsetWidth;
	if (closeall_width>0) closeall_width+=4+10+8; //+margin_left + margin_right + tab_gap
	var rowcap=idw-212-gid('logoutlink').offsetWidth-closeall_width-20; //total width - first tab position - right panel - closeall - comfort_20
	var rowsum=0;
 	
	for (i=0;i<document.tabcount;i++){
		rowsum+=document.tabtitles[i].offsetWidth+8; //tab_gap=8
		if (rowsum>rowcap){
			rowcap=idw;
		  	rowsum=0;
		  	document.tabtitles[i].style.clear='left';  
		} else document.tabtitles[i].style.clear='none';	
	}
	
}

showtab=function(key,opts){
  var i;
  var pasttab=document.currenttab;
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (gid('gamepadspot')) gid('gamepadspot').widx=null;  
  document.currenttab=tabid;
  
  if (!document.tabhistory) document.tabhistory=[];
  
  var lasttab=null;
  if (document.tabhistory.length>0) lasttab=document.tabhistory[document.tabhistory.length-1];
  
  if (lasttab!=key) document.tabhistory.push(key);

  if (document.appsettings.uiconfig.toolbar_position=='left') tab_reflow();  
  
  for (i=0;i<document.tabcount;i++){
	  var submod='';
	  
	  if (i==tabid) continue;
	  if (document.tabkeys[i]=='welcome'&&document.appsettings.uiconfig.toolbar_position=='left') submod=' firsttab';
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab'+submod;
  }	
  var actmod='';
  if (document.tabkeys[tabid]=='welcome'&&document.appsettings.uiconfig.toolbar_position=='left') actmod=' firsttab';  
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab'+actmod;

//wrapping
  var t=document.tabtitles[document.tabcount-1];
  var topmargin=0; //change this if changing tab style
  
  var tabbase=122;
  var tabviewheight=147;
  if (document.appsettings.uiconfig.toolbar_position=='left'){
	  tabbase=57;
	  tabviewheight=82;
  }


      document.rowcount=(t.offsetTop-topmargin)/38+1;

      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        gid('tabtitles').style.height=38*document.rowcount+'px';
        gid('tabviews').style.top=tabbase+38*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
		gid('tabviews').scalech=tabviewheight+38*(document.rowcount-1);
		
        if (document.appsettings.uiconfig.toolbar_position=='left'){
         gid('mainmenu').style.top=tabbase+38*(document.rowcount-1)+'px';
         gid('bookmarkview').style.top=tabbase+38*(document.rowcount-1)+'px';
	   	 gid('mainmenu').style.height=38*document.rowcount+'px';
	   	 gid('bookmarkview').style.height=38*document.rowcount+'px';
         gid('mainmenu').scalech=tabviewheight+38*(document.rowcount-1);
         gid('bookmarkview').scalech=tabviewheight+38*(document.rowcount-1);
    	}
		
        scaleall(document.body);
      }
      document.lastrowcount=document.rowcount;
      
      if (opts&&opts.bookmark) gototabbookmark(opts.bookmark,pasttab!=document.currenttab);

      var keyparts=key.split('_');
      var ckey=keyparts[0];

      if (self['tabresizefunc_'+ckey]) {
	  	if (!document.resizefuncs) document.resizefuncs={};
	  	document.resizefuncs[ckey]=keyparts[1]||0;   
      }
      if (self['tabviewfunc_'+ckey]) {
	      if (pasttab!=document.currenttab) {
		      self['tabviewfunc_'+ckey](keyparts[1]);
	      }
      }
      if (self['tabscrollfunc_'+ckey]) document.tabviews[document.currenttab].onscroll=self['tabscrollfunc_'+ckey](keyparts[1]);
      if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();
           
  tab_setdoctitle(document.tabtitles[tabid]);
      
  if (document.tabafloat){
	if (!document.tabviews[tabid].afloat) undocktab();	  
  } else {
	if (document.tabviews[tabid].afloat) redocktab();
	
	var idw=cw();
	
	if (document.tabtitles[tabid].reloadinfo&&document.tabtitles[tabid].reloadinfo.opts&&document.tabtitles[tabid].reloadinfo.opts.wide){
		gid('tabviews').style.left=0;
		//gid('tooltitle').style.display='none';
		gid('tabviews').style.width=(idw-1)+'px';
		gid('leftview').style.visibility='hidden';
		document.widen=true;
	} else {
		if (document.appsettings.uiconfig.toolbar_position=='left'){
			gid('tabviews').style.width=(idw-261)+'px';
			gid('tabviews').style.left='260px';			
		} else {
			if (document.appsettings.quicklist){
				gid('tabviews').style.width=(idw-296)+'px';
				gid('tabviews').style.left='295px';
			} else {
				gid('tabviews').style.width=(idw-21)+'px';
				gid('tabviews').style.left='20px';
				
			}
		}
		//gid('tooltitle').style.display='block';
		gid('leftview').style.visibility='visible';
		document.widen=false;
	}
	
	if (gid('tabexpander')) gid('tabexpander').style.top=document.tabviews[tabid].offsetParent.offsetTop+'px';
	
	if (document.appsettings.uiconfig.toolbar_position=='left'){
		
		gid('bookmarkview').innerHTML='';
		
		if (key=='welcome'||(document.tabtitles[tabid].reloadinfo&&document.tabtitles[tabid].reloadinfo.opts&&document.tabtitles[tabid].reloadinfo.opts.tabctx=='dash')){
			gid('mainmenu').style.visibility='visible';
			gid('bookmarkview').style.display='none';
			
		} else {
			gid('mainmenu').style.visibility='hidden';	
			gid('bookmarkview').style.display='block';
			
			tab_loadbookmark(document.tabviews[tabid]);	
			
			if (!self['tabscrollfunc_'+ckey]) {
				document.tabviews[document.currenttab].onscroll=tab_syncbookmarks;
				setTimeout(tab_syncbookmarks,100);
			}
			
		}
		
		
		
		
	}//left mode
	
	
  }    
      
}

function tab_syncbookmarks(){
		if (document.bookmarklock){
			document.bookmarklock=null;
			return;	
		}
		
		var bookmarks=gid('bookmarkview').bookmarks;
		if (bookmarks==null) return;
		if (bookmarks.length==0) return;
		
		var tab=document.tabviews[document.currenttab];
		var top=tab.scrollTop;
		
		var cur=null;
		var h=ch();
		var offset=gid('tabviews').offsetTop;
		var last=null;
		
		for (var i=0;i<bookmarks.length;i++){
			var k=bookmarks[i].ref;
			var mark=gid(bookmarks[i].target);
			if (!mark) continue;
			if (k.offsetHeight<5) continue; //skip invisible bookmarks
			if (mark.offsetHeight<5) continue; //skip invisible content
			if (top<mark.offsetTop) {cur=k;break;} //top+h-offset-30>mark.offsetTop && 
			last=k;
			
		}

		if (!cur) cur=last;		
		
		for (var i=0;i<bookmarks.length;i++){
			if (cur==bookmarks[i].ref) cur.className='qnavitem infocus';
			else bookmarks[i].ref.className='qnavitem';	
		}		
		
		
		
}

function tab_loadbookmark(d){
	if (d==null) return;
	var qnavs=d.getElementsByClassName('qnav_'); //polyfill needed for getElementsByClassName < IE8
	if (qnavs.length>0){
		gid('bookmarkview').innerHTML=qnavs[0].innerHTML;

		var pattern=/gototabbookmark\((\S+?)\)/g
		var rawitems=gid('bookmarkview').getElementsByClassName('qnavitem');
		var items=[];
		for (var i=0;i<rawitems.length;i++){
			var item=rawitems[i];
			var target='';
			while (matches=pattern.exec(item.onclick+'')) target=matches[1].replace(/'/g,'');
			if (target!='') items.push({ref:item,target:target}); 	
		}
		
		//sort by target position
		items.sort(function(a,b){
			var oa=gid(a.target);
			var ob=gid(b.target);
			var va=oa?oa.offsetTop:0;
			var vb=ob?ob.offsetTop:0;
			if (va==vb) return 0; if (va<vb) return -1; else return 1;	
		});
				
		gid('bookmarkview').bookmarks=items;
		
		for (var i=0;i<items.length;i++){
			items[i].ref.parentNode.appendChild(items[i].ref.parentNode.removeChild(items[i].ref));	
		}
							
	} else {
		gid('bookmarkview').bookmarks=null;	
	}
	
}

function synclbookmarks(rectype,recid,marks){//legacy sync code for wide view
		if (document.bookmarklock){
			document.bookmarklock=null;
			return;	
		}
		
		var tab=document.tabviews[document.currenttab];
		var top=tab.scrollTop;
		
		var cur=null;
		var h=ch();
		var offset=gid('tabviews').offsetTop;
		
		
		for (var i=0;i<marks.length;i++){
			var k=marks[i];
			var mark=gid('bookmark_'+rectype+'_'+recid+'_'+k);
			if (!mark) continue;
			marks[k]=mark.offsetTop;
			if (top+h-offset-30>mark.offsetTop && top<mark.offsetTop) {cur=k;break;}
		}
				
		if (cur) setlbookmark(rectype+'_'+recid,rectype+'_'+recid+'_'+cur);		
			
}

function setlbookmark(ltoc,lbookmark){ //neuroscope-specific
	
	if (!gid('lbookmarks_'+ltoc)) return;
		
	var os=gid('lbookmarks_'+ltoc).getElementsByTagName('div');
	for (var i=0;i<os.length;i++){
		var o=os[i];
		if (o.className=='listitem current') o.className='listitem';
	}
	
	if (gid('lbookmark_'+lbookmark)) gid('lbookmark_'+lbookmark).className='listitem current';
	var tab=document.tabtitles[document.currenttab];
	if (!tab) return;
	tab.lbookmark=lbookmark;
	
}


tablock=false;

function tab_setdoctitle(t,title){
	if (title==null||title=='') title=t.tabname;
	if (title=='') return;
	t.tabname=title.replace(/(<([^>]+)>)/gi,'');
	var sptr=' | ';
	if (document.appsettings.shortappname==' ') sptr='';
	if (document.appsettings.shortappname) document.title=document.appsettings.shortappname+sptr+t.tabname;		
}

function settabtitle(key,title,opts){
	var tabid=gettabid(key);
	if (tabid==-1) return;
	var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
    if (opts!=null&&opts.noclose) {
	    tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	    document.tabtitles[tabid].noclose=true;
    }
	if (title) {
		document.tabtitles[tabid].innerHTML=tabhtml;
		tab_setdoctitle(document.tabtitles[tabid],title);
	}

	autosize();
}

function reloadtab(key,title,params,loadfunc,data,opts,gskey){

  var tabid=gettabid(key);
  if (tabid==-1) return;
		
  if (document.tabtitles[tabid].conflicted){
  
	  if (!sconfirm("Are you sure you want to override the edit conflict\n and save your version regardless?")){
		  return;
	  }
  }	
  
  if (document.tabtitles[tabid].tablock) return;
  document.tabtitles[tabid].tablock=1;
  
  var tabbingo=document.tabtitles[tabid].bingo; 
  
  if (document.tabtitles[tabid].autosaver) {clearTimeout(document.tabtitles[tabid].autosaver);document.tabtitles[tabid].autosavertimer=null;}
  
  var rq=xmlHTTPRequestObject();
  
  
  var scn=document.appsettings.codepage+'?cmd=';
  if (opts&&opts.fastlane) scn=document.appsettings.fastlane+'?cmd=';
  if (opts&&opts.bingo) {
	  scn=document.appsettings.binpages[opts.bingo+'']+'?cmd=';
	  document.tabtitles[tabid].bingo=opts.bingo;
  }
  if (tabbingo){
	  scn=document.appsettings.binpages[tabbingo+'']+'?cmd=';
  }
  
  if (document.tabtitles[tabid].conflicted){
	  params=params+'&__tabconflicted=1';		  
  }
  
  document.tabtitles[tabid].conflicted=null;
	    
  if (document.wssid) params=params+'&wssid_='+document.wssid;
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
  
  if (gskey!=null) {
	  //rq.setRequestHeader('X-GSREQ-KEY',gskey);
	  if (data==null) data='X-GSREQ-KEY='+gskey;
	  else data+='&X-GSREQ-KEY='+gskey;
	  
  }
  
	var ct=document.tabviews[tabid];
		
	ct.slowtimer=setTimeout(function(){
		var first=ct.firstChild;
		if (ct.gswi) return;
		var wi=document.createElement('img'); wi.src='imgs/hourglass.gif'; ct.gswi=wi;
		if (gid('statusc')!=ct) wi.style.margin='10px';
		if (first==null) ct.appendChild(wi); else ct.insertBefore(wi,first);
		ct.style.opacity=0.5; ct.style.filter='alpha(50)'; ct.style.color='#999999';
	},800);
	
	
    
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
      
	cancelgswi(ct);
	var apperror=rq.getResponseHeader('apperror');
	if (apperror!=null&&apperror!=''){
		if (opts&&opts.errfunc&&opts.errfunc!=null&&opts.errfunc!='') opts.errfunc(rq,decodeURIComponent(apperror));
		else salert('Error: '+decodeURIComponent(apperror));
		return;	
	}
	
	var newkey=rq.getResponseHeader('newkey');
	var tabctx=rq.getResponseHeader('tabctx');

	if ((newkey!=null&&newkey!='')||(tabctx=='dash')) {
		
		if (newkey!=null&&newkey!=''){		
			var newparams=rq.getResponseHeader('newparams');
			if (newparams==null||newparams==''){
				salert('Incomplete key change');
				return;	
			}
		}
		
		var newloadfunc=rq.getResponseHeader('newloadfunc');
		if (newloadfunc!=null&&newloadfunc!='') loadfunc=function(){eval(newloadfunc)};
		
		document.tabtitles[tabid].reloadinfo={params:newparams?newparams:params,loadfunc:loadfunc,data:null,opts:null};
		
		if (newkey!=null&&newkey!=''){
		
			document.tabkeys[tabid]=newkey;
			
			if (document.tabhistory){
				for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) {console.warn('key collision; new key ignored');newkey=key;}
				
				for (var i=0;i<document.tabhistory.length;i++){
					if (document.tabhistory[i]==key) document.tabhistory[i]=newkey;
				}
			}
			key=newkey;
		}
		
	}  
	
	var parenttab=rq.getResponseHeader('parenttab');
	if (parenttab!=null&&parenttab!='') {		
		document.tabviews[tabid].parenttab=parenttab;
	}
	
	var newtitle=rq.getResponseHeader('newtitle');
	if (newtitle!=null&&newtitle!=''){
		title=decodeURIComponent(newtitle);	
	}	       
	
	if (opts&&opts.persist) document.tabtitles[tabid].reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};
		
	var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
	if (opts!=null&&opts.noclose) {
		tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
		document.tabtitles[tabid].noclose=true;
	}
	
	if (title) {
		document.tabtitles[tabid].innerHTML=tabhtml;
		tab_setdoctitle(document.tabtitles[tabid],title);
	}
	
	var ta=0;
	if (document.nanoperf) ta=hb();
		
	document.tabviews[tabid].innerHTML=rq.responseText;
	
	if (document.nanoperf){
		var delta=hb()-ta;
		if (document.nanoavg==null) document.nanoavg=0;
		document.nanoavg=document.nanoavg*0.8+delta*0.2;
		if (self.warnsyslow){
			if (document.nanoavg>document.nanoperf) warnsyslow(true); else warnsyslow(false);
		}	
	}
	
	if (loadfunc!=null) loadfunc(rq);
	if (opts&&opts.bookmark) gototabbookmark(opts.bookmark);
	autosize();
	}
  }
  rq.send(data);
}

function refreshtab(key,skipconfirm){
	
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (!skipconfirm&&!sconfirm(document.dict['confirm_refresh_tab'])) return;
  document.tabtitles[tabid].conflicted=null;
  var tab=document.tabtitles[tabid];
  if (!tab.reloadinfo) return;
  tab.style.color='#000000';
  if (document.tabviews[tabid].afloat) {
	  document.tabviews[tabid].className='afloat'; 
  } else {
	  document.tabviews[tabid].className='';
  }
  
  var keyparts=key.split('_');
  var ckey=keyparts[0];
  
  reloadtab(key,null,tab.reloadinfo.params,tab.reloadinfo.loadfunc,tab.reloadinfo.data,tab.reloadinfo.opts);
}

function resizetabs(){
	if (document.tabcount==null) return;
	var count=document.tabcount;
	
	if (count>6) {
		if (document.appsettings.uiconfig.toolbar_position=='top') gid('tabtitles').className='compact';
		if (document.appsettings.uiconfig.toolbar_position=='left') gid('tabtitles').className='moveup compact';
	} else {
		if (document.appsettings.uiconfig.toolbar_position=='top') gid('tabtitles').className='';
		if (document.appsettings.uiconfig.toolbar_position=='left') gid('tabtitles').className='moveup';
	}
}

function addtab(key,title,params,loadfunc,data,opts){	
  var i;

  if (document.tablock!=null) return;
  document.tablock=true;
  
  for (i=0;i<document.tabcount;i++) {

	if (document.tabkeys[i]==key) {
        showtab(key,opts);
        document.tablock=null;
		return;
	}
  }

  
  if (document.appsettings.uiconfig.toolbar_position=='left'){
	  gid('tabviews').className='boundless bgflash';
  	  setTimeout(function(){gid('tabviews').className='boundless bgready'},250);	  
  } else {
	  gid('tabviews').className='bgflash';
  	  setTimeout(function(){gid('tabviews').className='bgready'},250);
  }

  var rq=xmlHTTPRequestObject();
  var scn=document.appsettings.codepage+'?cmd=';
  if (opts&&opts.fastlane) scn=document.appsettings.fastlane+'?cmd=';
  if (opts&&opts.bingo) scn=document.appsettings.binpages[opts.bingo+'']+'?cmd=';
  
  if (document.wssid) params=params+'&wssid_='+document.wssid;
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
    
    
  var c=document.createElement('div');
  c.style.display='none'; c.style.width="100%"; c.style.height="100%"; c.style.overflow="auto";
  
  c.slowtimer=setTimeout(function(){c.innerHTML='<image class="hourglass" src="imgs/hourglass.gif">';},800);

  var t=document.createElement('span');
  var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
  if (opts!=null&&opts.noclose) {
	  tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	  t.noclose=true;
  }
  t.tabname=title.replace(/(<([^>]+)>)/gi,'');

  if (opts!=null&&opts.closeall) tabhtml="<nobr><a title=\""+document.dict['close_all_tabs']+"\" onclick=\"resettabs('"+key+"');\" class=\"closeall\"></a><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" style=\"padding-left:1px;\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";

  
      
  t.innerHTML=tabhtml;
  if (document.appsettings.uiconfig.closeall_button=='after'&&key=='welcome') gid('tabtitles').insertBefore(t,gid('closeall')); else gid('tabtitles').appendChild(t);
  gid('tabviews').appendChild(c);

/*
  if (key=='welcome'){
	  var closeall=document.createElement('span');
	  closeall.innerHTML="<nobr><a id=\"subcloseall\" title=\""+document.dict['close_all_tabs']+"\" onclick=\"resettabs('"+key+"');\" class=\"tt\">bbbbb</a></nobr>";
	  gid('tabtitles').appendChild(closeall);
  }
*/      

  t.reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};

  document.tabviews[document.tabcount]=c;
  document.tabtitles[document.tabcount]=t;
  document.tabkeys[document.tabcount]=key;
  
  if (opts&&opts.bingo) document.tabtitles[document.tabcount].bingo=opts.bingo;
  
  document.tabcount++;
  resizetabs();  
  showtab(key,opts);
  
  if (document.tabcount>2&&gid('closeall')) {
	  //if (document.appsettings.uiconfig.toolbar_position=='top') {
		  gid('closeall').style.display='block';
	  //} else {
		//todo insert closeall as second tab, after the home tab	  
	  //}  
  }
    
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (c.slowtimer) clearTimeout(c.slowtimer);
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||(xtatus|0)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }
      
	  if (rq.status==401||(xtatus|0)==401){
		  ajxjs(self.showgssubscription,'gssubscriptions.js');
		  showgssubscription();
	      return;
	  }            

		var newtitle=rq.getResponseHeader('newtitle');
		if (newtitle!=null&&newtitle!=''){
			settabtitle(key,decodeURIComponent(newtitle));
		}
		
		var newloadfunc=rq.getResponseHeader('newloadfunc');
		if (newloadfunc!=null&&newloadfunc!='') loadfunc=function(){eval(newloadfunc)};			       
	      
	var apperror=rq.getResponseHeader('apperror');
	if (apperror!=null&&apperror!=''){
		salert('Error: '+decodeURIComponent(apperror));
		document.tablock=null;
		return;	
	}      
      
		var parenttab=rq.getResponseHeader('parenttab');
		if (parenttab!=null&&parenttab!='') {		
			t.parenttab=parenttab;
		}
		
	var ta=0;
	if (document.nanoperf) ta=hb();
      
      c.innerHTML=rq.responseText; //'<input id="rightview_'+key+'" style="position:absolute;top:-60px;left:0;" title='+encodeHTML(title)+'>'+
      
      tab_loadbookmark(c);
      
	if (document.nanoperf){
		var delta=hb()-ta;
		if (document.nanoavg==null) document.nanoavg=0;
		document.nanoavg=document.nanoavg*0.8+delta*0.2;
		if (self.warnsyslow){
			if (document.nanoavg>document.nanoperf) warnsyslow(true); else warnsyslow(false);
		}	
	}

      document.tablock=null;
      if (loadfunc!=null) loadfunc(rq);
      if (opts&&opts.bookmark) gototabbookmark(opts.bookmark,true);

    }
  }
  rq.send(data);
}


function resettabs(key){
	if (!sconfirm(document.dict['confirm_close_all_tabs'])) return;
	if (gid('closeall')) gid('closeall').style.display='none';
	
	var tabid=gettabid(key);
	for (var i=0;i<document.tabcount;i++){
		if (tabid==i) continue;
		if (document.tabtitles[i]!=null&&document.tabtitles[i].autosaver) {clearTimeout(document.tabtitles[i].autosaver);document.tabtitles[i].autosavertimer=null;}		
		if (document.tabtitles[i]!=null) gid('tabtitles').removeChild(document.tabtitles[i]);
		if (document.tabviews[i]!=null) gid('tabviews').removeChild(document.tabviews[i]);
		
		document.tabtitles[i]=null;
		document.tabviews[i]=null;
		document.tabkeys[i]=null;
	}
	
	document.tabcount=1;
	document.currenttab=tabid;
	
	document.tabhistory=[];	
	resizetabs();
	showtab(key);
	
}

closetab=function(key){
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (document.tabtitles[tabid].autosaver) {clearTimeout(document.tabtitles[tabid].autosaver);document.tabtitles[tabid].autosavertimer=null;}
      
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
  
  resizetabs(); 
  
  if (document.resizefuncs){
	  var keyparts=key.split('_');
      var ckey=keyparts[0];

	if (document.resizefuncs[ckey]!=null) {
		delete document.resizefuncs[ckey];
	}	  
  } 
  
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
    if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();
    
	
}

function closetabs(rectype){
	if (!document.tabkeys) return;
	
	var cf=function(tk){return function(){
		closetab(tk);	
	}}
	
	for (var i=0; i<document.tabkeys.length; i++){
		var tabkey=document.tabkeys[i];
		var id=tabkey.replace(rectype+'_','');
		if (parseInt(id,10)==id) setTimeout(cf(tabkey),i*50);	
	}
	
}

function closetabtree(root,sub){
	if (!document.tabkeys) return;
	
	if (!sub) document.toclose=[];

	for (var i=0; i<document.tabkeys.length; i++){
		var tab=document.tabtitles[i];
		var tabkey=document.tabkeys[i];
		if (tab&&tab.parenttab&&tab.parenttab==root) closetabtree(tabkey,1);
	}
	
	document.toclose.push(root);
	
	if (!sub) {

		var cf=function(tk){return function(){
			closetab(tk);	
		}}
					
		for (var i=0; i<document.toclose.length; i++){
			var tabkey=document.toclose[i];
			setTimeout(cf(tabkey),i*50);	
		}
	}
	
      if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();

}

function undocktab(){
	if (document.currenttab==null) return;
	if (document.currenttab>=document.tabcount) return;
	var tab=document.tabviews[document.currenttab];
	
	tab.oleft=tab.parentNode.offsetLeft;
	tab.otop=tab.parentNode.offsetTop;
	tab.owidth=tab.offsetWidth;
	tab.oheight=tab.offsetHeight;
	
	tab.style.transition='left 200ms,top 200ms,width 200ms,height 200ms';
	tab.style.position='fixed';
	
	tab.style.left=tab.oleft+'px';
	tab.style.top=tab.otop+'px';
	tab.style.width=Math.floor(tab.owidth*100/cw())+'%';
	tab.style.height=Math.floor(tab.oheight*100/ch())+'%';
	tab.style.zIndex=600;
	
	if (gid('tabexpander')) gid('tabexpander').style.display='none';
	
	setTimeout(function(){
		tab.style.left=0;
		tab.style.top=0;
		tab.style.width='100%';
		tab.style.height='100%';
		if (document.tabtitles[document.currenttab].conflicted) tab.className='afloat tabchanged';
		else tab.className='afloat';
		tab.afloat=true;
		document.tabafloat=true;
		lkv_dismount();
		rescaletabs();
		if (gid('tabexpander')) {
			gid('tabexpander').style.display='block';
			gid('tabexpander').className='afloat';
			gid('tabexpander').style.top='12px';
		}
	},10);
	
}

function redocktab(){
	if (document.currenttab==null) return;
	if (document.currenttab>=document.tabcount) return;
	var tab=document.tabviews[document.currenttab];
	
	
	tab.style.left=tab.oleft+'px';
	tab.style.top=tab.otop+'px';
	tab.style.width=Math.floor(tab.owidth*100/cw())+'%';
	tab.style.height=Math.floor(tab.oheight*100/ch())+'%';
	if (gid('tabexpander')) gid('tabexpander').style.display='none';
	
	setTimeout(function(){
		tab.style.left='auto';
		tab.style.top='auto';
		tab.style.position='static';
		tab.style.width='100%';
		tab.style.height='100%';
		tab.style.zIndex='';
		if (document.tabtitles[document.currenttab].conflicted) tab.className='tabchanged';
		else tab.className='';
		tab.afloat=null;
		document.tabafloat=null;
		if (!document.fsshowing&&document.appsettings.quicklist) lkv_remount();
		rescaletabs();
		if (gid('tabexpander')) {
			gid('tabexpander').style.display='block';
			gid('tabexpander').className='';
			gid('tabexpander').style.top=document.tabviews[document.currenttab].offsetParent.offsetTop+'px';
		}		
		
	},200);	
		
}

function toggletabdock(){
	if (document.currenttab==null) return;
	if (document.currenttab>=document.tabcount) return;
	var tab=document.tabviews[document.currenttab];
	
	if (!tab.afloat) undocktab(); else redocktab();
	
	 var os=document.body.getElementsByTagName('div');

	//also in viewport.js: scaleall
	
	var idh=ch();
	
	setTimeout(function(){ 
		var menutop=0;
		if (document.tabviews[document.currenttab].offsetParent) menutop=document.tabviews[document.currenttab].offsetParent.offsetTop;
		 	 
		for (var i=0;i<os.length;i++){
			var node=os[i];
			if (node.scalech) node.style.height=(idh-node.scalech)+'px';
			if (node.scalerch){
				node.style.height=(idh-menutop-node.scalerch)+'px';   
			}
		}
	},210);
  	 
}


function sconfirm(msg){
	var a=hb();
	var res=confirm(msg);
	var b=hb();
	if (b-a<50&&gid('diagwarn')) {gid('diagwarn').style.display='inline';flashstatus('Warning: dialogs suppressed');}
	return res;
}

function salert(msg){
	document.keyboard=[];
	var a=hb();
	alert(msg);
	var b=hb();
	if (b-a<50&&gid('diagwarn')) {gid('diagwarn').style.display='inline';
		flashstatus(msg);
		setTimeout(function(){flashstatus('Warning: dialogs suppressed');},1000);
	}
}

function sprompt(title,def){
	document.keyboard=[];
	var a=hb();
	var res=prompt(title,def);
	var b=hb();
	if (b-a<50&&gid('diagwarn')) {gid('diagwarn').style.display='inline';flashstatus('Warning: dialogs suppressed');}
	return res;
}

function scrollcoldash(container,colkey){
	//gid(container+'_view').scrollLeft=gid(container+'_'+colkey).offsetLeft;
	var d=gid(container+'_view');
	var ref=gid(container+'_'+colkey);
	var diff=ref.offsetLeft-d.scrollLeft;
	var seq=[];
	while (Math.abs(diff)>20){seq.push(ref.offsetLeft-diff); diff=Math.round(diff/4);}
	seq.push(ref.offsetLeft);
	if (d.animitv) clearInterval(d.animitv);
	d.animidx=0;
	d.animitv=setInterval(function(){
		var left=seq[d.animidx]; if (left<=0) left=0;
		d.scrollLeft=left;
		d.animidx++;
		if (d.animidx>=seq.length){
			clearInterval(d.animitv);
			d.animitv=null;
			
			setTimeout(function(){
				ref.style.opacity=0.6;
				ref.style.filter='sepia(1)';
			},100);
			
			setTimeout(function(){
				ref.style.opacity=1;
				ref.style.filter='sepia(0)';
			},500);
			
			return;	
		}
	},40);
	
}

function gototabbookmark(id,callout){
	
	if (!gid(id)||document.currenttab==null||!document.tabviews||!document.tabviews[document.currenttab]) return;
	var d=document.tabviews[document.currenttab];
	//d.scrollTop=gid(id).offsetTop-30; return; //uncomment this line to disable animation
	var diff=gid(id).offsetTop-30-d.scrollTop;
	var seq=[];
	while (Math.abs(diff)>20){seq.push(gid(id).offsetTop-diff); diff=Math.round(diff/4);}
	seq.push(gid(id).offsetTop-30);
	if (d.animitv) clearInterval(d.animitv);
	d.animidx=0;
	d.animitv=setInterval(function(){
		var top=seq[d.animidx]; if (top<=0) top=0;
		d.scrollTop=top;
		d.animidx++;
		if (d.animidx>=seq.length){
			clearInterval(d.animitv);
			d.animitv=null;
			return;	
		}
	},30);
	
	if (callout){
	    setTimeout(function(){
		    callout_section(gid(id));
	    },300);
	}
      	
}

function pullupeditor(d){
	document.tabviews[document.currenttab].scrollTop=d.parentNode.parentNode.offsetTop-5;	
}

function marktabchanged(tabkey,hide){
	var mode='block';
	if (hide) mode='none';
	if (gid('changebar_'+tabkey)) gid('changebar_'+tabkey).style.display=mode;
	else return;
	
	if (document.appsettings.autosave==null&&!gid('autosavetimeout_'+tabkey)) return;
		
	var tabid=gettabid(tabkey);
	var tab=document.tabtitles[tabid];
	
	if (!tab) return;
	
	
	if (tab.autosaver) {clearInterval(tab.autosaver);tab.autosavertimer=null;}
	
	if (tab.conflicted){
		if (gid('autosavercountdown_'+tabkey)) gid('autosavercountdown_'+tabkey).innerHTML='';
		return;
	}
	
	if (hide){//detach auto saver
		//nothing to do here
	} else {//attach/reset auto saver
		var autosavetimeout=document.appsettings.autosave-1;
		if (gid('autosavetimeout_'+tabkey)){
			if (gid('autosavetimeout_'+tabkey).value=='') return;
			autosavetimeout=parseInt(gid('autosavetimeout_'+tabkey).value,10)-1;
		}
		tab.autosaver=setInterval(function(){
			if (tab.autosavertimer==null) tab.autosavertimer=autosavetimeout;
			
			if (gid('autosavercountdown_'+tabkey)) gid('autosavercountdown_'+tabkey).innerHTML='&nbsp; '+tab.autosavertimer+' <a class="autosavekiller" onclick="cancelautosaver(\''+tabkey+'\');">&times;</a>';
			if (tab&&(!gid('changebar_'+tabkey)||tab.conflicted)){
				clearInterval(tab.autosaver);tab.autosavertimer=null;
				if (gid('autosavercountdown_'+tabkey)) gid('autosavercountdown_'+tabkey).innerHTML='';
				return;
			}
			//console.log(tab.autosavertimer);
			tab.autosavertimer--;
			if (tab.autosavertimer<0){
				clearInterval(tab.autosaver);
				tab.autosavertimer=null;
				var event=document.createEvent('Events');
				event.initEvent('click',true,false);
				if (gid('changebar_button_'+tabkey)) gid('changebar_button_'+tabkey).dispatchEvent(event);
			}
		},1000);		
	}
	
}

function cancelautosaver(tabkey){
	var tabid=gettabid(tabkey);
	var tab=document.tabtitles[tabid];
	if (!tab) return;
	if (tab.autosaver){clearTimeout(tab.autosaver);tab.autosavertimer=null;}
	if (gid('autosavercountdown_'+tabkey)) gid('autosavercountdown_'+tabkey).innerHTML='';
	
}

function marktabsaved(tabkey,title){
	var tab=gid('savebar_'+tabkey);
	if (!tab) return;
	if (!tab.orgsavetitle) tab.orgsavetitle=tab.getElementsByClassName('savebar_content')[0].innerHTML;
	if (!title) title=tab.orgsavetitle;
	tab.getElementsByClassName('savebar_content')[0].innerHTML=title;
	if (tab.timer) clearTimeout(tab.timer);
	tab.style.display='block';
	tab.timer=setTimeout(function(){
		tab.style.display='none';
	},1200);	
}




Array.prototype.push = function() {
    var n = this.length >>> 0;
    for (var i = 0; i < arguments.length; i++) {this[n] = arguments[i]; n = n + 1 >>> 0;}
    this.length = n;
    return n;
};

Array.prototype.pop = function() {
    var n = this.length >>> 0, value;
    if (n) {value = this[--n]; delete this[n];}
    this.length = n;
    return value;
};
