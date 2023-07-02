document.tabcount=0;
document.tabviews=[];
document.tabkeys=[];
document.tabtitles=[];
document.tabseq=[];

document.currenttab=-1;
gettabid=function(key){
	var i;
	for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) return i;
	
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
	showtab(obj);
	
}

function undocktab(){
	//for now
}

function redocktab(){
	//for now	
}

function toggletabdock(){
	//for now
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

showtab=function(key,opts){
  var i;
  var pasttab=document.currenttab;
  rotate();
  var tabid=gettabid(key);
  if (tabid==-1) return;
  if (self.onrotate) onrotate();
  document.currenttab=tabid;

  document.tabseq.push(key);
  document.viewmode=2;
  
  for (i=0;i<document.tabcount;i++){
	  if (i==tabid) continue;
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab';
  }	
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab';

//wrapping
  var t=document.tabtitles[document.tabcount-1];
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
      if (opts&&opts.bookmark) gototabbookmark(opts.bookmark,pasttab!=document.currenttab);
      var keyparts=key.split('_');
      var ckey=keyparts[0];
      if (self['tabresizefunc_'+ckey]) {
	  	if (!document.resizefuncs) document.resizefuncs={};
	  	document.resizefuncs[ckey]=keyparts[1]||0;   
      }      
      if (self['tabviewfunc_'+ckey]) {
	      if (pasttab!=document.currenttab) self['tabviewfunc_'+ckey](keyparts[1]);
      }
      if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();
      	            
}

tablock=false;

function settabtitle(key,title,opts){
	var tabid=gettabid(key);
	if (tabid==-1) return;
	
	var tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
    if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" ondblclick=\"refreshtab('"+key+"');\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
	if (title) document.tabtitles[tabid].innerHTML=tabhtml;		

}

function reloadtab(key,title,params,loadfunc,data,opts,gskey){

	//if tab doesn't exist, ignore it
	var tabid=gettabid(key);
	if (tabid==-1) return;
	
	if (document.tabtitles[tabid].conflicted){	
	  if (!sconfirm("Are you sure you want to override the edit conflict\n and save your version regardless?")){
		  return;
	  }
	}
		
	
	if (document.tabtitles[tabid].tablock) return;
	document.tabtitles[tabid].tablock=1;
	
  if (document.tabtitles[tabid].conflicted){
	  params=params+'&__tabconflicted=1';		  
  }
	
	
	document.tabtitles[tabid].conflicted=null;
	
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

	  if (rq.status==401||(xtatus|0)==401){
		  ajxjs(self.showgssubscription,'gssubscriptions.js');
		  showgssubscription();
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

	if (newkey!=null&&newkey!='') {
		var newparams=rq.getResponseHeader('newparams');
		if (newparams==null||newparams==''){
			salert('Incomplete key change');
			return;	
		}
		
		var newloadfunc=rq.getResponseHeader('newloadfunc');
		if (newloadfunc!=null&&newloadfunc!='') loadfunc=function(){eval(newloadfunc)};
		
		document.tabtitles[tabid].reloadinfo={params:newparams,loadfunc:loadfunc,data:null,opts:null};

		document.tabkeys[tabid]=newkey;
		
		if (document.tabseq){
			for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) {console.warn('key collision; new key ignored');newkey=key;}
			
			for (var i=0;i<document.tabseq.length;i++){
				if (document.tabseq[i]==key) document.tabseq[i]=newkey;
			}
		}
		key=newkey;
		
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

	var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
	if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  
    if (title) document.tabtitles[tabid].innerHTML=tabhtml;
	
  	  var reloader="<div class=\"reloader\" id=\"tabreloader_"+key+"\"><a onclick=\"refreshtab('"+key+"');\">"+document.dict['tab_reload']+"</a></div>";
	document.tabviews[tabid].innerHTML=reloader+rq.responseText;
	if (loadfunc!=null) loadfunc(rq);
	if (opts&&opts.bookmark) gototabbookmark(opts.bookmark);
	scaleall(document.body);
	}
  }
  rq.send(data);
}

function addtab(key,title,params,loadfunc,data,opts){
  document.viewmode=2;
  rotate();
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
  
  var c=document.createElement('div');
  c.style.display='none';
  
  var t=document.createElement('span');
  var tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><a onclick=\"closetab('"+key+"')\"><span class=\"tabclose\"></span></a></nobr>";
  if (opts!=null&&opts.noclose) tabhtml="<nobr><a class=\"tt\" onclick=\"showtab('"+key+"');\">"+title+"</a><span class=\"noclose\"></span></nobr>";
  if (title) t.innerHTML=tabhtml;
  gid('tabtitles').appendChild(t);
  gid('tabviews').appendChild(c);
  c.slowtimer=setTimeout(function(){c.innerHTML='<image class="hourglass" src="imgs/hourglass.gif">';},800);

  t.reloadinfo={params:params,loadfunc:loadfunc,data:data,opts:opts};

  document.tabviews[document.tabcount]=c;
  document.tabtitles[document.tabcount]=t;
  document.tabkeys[document.tabcount]=key;
  if (opts&&opts.bingo) document.tabtitles[document.tabcount].bingo=opts.bingo;
  document.tabcount++;
  showtab(key,opts);
  

  var rq=xmlHTTPRequestObject();
  var scn=document.appsettings.codepage+'?cmd=';
  if (opts&&opts.fastlane) scn=document.appsettings.fastlane+'?cmd=';
  if (opts&&opts.bingo) scn=document.appsettings.binpages[opts.bingo+'']+'?cmd=';
    
  if (document.wssid) params=params+'&wssid_='+document.wssid;  
  
  rq.open('POST',scn+params+'&hb='+hb(),true);
  
  rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
  rq.onreadystatechange=function(){
    if (rq.readyState==4){
	  if (c.slowtimer) clearTimeout(c.slowtimer);
	  
	  var xtatus=rq.getResponseHeader('X-STATUS');
	  if (rq.status==403||(xtatus|0)==403){
		    if (self.skipconfirm) skipconfirm(); 
		  	window.location.href='login.php';
		    return;
      }

	var apperror=rq.getResponseHeader('apperror');
	if (apperror!=null&&apperror!=''){
		salert('Error: '+decodeURIComponent(apperror));
		document.tablock=null;
		return;	
	}  
	
		var newtitle=rq.getResponseHeader('newtitle');
		if (newtitle!=null&&newtitle!=''){
			settabtitle(key,decodeURIComponent(newtitle));	
		}

		var newloadfunc=rq.getResponseHeader('newloadfunc');
		if (newloadfunc!=null&&newloadfunc!='') loadfunc=function(){eval(newloadfunc)};	       
	      
		var parenttab=rq.getResponseHeader('parenttab');
		if (parenttab!=null&&parenttab!='') {		
			t.parenttab=parenttab;
		}
      	  
	  var reloader="<div class=\"reloader\" id=\"tabreloader_"+key+"\"><a onclick=\"refreshtab('"+key+"');\">"+document.dict['tab_reload']+"</a></div>";
      c.innerHTML=reloader+rq.responseText;

      document.tablock=null;
      if (loadfunc!=null) loadfunc(rq);
      if (opts&&opts.bookmark) gototabbookmark(opts.bookmark,true);
    }
  }
  rq.send(data);
}

closetab=function(key){
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (document.tabtitles[tabid].autosaver) {clearTimeout(document.tabtitles[tabid].autosaver);document.tabtitles[tabid].autosavertimer=null;}  
    
  gid('tabtitles').removeChild(document.tabtitles[tabid]);
  gid('tabviews').removeChild(document.tabviews[tabid]);

  var i;
  for (i=tabid;i<document.tabcount-1;i++){
	  document.tabtitles[i]=document.tabtitles[i+1];
	  document.tabviews[i]=document.tabviews[i+1];
	  document.tabkeys[i]=document.tabkeys[i+1];	  
  }
  document.tabcount--;
  
  if (document.resizefuncs){
	  var keyparts=key.split('_');
      var ckey=keyparts[0];

	if (document.resizefuncs[ckey]!=null) delete document.resizefuncs[ckey];	  
  }   

	if (document.tabseq){
		for (var i=0;i<document.tabseq.length;i++) if (document.tabseq[i]==key) document.tabseq[i]=null;
	}
		
	if (document.currenttab==tabid) {
		document.currenttab=0;
		var lasttab=null;
		while (lasttab==null&&document.tabseq.length>0){
			lasttab=document.tabseq.pop();	
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

function refreshtab(key,skipconfirm){
	
  var tabid=gettabid(key);
  if (tabid==-1) return;
  
  if (!skipconfirm&&!sconfirm(document.dict['confirm_refresh_tab'])) return;

  document.tabtitles[tabid].conflicted=null; 
  var tab=document.tabtitles[tabid];
  if (!tab.reloadinfo) return;
  tab.style.color='#000000';
  
  var keyparts=key.split('_');
  var ckey=keyparts[0];

  reloadtab(key,null,tab.reloadinfo.params,tab.reloadinfo.loadfunc,tab.reloadinfo.data,tab.reloadinfo.opts);
    
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

function sconfirm(msg){
	var a=hb();
	var res=confirm(msg);
	var b=hb();
	if (b-a<120) window.location.reload();
	return res;
}

function salert(msg){
	var a=hb();
	alert(msg);
	var b=hb();
	if (b-a<120) window.location.reload();
}

function sprompt(title,def){
	var a=hb();
	var res=prompt(title,def);
	var b=hb();
	if (b-a<120) window.location.reload();
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
	var d,delta;
	
	if (!document.iphone_portrait){
		if (!gid(id)||!gid('tabviews')) return;
		d=gid('tabviews'); delta=90;
	} else {
		if (!gid(id)) return;
		d=document.body; delta=130;
	}
	
	//d.scrollTop=gid(id).offsetTop-delta; return; //uncomment this line to disable animation
	
	var diff=gid(id).offsetTop-delta-d.scrollTop;
	var seq=[];
	while (Math.abs(diff)>20){seq.push(gid(id).offsetTop-diff); diff=Math.round(diff/4);}
	seq.push(gid(id).offsetTop-delta);
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
	
	if (!document.iphone_portrait){
		if (!gid('tabviews')) return;
		gid('tabviews').scrollTop=d.parentNode.parentNode.offsetTop-120;
	} else {
		document.body.scrollTop=d.parentNode.parentNode.offsetTop-140;
	}	
}

function marktabchanged(tabkey,hide){
	var mode='block';
	if (hide) mode='none';
	if (gid('changebar_'+tabkey)) gid('changebar_'+tabkey).style.display=mode;
	else return;
	
	if (document.appsettings.autosave==null) return;
		
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