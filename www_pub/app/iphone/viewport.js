
// Feature detection for visualViewport API (mobile keyboard handling)
document.vvSupported=!!(window.visualViewport&&typeof window.visualViewport.height==='number'&&typeof window.visualViewport.addEventListener==='function');
document.scrollIntoViewSupported=document.vvSupported; // mobile browsers with visualViewport also support scrollIntoView options
document.vvKeyboardOpen=false;
document.vvHeight=null;
document.vvWidth=null;
document.vvOffsetTop=0;
document.fullLayoutHeight=0;
document.fullLayoutWidth=0;

function ch(){
  // Always return full layout height. Keyboard-aware sizing is done explicitly
  // in scaleall() and other layout functions using document.vvHeight/fullLayoutHeight.
  // Overriding ch() to return visual viewport height caused all panels to shrink
  // dramatically, including elements that should stay full-height.
  if (window.innerHeight) return window.innerHeight;
  if (document.documentElement.clientHeight) return document.documentElement.clientHeight;
  return document.body.clientHeight;
}

function cw(){
  // Always return full layout width. Width doesn't change significantly with keyboard.
  if (window.innerWidth) return window.innerWidth;
  if (document.documentElement.clientWidth) return document.documentElement.clientWidth;
  return document.body.clientWidth;
}

function initViewportKeyboard(){
  if (!document.vvSupported) return;
  var vv=window.visualViewport;

  // Store initial full dimensions (before any keyboard)
  document.fullLayoutHeight=window.innerHeight;
  document.fullLayoutWidth=window.innerWidth;
  document.vvHeight=vv.height;
  document.vvWidth=vv.width;

  function onViewportChange(){
    var prevKeyboardOpen=document.vvKeyboardOpen;
    var layoutH=window.innerHeight;
    var vvH=vv.height;
    var vvW=vv.width;
    var vvOT=vv.offsetTop;

    // Detect keyboard: visual viewport significantly smaller than layout viewport
    // 150px threshold separates keyboard (>200px on most devices) from browser chrome (~50-70px)
    var keyboardOpen=(layoutH-vvH)>150;

    document.vvKeyboardOpen=keyboardOpen;
    document.vvHeight=vvH;
    document.vvWidth=vvW;
    document.vvOffsetTop=vvOT;

    if (!keyboardOpen){
      // Store full dimensions when keyboard closes
      document.fullLayoutHeight=layoutH;
      document.fullLayoutWidth=window.innerWidth;
    }

    // Only relayout when keyboard state changed or viewport height shifted significantly
    var vvHeightChanged=(document._lastVvH===undefined)||Math.abs(vvH-document._lastVvH)>10;
    document._lastVvH=vvH;

    if (prevKeyboardOpen!==keyboardOpen||(keyboardOpen&&vvHeightChanged)){
      // Reposition dismounted lkv if visible
      if (document.lkvdismounted&&gid('lkv')&&gid('lkv').showing){
        repositionDismountedLkv();
      }

      // Reposition corner tab
      if (document.cornertabkey&&!document.cornerminimized){
        resizeall_corner();
      }
    }

  }

  vv.addEventListener('resize',onViewportChange);
  // scroll event fires on iOS when page scrolls relative to visual viewport
  vv.addEventListener('scroll',onViewportChange);
}

function scrollActiveInputIntoView(){
  var activeEl=document.activeElement;
  if (!activeEl) return;
  var tag=activeEl.tagName.toLowerCase();
  if (tag!=='input'&&tag!=='textarea'&&tag!=='select'&&!activeEl.isContentEditable) return;

  // Find the nearest scrollable ancestor
  var scrollParent=activeEl.parentElement;
  while(scrollParent&&scrollParent!==document.body){
    var style=window.getComputedStyle(scrollParent);
    var overflowY=style.overflowY;
    if((overflowY==='auto'||overflowY==='scroll')&&scrollParent.scrollHeight>scrollParent.clientHeight){
      break;
    }
    scrollParent=scrollParent.parentElement;
  }
  if(!scrollParent||scrollParent===document.body) scrollParent=null;

  if(scrollParent){
    // Use getBoundingClientRect to check where the input is relative to the
    // visible scroll container. This avoids inflated offsetTop values from
    // the bottom padding, since getBoundingClientRect only reflects visible position.
    var parentRect=scrollParent.getBoundingClientRect();
    var elRect=activeEl.getBoundingClientRect();
    var elCenter=elRect.top+elRect.height/2-parentRect.top;
    var visibleHeight=parentRect.height;

    // If the input is already in the upper portion, no need to scroll
    // Use a percentage threshold (50%) so it scales with container size.
    // The old formula (0.4+200px) never triggered on mobile: a 250px container
    // gave threshold 300px, which exceeded the container height entirely.
    if(elCenter<visibleHeight*0.5) return;

    // Scroll just enough to move the input into the upper portion of the container.
    // We want it at ~30% from the top — high enough to stay above the keyboard,
    // not so high that it jumps dramatically.
    var targetRatio=0.3;
    var scrollNeeded=elCenter-visibleHeight*targetRatio;
    if(scrollNeeded<=0) return;

    var startPos=scrollParent.scrollTop;
    var targetScroll=startPos+scrollNeeded;
    var maxScroll=scrollParent.scrollHeight-scrollParent.clientHeight;
    if(targetScroll>maxScroll) targetScroll=maxScroll;
    if(targetScroll<0) targetScroll=0;

    var dist=targetScroll-startPos;
    if(Math.abs(dist)<5){
      scrollParent.scrollTop=targetScroll;
    }else{
      var duration=150;
      var startTime=null;
      function animStep(ts){
        if(!startTime) startTime=ts;
        var elapsed=ts-startTime;
        var progress=Math.min(elapsed/duration,1);
        var ease=1-(1-progress)*(1-progress);
        scrollParent.scrollTop=startPos+dist*ease;
        if(progress<1) requestAnimationFrame(animStep);
      }
      requestAnimationFrame(animStep);
    }
  }else{
    // No scrollable ancestor — fall back to scrollIntoView
    try{
      activeEl.scrollIntoView({block:'start',behavior:'smooth'});
    }catch(e){
      activeEl.scrollIntoView(true);
    }
  }
}

function repositionDismountedLkv(){
  var lkv=gid('lkv');
  if (!lkv||!document.lkvdismounted) return;

  var h,top;
  if (document.vvKeyboardOpen&&document.vvSupported){
    // Size to visual viewport (area above keyboard)
    h=document.vvHeight-40;
    top=50;
    if (document.iphone_portrait) top=55;
    // Clamp: don't make it too small
    if (h<100) h=100;
  }else{
    // Normal sizing
    h=ch()-60;
    if (document.iphone_portrait) h=ch()-70;
    top=50;
    if (document.iphone_portrait) top=55;
  }
  // Max height: respect 9:16 ratio (width capped at 400)
  var w=parseInt(lkv.style.width)||400;
  var maxH=Math.round(w*16/9);
  if (h>maxH) h=maxH;
  lkv.style.height=h+'px';
  lkv.style.top=top+'px';
  var lkvc=gid('lkvc');
  if (lkvc) lkvc.style.height=(h-33)+'px';
}

function initKeyboardFocusHandlers(){
  if (!document.vvSupported) return;

  document.addEventListener('focusin',function(e){
    var tag=e.target.tagName.toLowerCase();
    if (tag!=='input'&&tag!=='textarea'&&tag!=='select'&&!e.target.isContentEditable) return;

    // Defer scroll by one frame so the browser finishes processing the
    // focus/click event before we move the element. This prevents the
    // scroll from shifting an overlay (e.g. password manager) under the
    // cursor, causing an accidental click on the overlay.
    requestAnimationFrame(function(){scrollActiveInputIntoView();});

    // Delayed check: let visualViewport and scroll settle after keyboard animation.
    // 300ms — keyboard animation is mostly done by then on most devices.
    setTimeout(function(){
      var vv=window.visualViewport;
      if (!vv) return;
      var wasOpen=document.vvKeyboardOpen;
      if ((window.innerHeight-vv.height)>150){
        document.vvKeyboardOpen=true;
        document.vvHeight=vv.height;
        document.vvWidth=vv.width;
        document.vvOffsetTop=vv.offsetTop;
        if (!wasOpen){
          if (document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
          if (document.cornertabkey&&!document.cornerminimized) resizeall_corner();
        }
      }
    },300);
  });
}

function tabw(){
	var idw=cw();
	if (!document.tabafloat&&!document.widen) return idw-gid('tabviews').offsetLeft-40;
	return idw-40;	
}

function tabh(){
	var idh=ch();
	if (!document.tabafloat) return idh-gid('tabtitles').offsetTop;
	return idh;	
		
}

function resizeall_corner(){
	var ct=gid('cornertab');
	var cb=gid('cornerbutton');
	if (!ct&&!cb) return;

	// Use full pre-keyboard height for corner tab so it doesn't collapse
	// when the keyboard is open and the user is typing in it
	var idh=(document.vvKeyboardOpen&&document.vvSupported&&document.fullLayoutHeight)?document.fullLayoutHeight:ch();
	var idw=cw();
	var ucfg=document.appsettings.uiconfig;
	var mob=ucfg?ucfg.mobile:null;
	var mobl=ucfg?ucfg.mobile_landscape:null;

	if (document.iphone_portrait){
		// Portrait: use mobile.toolbar_position
		var toolbar_bottom=(mob&&mob.toolbar_position=='bottom');
		var bot_off=toolbar_bottom?50:10;
		var top_off=toolbar_bottom?10:50;
		if (ct&&ct.style.display!='none'){
			ct.style.width='90vw';
			ct.style.height=Math.max(200,idh-top_off-bot_off-30)+'px';
			ct.style.bottom=bot_off+'px';
			ct.style.top='';
			ct.style.right='5vw';
			ct.style.left='5vw';
		}
		if (cb){
			cb.style.bottom=bot_off+'px';
			cb.style.right='10px';
		}
	} else {
		// Landscape: use mobile_landscape.toolbar_position (fall back to mobile)
		var toolbar_bottom=(mobl&&mobl.toolbar_position=='bottom')||(mob&&mob.toolbar_position=='bottom'&&!mobl);
		var bot_off=toolbar_bottom?50:10;
		var top_off=toolbar_bottom?10:50;
		if (ct&&ct.style.display!='none'){
			ct.style.width='300px';
			ct.style.height=Math.max(200,idh-top_off-bot_off-20)+'px';
			ct.style.bottom=bot_off+'px';
			ct.style.top='';
			ct.style.left='';
			ct.style.right='10px';
		}
		if (cb){
			cb.style.bottom=bot_off+'px';
			cb.style.right='10px';
		}
	}
}

function scaleall(root){
  var i,j;
  var idh=ch();
  var idw=cw();
  
  var stretch=0;
  
  if (document.appsettings.uiconfig&&document.appsettings.uiconfig.mobile&&document.appsettings.uiconfig.mobile.toolbar_position=='bottom') stretch=35;
  
  var os=root.getElementsByTagName('div'); //AKB#2
  
	if (!document.iphone_portrait){
		if (document.tabafloat){
			gid('content').style.height=idh+'px';
			gid('tabviews').style.height=idh+'px';
		} else {
			var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
			var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
			var lscape_left=(mobl&&mobl.toolbar_position=='left');
			var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
			if (lscape_left&&!lscape_vnp){
				// vnp=false: top bar (logo+power) + left panel (menu items)
				gid('content').style.height=idh+'px';
				// Reposition tabviews based on current tab row count
				var rc=document.rowcount>=1?document.rowcount:1;
				gid('tabviews').style.top=17+26*rc+'px';
				gid('tabviews').style.height=(idh-17-26*rc)+'px';
				// qnav is position:fixed, set its top to match tabviews
				var qns2=document.getElementsByClassName('qnav_');for(var qi2=0;qi2<qns2.length;qi2++) qns2[qi2].style.top=17+26*rc+'px';
				// Recalculate mainmenu height on resize: full height minus 40px top bar
				gid('mainmenu').style.height=(idh-40)+'px';
				var mmi=gid('mainmenuitems');if(mmi) mmi.style.height=(idh-40)+'px';
			} else if (lscape_left){
					// vnp=true: 40px top bar + collapsible icon sidebar
					gid('content').style.height=idh+'px';
					// Reposition tabviews based on current tab row count
					var rc=document.rowcount>=1?document.rowcount:1;
					gid('tabviews').style.top=17+26*rc+'px';
					gid('tabviews').style.height=(idh-17-26*rc)+'px';
					gid('mainmenu').style.height=(idh-40)+'px';
					var vnav_acct2=gid('vnav_account');var vnav_h2=(vnav_acct2?vnav_acct2.offsetHeight:0);
					var mmi2=gid('mainmenuitems');if(mmi2) mmi2.style.height=(idh-40-vnav_h2)+'px';
					// Set qnav left position based on quicklist state
					var qns2=document.getElementsByClassName('qnav_');
					var qnavLeft=document.appsettings.quicklist?'210px':'54px';
					for(var qi2=0;qi2<qns2.length;qi2++){qns2[qi2].style.left=qnavLeft;qns2[qi2].style.top='';}
						// Resize lkv when mounted in sidebar (vnp=true quicklist)
						if (!document.lkvdismounted&&gid('lkv')&&document.appsettings.quicklist) {
							gid('lkv').style.height=(idh-55)+'px';
							gid('lkvc').style.height=(idh-55-24)+'px';
							gid('lkv').style.top='50px';
						}
			} else {
				gid('leftview').style.height=(idh-58)+'px';
				gid('content').style.height=(idh-55)+'px';
				gid('lvviews').style.height=(idh-86)+'px';
				gid('tabviews').style.height=(idh-55)+'px';
				// Ensure qnav left position matches current quicklist state
				var qns_landscape=document.getElementsByClassName('qnav_');
				var qnav_left=document.appsettings.quicklist?'237px':'22px';
				for(var qi_l=0;qi_l<qns_landscape.length;qi_l++) qns_landscape[qi_l].style.left=qnav_left;
			}
		}
	} else {
		if (document.tabafloat){
			gid('tabviews').style.height=idh+'px';
		} else {
			gid('tabviews').style.height=(idh-105+stretch)+'px';
		}
		gid('lvviews').style.height=(idh-105+stretch)+'px';
	}

  if (document.rowcount){
		gid('tabtitleshadow').style.height=(26*document.rowcount-1)	  
  }

  
  gid('fsmask').style.width=idw+'px';
  gid('fsmask').style.height=idh+'px';

  gid('fsview').style.width=idw-20+'px';
  gid('fsview').style.height=idh-60+'px';
  
  gid('fstitlebar').style.width=idw-20+'px';  
  
  if (gid('gschat_chatbox')){
		var uptake=190; //space taken by other components
		gid('gschat').style.width=idw-30+'px';
		gid('gschat_chatbox').style.maxHeight=(idh-uptake)+'px';
	}
  
  	rescaletabs();   
	resizeall_corner();
}

function rescaletabs(){
  if (!document.resizefuncs) return;
  for (func in document.resizefuncs){
	var f='tabresizefunc_'+func;
	if (self[f]) self[f](document.resizefuncs[func]);	  
  }	
}

function flashsticker(msg,sec){
	if (document.stickertimer) clearTimeout(document.stickertimer);
	gid('gsstickercontent').innerHTML=msg;
	gid('gsstickerview').style.display='table';
	if (sec!=null){
		document.stickertimer=setTimeout(function(){
			gid('gsstickerview').style.display='none';
		},sec*1000);	
	}
		
}

function callout_section(d){
	if (d==null||!d) return;
	var callout=gid('callout');
	if (!callout) return;
	var rect=d.getBoundingClientRect();

	var h=ch()-60;
	
	var y=rect.y+10;
	if (y>h) y=h;
		
	callout.style.opacity=1;
	callout.style.top=y+'px';
	callout.style.left=(rect.x-56)+'px';
		
	var cls=d.className;
	
	d.className='calledout';
	setTimeout(function(){
		d.className=cls;	
		callout.style.opacity=0;
		callout.style.left=0;
		callout.style.top=h+'px';
	},500);
	
	
}

function showlookup(){
	var lkv=gid('lkv');

	if (lkv.showing) {
		// Already showing — reposition if keyboard is open
		if (document.vvKeyboardOpen&&document.vvSupported&&document.lkvdismounted) repositionDismountedLkv();
		return;
	}
	
	lkv.showing=true;

	var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	var lscape_left=(mobl&&mobl.toolbar_position=='left');
	var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
	
	lkv.style.top=-1*(ch()-40)-20+'px';
	if (document.fsshowing||document.iphone_portrait){
		lkv.style.top='55px';
		var w=cw()-70;
		if (w>400) w=400;
		lkv.style.width=w+'px';
		lkv.style.left=(cw()-w)/2+'px';

		var h=ch()-70;
		var maxH=Math.round(w*16/9);
		if (h>maxH) h=maxH;
		lkv.style.height=h+'px';
		gid('lkvc').style.height=h-24+'px';
		
	} else if (lscape_left&&lscape_vnp&&!document.tabafloat&&document.appsettings.quicklist) {
		// vnp=true with sidebar expanded: show lkv in sidebar area (like desktop)
		gid('leftview').style.zIndex=9999;
		lkv.style.width='210px';
		lkv.style.left='0px';
		lkv.style.top='50px';
		if(gid('lkvs'))gid('lkvs').style.display='none';
		lkv.style.height=(ch()-55)+'px';
		gid('lkvc').style.height=(ch()-55-24)+'px';
	} else {
		// landscape: center the lookup popup on screen
		lkv.style.top='50px';
		var w=cw()-30;
		if (w>400) w=400;
		lkv.style.width=w+'px';
		lkv.style.left=(cw()-w)/2+'px';
		var h=ch()-60;
		var maxH=Math.round(w*16/9);
		if (h>maxH) h=maxH;
		lkv.style.height=h+'px';
		gid('lkvc').style.height=(h-24)+'px';
	}
}

function hidelookup(){
	var lkv=gid('lkv');
	if (!lkv.showing) return;
	
	lkv.showing=null;
	
	var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	var lscape_left=(mobl&&mobl.toolbar_position=='left');
	var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
	
	lkv.style.top=-1*(ch()-40)-20+'px';
	if (document.fsshowing||document.iphone_portrait){
	} else if (lscape_left&&lscape_vnp&&document.appsettings.quicklist) {
		// vnp=true with sidebar expanded: hide lkv by moving off-screen
		lkv.style.left='-280px';
		if(gid('lkvs'))gid('lkvs').style.display='';
	} else {
		lkv.style.left='-500px';
	}	
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
	document.fsshowing=true;
	gid('fsmask').style.display='block';
	gid('fstitlebar').style.display='block';
	gid('fsview').style.display='block';
	gid('fsclose').closeaction=func;
	if (initfunc) initfunc();
}

function closefs(){
	document.fsshowing=null;
	setnosleep(false);
	gid('fsview').style.display='none';
	gid('fstitlebar').style.display='none';
	gid('fsmask').style.display='none';

	if (gid('fsclose').closeaction) gid('fsclose').closeaction();	
}

function loadfs(title,cmd,func,initfunc,bingo){
	setnosleep(true);
	var codepage=document.appsettings.codepage;
	if (bingo) codepage=document.appsettings.binpages[bingo+''];
	ajxpgn('fsview',codepage+'?cmd='+cmd,1,0,'',function(){
		gid('fstitle').innerHTML=title;	
		showfs(func,initfunc);	
	});
}


hinttimer=-2;

function quicklist_to_dash(){
	if (document.viewindex==null||document.viewindex=='') return;
	var idx=document.viewindex;
	var dashkey='dash_'+idx.replace(/\./g,'__');
	
	var params=gid('lv'+idx).params;
	gid('lv'+idx).innerHTML='';
	gid('lv'+idx).viewloaded=null;
	
	if (document.appsettings.quicklist) setquicklist(false);
	addtab(dashkey,idx,'dash_'+idx.replace(/\./g,'__')+'&'+params);
}

function dash_to_quicklist(){
	var tabid=document.currenttab;
	if (tabid==null||tabid==-1) return;
	
	var tabkey=document.tabkeys[tabid];
	var idx=tabkey.replace('dash_','').replace(/__/g,'.');
	
	var found=false;
	for (var i=0;i<document.appsettings.views.length;i++){
		if (document.appsettings.views[i]==idx){
			found=true;
			break;
		}
	}
	
	if (!found) return;
	
	closetab(tabkey);
	if (!document.appsettings.quicklist) setquicklist(true);
	showview(idx);
}

function autosize(){

  scaleall(document.body);
  var caleview=gid('caleview');
  if (caleview){

  }
  if (document.tabcount>0){
  var t=document.tabtitles[document.tabcount-1];
//wrapping
      document.rowcount=(t.offsetTop-6)/26+1;
      if(document.rowcount<1) document.rowcount=1;
      if (!document.lastrowcount) document.lastrowcount=1;
      // Always set tabtitles height and tabviews layout
      gid('tabtitles').style.height=26*document.rowcount+'px';
      var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
      var lscape_left=(mobl&&mobl.toolbar_position=='left');
      var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
      if (lscape_left&&!lscape_vnp) {
        // vnp=false: tabs start at top:17px, tabviews starts after last tab row
        gid('tabviews').style.top=17+26*document.rowcount+'px';
        gid('tabviews').style.height=(ch()-17-26*document.rowcount)+'px';
        gid('tabviews').setAttribute("scale:ch",17+26*document.rowcount);
        // qnav is position:fixed, so set its top to match tabviews
        var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++) qns[qi].style.top=17+26*document.rowcount+'px';
      } else {
        gid('tabviews').style.top=80+26*(document.rowcount-1)+'px';
        gid('tabviews').setAttribute("scale:ch",105+26*(document.rowcount-1));
      }
      if (document.lastrowcount!=document.rowcount) {
        scaleall(document.body);
      }
      document.lastrowcount=document.rowcount;
  }
  if (self.update_qnav_padding) update_qnav_padding();
}

function hintstatus(t,d){
  if (hinttimer!=-2) clearTimeout(hinttimer);
  gid('statusinfo').innerHTML='<a>'+t+'</a>';
  d.onmouseout=function(){
    gid('statusinfo').innerHTML='';
  }
}

function flashstatus(t,l){
	if (l){
		gid('statusc').innerHTML=t;
		hinttimer=setTimeout(function(){gid('statusc').innerHTML='';},l);
	}
	
	if (window.Notification){
		if (document.lastnotification==t) return;
		document.lastnotification=t;
		var n=new Notification('Gyroscope',{body:t}); 
		setTimeout(function(){document.lastnotification=null;},10000);
	}

}


function reloadview(idx,listid){
	hidelookup();
	if (document.viewindex!=idx) return;

	if (self.onrotate) onrotate();
	
	var params='';
	if (gid('lv'+document.viewindex)) params=gid('lv'+document.viewindex).params;
	var bingo=gid('lv'+document.viewindex).bingo;
		
	if (listid) reajxpgn(listid,'lv'+idx);
	else showview(idx,0,0,params,null,bingo);
}

function showview(idx,lazy,force,params,func,bingo,submenu){
	if (gid('defleftview')) gid('defleftview').style.display='none';
	
    var codepage=document.appsettings.codepage;
    if (bingo>0) codepage=document.appsettings.binpages[bingo+''];
	if (!params) params='';

		var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
		var lscape_left=(mobl&&mobl.toolbar_position=='left');
		var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
		// Left toolbar doc-centric mode (vnp=false): dash items reload the welcome tab,
		// submenu items open as new tabs with boundless class (left panel shows bookmarks)
		if (lscape_left&&!lscape_vnp&&!document.iphone_portrait){
			if (!submenu){
				// Dash items: reload welcome tab (keeps left menu visible)
				reloadtab('welcome','','dash_'+idx.replace(/\./g,'__')+'&'+params,func,null,{evaljs:true});
				if (document.appsettings.uiconfig.vertical_nav_persist) showtab('welcome');
			} else {
				// Submenu items: open as new tab (left panel shows bookmarks or goes blank)
				addtab('dash_'+idx.replace(/\./g,'__'),idx,'dash_'+idx.replace(/\./g,'__')+'&'+params,func,null,{evaljs:true});
			}
			gid('lv'+idx).viewloaded=null;
			gid('lv'+idx).innerHTML='';
			if (document.viewindex==idx){document.viewindex=null;gid('tooltitle').innerHTML='';}
			return;
		}
		// vnp=true: always open as tab (lv_ promoted to dash_); otherwise quicklist off: open as tab
		var reroute_to_tab=((lscape_left&&lscape_vnp)||!document.appsettings.quicklist)&&!document.iphone_portrait;
		if (reroute_to_tab){
			addtab('dash_'+idx.replace(/\./g,'__'),idx,'dash_'+idx.replace(/\./g,'__')+'&'+params,func);
			gid('lv'+idx).viewloaded=null;
			gid('lv'+idx).innerHTML='';
			if (document.viewindex==idx){document.viewindex=null;gid('tooltitle').innerHTML='';}
			return;
		}

	
	// Close any existing dash tab for this view to prevent DOM id collision
	var dashtabid=gettabid('dash_'+idx.replace(/\./g,'__'));
	if (dashtabid>=0) closetab(document.tabkeys[dashtabid]);

	
	if (!force&&document.viewmode!=1&&document.iphone_portrait) return;	
	document.viewmode=1;
	rotate();
	hidelookup();
  
  if (document.viewindex!=null) {
	  gid('lv'+document.viewindex).tooltitle=gid('tooltitle').innerHTML;
  }

  if (gid('lv'+idx)) {
	  gid('lv'+idx).params=params;
	  gid('lv'+idx).bingo=bingo;
  }
  
  for (var k=0;k<document.appsettings.views.length;k++){
	var i=document.appsettings.views[k];
    if (i!=idx) {
      gid('lv'+i).style.display='none';
    } else {
		if (!lazy||document.viewindex==idx||!gid('lv'+i).viewloaded){
			if (document.lvxhr&&document.lvxhr.reqobj) {document.lvxhr.abortflag=1;document.lvxhr.reqobj.abort();document.lvxhr.reqobj=null;cancelgswi(document.lvxhr);}
			document.lvxhr=gid('lv'+i);			
			ajxpgn('lv'+i,codepage+'?cmd=slv_'+i.replace(/\./g,'__')+'&'+params,true,true,'',function(rq){
				var title=rq.getResponseHeader('listviewtitle');
				if (title!=null&&title!='') gid('tooltitle').innerHTML='<a>'+decodeURIComponent(title)+'</a>';
				var flag=rq.getResponseHeader('listviewflag');
				var js=rq.getResponseHeader('listviewjs');
				if (flag!=null&&js!=null&&js!=''){
					ajxjs2(flag,js,function(){
						if (func!=null) func();				
					});
					//sajxjs(flag,js);	
				}				
			});
		} else {
			gid('lv'+idx).style.display='block';
			if (gid('lv'+idx).tooltitle!=null&&gid('lv'+idx).tooltitle!='') gid('tooltitle').innerHTML=gid('lv'+idx).tooltitle;
		}
    }
  }

  gid('lv'+idx).viewloaded=1;
  document.viewindex=idx;
  if (force&&self.onrotate) onrotate();
  if (self.livechat_updatesummary&&document.chatstatus=='online') livechat_updatesummary();
  
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
  rq.open('POST',document.appsettings.codepage+'?cmd=pump&hb='+stamp,true);
  rq.onreadystatechange=f;
  rq.send('nocache='+stamp);
}

function sv(d,v,r){
	gid(d).value=v;
	if (r&&gid(d).onchange) {
		var ev=document.createEvent('Events'); ev.initEvent('change',true,false);
		gid(d).dispatchEvent(ev);
	}
}

if (document.createEvent){
	document.keyboard=[];
	
	window.onblur=function(){
		document.keyboard=[];
		document.gamepadlock=true;	
	}	
	
	document.onkeyup=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;	
		document.keyboard['key_'+keycode]=null;
		delete document.keyboard['key_'+keycode];
	}
	
	document.onkeydown=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;	
		document.keyboard['key_'+keycode]=1;
		
		var metakey=0;
		if (document.keyboard['key_17']||document.keyboard['key_91']||document.keyboard['key_224']) metakey=1;
		
		if (document.keyboard['key_13']&&metakey){
			picktop();	
		}
			
		if (document.keyboard['key_83']&&metakey){
			savecurrenttab();
			return false;
		}
		
		if (document.keyboard['key_16']&&document.keyboard['key_70']&&document.keyboard['key_55']&&!document.fleetview){
			document.fleetview=true;
			updategyroscope();	
		}		
		
		if (metakey&&document.keyboard['key_190']&&document.keyboard['key_188']) toggletabdock();

		// Meta+Shift+; - toggle corner tab (Chrome: keyCode 186, Firefox: keyCode 59)
		if (metakey&&document.keyboard['key_16']&&(document.keyboard['key_186']||document.keyboard['key_59'])) {
			if (self.toggletabcorner) toggletabcorner();
			return false;
		}

		if (metakey&&document.keyboard['key_16']&&document.keyboard['key_82']) {refreshtab(document.tabkeys[document.currenttab]);return false;}
		if (!document.fsshowing&&metakey&&document.keyboard['key_16']&&document.keyboard['key_52']&&document.tabtitles[document.currenttab]!=null&&!document.tabtitles[document.currenttab].noclose) {
			if (!sconfirm('Are you sure you want to CLOSE the current tab?')) return;
			closetab(document.tabkeys[document.currenttab]);
		}
		if (document.fsshowing&&metakey&&document.keyboard['key_16']&&document.keyboard['key_52']) {
			closefs();
		}
		
		
	}

	function savecurrenttab(){
		if (document.currenttab==null||document.currenttab==-1) return;
		if (!document.tabviews[document.currenttab]) return;
		var bts=document.tabviews[document.currenttab].getElementsByTagName('button');
		var bt=null;
		for (var i=0;i<bts.length;i++){
			if (bts[i].className&&bts[i].className=='changebar_button'){
				bt=bts[i];	
			}
		}
		if (!bt) return;
		
		var event=document.createEvent('Events');
		event.initEvent('click',true,false);
		bt.dispatchEvent(event);		
		
	}
	
	function picktop(){
		document.keyboard=[];
		if (!document.hotspot) return;
		if (!gid('lkvc')) return;
		var os=gid('lkvc').getElementsByTagName('a');
		var target=null;
		for (var i=0;i<os.length;i++){
			var o=os[i];
			if (o.parentNode&&(o.parentNode.className=='listitem'||o.attributes.pickable)&&o.onclick!=null){
				target=o;
				break;	
			}
		}//for
		if (!target) return;
		
		var event=document.createEvent('Events');
		event.initEvent('click',true,false);
		target.dispatchEvent(event);
		
	}
}


function toggle_easyread(){
	if (!document.easyreading){
		ajxcss(null,'easyon.css','easyon','easyoff');
		document.easyreading=true;	
	} else {
		ajxcss(null,'easyoff.css','easyoff','easyon');
		document.easyreading=null;	
	}
}

function toggle_easyread_start(){
	if (document.easyreadswitcher) clearTimeout(document.easyreadswitcher);
	document.easyreadswitcher=setTimeout(function(){
		toggle_easyread();
	},1200);	
}

function toggle_easyread_end(){
	if (document.easyreadswitcher) clearTimeout(document.easyreadswitcher);	
}

function showhelpspot(id,once){
	showhide('helpspot_'+id);
	if (gid('helpspot_'+id).showing){
		gid('phelpspot_'+id).style.width='100%';
	} else {
		gid('phelpspot_'+id).style.width='auto';		
	}
}

function hidehelpspot(id,topic,once,gskey){
	gid('helpspot_'+id).style.display='none';
	if (once){
		gid('helpanchor_'+id).style.display='none';	
		ajxpgn('statusc',document.appsettings.codepage+'?cmd=ackhelpspot&topic='+encodeHTML(topic),0,0,null,null,null,null,gskey);
		var os=document.getElementsByTagName('span');
		for (var i=0;i<os.length;i++) if (os[i].attributes&&os[i].attributes.helptopic&&os[i].attributes.helptopic.value==topic) os[i].style.display='none';
	}
}

function loaddash(tabkey,title,cmd){
	//always load as split tabs
	addtab(tabkey,title,cmd);
}

function resetdarkmode(darkmode,csstail){
	if (!csstail) csstail='.php';
	
	if (gid('ajxcss_gyrodemo')){
		ajxcss(null,'iphone/gyrodemo_css'+csstail+'?dark='+darkmode,'gyrodemo','gyrodemo');
		hdpromote('iphone/gyrodemo_hd_css'+csstail+'?dark='+darkmode);
	}
		
	if (gid('ajxcss_toolbar')){
		ajxcss(null,'toolbar_css'+csstail+'?dark='+darkmode,'toolbar','toolbar');
		hdpromote('toolbar_hd_css'+csstail+'?dark='+darkmode);
	}	
	
	if (gid('ajxcss_passrules')){
		ajxcss(null,'passrules_css'+csstail+'?dark='+darkmode,'pssrules','passrules');		
	}		
	
	if (gid('ajxcss_portrait')){
		ajxcss(null,'iphone/portrait_css'+csstail+'?dark='+darkmode,'portrait','portrait');
	}			
	
	if (gid('ajxcss_landscape')){
		ajxcss(null,'iphone/landscape_css'+csstail+'?dark='+darkmode,'landscape','landscape');
	}			
	
}

function listmenu_setcurrent(d){
	var menu=gid('mainmenu');
	if (!menu) return;
	var os=menu.getElementsByTagName('div');
	for (var i=0;i<os.length;i++){
		var o=os[i];
		if (o.className=='listmenuitem'||o.className=='listmenuitem current') o.className='listmenuitem';
	}
	d.parentNode.className='listmenuitem current';
}
function showmainmenu(){
	if (!document.iphone_portrait) {
		gid('mainmenu').style.visibility='visible';
		gid('bookmarkview').style.display='none';
		gid('bookmarkview').innerHTML='';
		gid('bookmarkview').bookmarks=null;
	}
	// listmenu_setcurrent is called by the onclick handlers in listwelcome
}
function showmmastersearch(){
	var ms=gid('mmastersearch');
	var mob=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile:null;
	var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	var expandSearch=false;
	if (document.iphone_portrait && mob && mob.enable_master_search) expandSearch=true;
	if (!document.iphone_portrait && mobl && mobl.enable_master_search) expandSearch=true;
	if (expandSearch){
		// Expand from icon (skip if already expanded)
		if (ms.style.display==='inline-block' && gid('msearchicon').style.display==='none') return;
		ms.style.display='inline-block';
		var micon=gid('msearchicon');
		if (micon) micon.style.display='none';
		var shell=ms.querySelector('.mastersearchshell');
		var expandW=0;
		if (shell){
				// All left-toolbar and non-left modes: search expands from top-right
				var taW=gid('toolbaractions')?(gid('toolbaractions').offsetWidth||0):0;
				expandW=cw()-taW-10;
				if (expandW>400) expandW=400;
				if (expandW<150) expandW=150;
				shell.style.width=expandW+'px';
			}
		gid('mastersearch').className='expanded';
		var mcloser=gid('msearchcloser');
		if (mcloser) mcloser.style.display='block';
		gid('mastersearch').focus();
		// Position search overlay below toolbar
		if (document.iphone_portrait) {
			gid('mainsearchview_').style.top='45px';
			gid('mainsearchview_').style.left='0';
			gid('mainsearchview_').style.right='';
			gid('mainsearchview_').style.width='100%';
		} else {
				gid('mainsearchview_').style.top='40px';
				// Align search results panel: extend left past input field, allow extending under power button
				var msEl=gid('mmastersearch');
				var msRight=msEl?msEl.getBoundingClientRect().right:cw();
				var searchLeft=msRight-expandW-50;
				if (searchLeft<0) searchLeft=0;
				var searchWidth=cw()-searchLeft+5;
				gid('mainsearchview_').style.left=searchLeft+'px';
				gid('mainsearchview_').style.right='';
				gid('mainsearchview_').style.width=searchWidth+'px';
		}
		if (gid('mastersearch').value!='') showmainsearchview();
	} else {
		// Default: show as full-width overlay (search disabled)
		ms.style.display='block';
		gid('mastersearch').focus();
		if (gid('mastersearch').value!='') showmainsearchview();
	}
}

function hidemmastersearch(){
	var ms=gid('mmastersearch');
	var shell=ms?ms.querySelector('.mastersearchshell'):null;
		gid('mastersearch').className='';
		gid('mastersearch').value='';
		// Delay hiding so clicks on search results can fire before blur dismisses the dropdown
		setTimeout(function(){
			if (shell){shell.style.width='0';}
			var micon=gid('msearchicon');
			if (micon) micon.style.display='';
			var mcloser=gid('msearchcloser');
			if (mcloser) mcloser.style.display='none';
			ms.style.display='none';
			hidemainsearchview();
		},200);
}

function showdeck(){
	switch(document.viewmode){
		case 1:
			gid('leftview').style.display='block';
			gid('tabtitles').style.display='block';
			gid('content').style.display='none';
		break;
		case 2:
			gid('leftview').style.display='none';
			gid('tabtitles').style.display='none';
			gid('content').style.display='block';
		break;
	}
}

function appendMobileMenuItems(){
	var mmi=gid('mainmenuitems');
	if (!mmi) return;
	//if (document.enablelivechat){
	//	mmi.innerHTML+='<div class="listmenuitem"><a onclick="livechat_start();return false;"><img alt="live chat" id="chaticon" class="img-livechat" src="imgs/t.gif" width="28" height="28"> <span style="vertical-align:middle;">Chat</span></a></div>';
	//}
	//mmi.innerHTML+='<div class="listmenuitem"><a onclick="_gsreplay_rec_start(this);return false;"><img id="gsreplayicon" class="img-gsreplay" src="imgs/t.gif" width="28" height="28"> <span style="vertical-align:middle;">Replay</span></a></div>';
}

function reposition_bar_views(){
	var cbv=document.querySelectorAll('.changebar_view');
	var sbv=document.querySelectorAll('.savebar_view');
	if (document.iphone_portrait){
		for(var i=0;i<cbv.length;i++){cbv[i].style.left='';cbv[i].style.width='';cbv[i].style.top='';}
		for(var i=0;i<sbv.length;i++){sbv[i].style.left='';sbv[i].style.width='';sbv[i].style.top='';}
	} else if (document.tabafloat) {
		for(var i=0;i<cbv.length;i++){cbv[i].style.left='0';cbv[i].style.width='100%';cbv[i].style.top='0';}
		for(var i=0;i<sbv.length;i++){sbv[i].style.left='0';sbv[i].style.width='100%';sbv[i].style.top='0';}
	} else {
		// Position bars below tab rows using row-based formula
		var rc=document.rowcount>=1?document.rowcount:1;
		var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
		var lscape_left=(mobl&&mobl.toolbar_position=='left');
		var toolbar_bottom=(mobl&&mobl.toolbar_position=='bottom');
		var barTop=17+26*rc+2;
		if (!lscape_left&&!toolbar_bottom) barTop+=30;
		var ml=gid('content').style.marginLeft||'240px';
		for(var i=0;i<cbv.length;i++){cbv[i].style.left=ml;cbv[i].style.width='calc(100% - '+ml+')';cbv[i].style.top=barTop+'px';}
		for(var i=0;i<sbv.length;i++){sbv[i].style.left=ml;sbv[i].style.width='calc(100% - '+ml+')';sbv[i].style.top=barTop+'px';}
	}
}

function portrait_ignore(ttl){
	if (!ttl) ttl=2000;
	document.portraitlock=1;
	setTimeout(function(){document.portraitlock=null;},ttl);
}

function setquicklist(quicklist,noupdate){
	if (document.tabafloat&&!document.iphone_portrait) return;
	var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	var lscape_left=(mobl&&mobl.toolbar_position=='left');
	var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
	// When left toolbar with vertical_nav_persist=false, sidebar is always visible and cannot be toggled
	if (lscape_left&&!lscape_vnp&&!document.iphone_portrait) return;
	document.appsettings.quicklist=quicklist;
	if (!quicklist){
			if (lscape_left&&!document.iphone_portrait){
				// vnp=true: collapse sidebar to icon-only
				gid('mainmenu').className='collapsed';
				gid('applogo').src=document.appsettings.codepage+'?cmd=clogo&mode=sqr';
				gid('content').style.width=(document.appsettings.cw-54)+'px';
				gid('content').style.marginLeft='54px';
				gid('tabtitles').style.left='54px';gid('tabtitles').style.width=(document.appsettings.cw-54)+'px';gid('tabtitles').style.marginLeft='';
				gid('tabviews').style.left='54px';gid('tabviews').style.width=(document.appsettings.cw-54)+'px';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='54px';qns[qi].style.top='';}
				if(gid('vnavside_hide'))gid('vnavside_hide').style.display='none';if(gid('vnavside_show'))gid('vnavside_show').style.display='block';
			} else {
				gid('leftview').style.left='-230px';
				gid('mvptr').style.left='0';
				gid('mvptr').className='rexpand';
				gid('content').style.width=(document.appsettings.cw-24)+'px';
				gid('content').style.marginLeft='22px';
				gid('tabtitles').style.width=(document.appsettings.cw-24)+'px';
				gid('tabtitles').style.marginLeft='22px';
				gid('tabviews').style.left='22px';gid('tabviews').style.width=(document.appsettings.cw-24)+'px';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='22px';qns[qi].style.top='';}
			}
			lkv_dismount();
			if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
		} else {
			if (lscape_left&&!document.iphone_portrait){
				// vnp=true: expand sidebar to show text labels
				gid('mainmenu').className='';
				gid('applogo').src=document.appsettings.codepage+'?cmd=clogo';
				gid('content').style.width=(document.appsettings.cw-210)+'px';
				gid('content').style.marginLeft='210px';
				gid('tabtitles').style.left='210px';gid('tabtitles').style.width=(document.appsettings.cw-210)+'px';gid('tabtitles').style.marginLeft='';
				gid('tabviews').style.left='210px';gid('tabviews').style.width=(document.appsettings.cw-210)+'px';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='210px';qns[qi].style.top='';}
				if(gid('vnavside_hide'))gid('vnavside_hide').style.display='block';if(gid('vnavside_show'))gid('vnavside_show').style.display='none';
			} else {
				gid('leftview').style.left='5px';
				gid('leftview').style.width='210px';
				gid('mvptr').style.left='215px';
				gid('mvptr').className='';
				gid('content').style.width=(document.appsettings.cw-240)+'px';
				gid('content').style.marginLeft='240px';
				gid('tabtitles').style.width=(document.appsettings.cw-240)+'px';
				gid('tabtitles').style.marginLeft='';
				gid('tabviews').style.left='237px';gid('tabviews').style.width=(document.appsettings.cw-240)+'px';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='237px';qns[qi].style.top='';}
			}
			if (lscape_left) lkv_remount(); else lkv_dismount();
			if (document.vvKeyboardOpen&&document.lkvdismounted&&gid('lkv')&&gid('lkv').showing) repositionDismountedLkv();
		}
		// vnp=true uses vnavside buttons instead of mvptr - no span toggle needed (CSS hides them)
		if (!noupdate) ajxpgn('statusc',document.appsettings.codepage+'?cmd=setmyquicklist&silent=1&quicklist='+(quicklist?1:0));
	scaleall(document.body);
	reposition_bar_views();
	update_qnav_padding();
		setTimeout(function(){scaleall(document.body);if(typeof tab_reflow==='function')tab_reflow();},250);
}

function onrotate(){
	if (document.resizetimer) clearTimeout(document.resizetimer);
	document.resizetimer=setTimeout(function(){
		rotate();
		setTimeout(rotate,500);
	},100);
}
