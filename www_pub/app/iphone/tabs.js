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

tab_reflow=function(){
	var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	if (!mobl||mobl.toolbar_position!=='left') return;
	var idw=gid('tabtitles').offsetWidth||cw();
	var taW=gid('toolbaractions')?(gid('toolbaractions').offsetWidth||0):0;
	// Subtract toolbar actions width from available row capacity
	var rowcap=idw-taW-8; // 8px comfort margin
	if (rowcap<100) rowcap=idw; // fallback: use full width if actions are too wide
	var rowsum=0;
	var os=gid('tabtitles').getElementsByTagName('span');
	for (var idx=0;idx<os.length;idx++){
		var tab=os[idx];
		if (!tab.reloadinfo) continue;
		rowsum+=tab.offsetWidth+8; // tab_gap=8
		if (rowsum>rowcap){
			rowcap=idw; // subsequent rows can use full width
			rowsum=0;
			tab.style.clear='left';
		} else {
			tab.style.clear='none';
		}
	}
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
	if (document.currenttab==null) return;
	if (document.currenttab>=document.tabcount) return;

	document.preAfloatQuicklist = document.appsettings.quicklist;
	document.tabafloat = true;
	var tab=document.tabviews[document.currenttab];
	if (tab) tab.afloat=true;

	if (!document.iphone_portrait) {
		// Afloat: hide sidebar, content fills width, show qnav as gutter
		gid('leftview').style.display='none';
		gid('mvptr').style.display='none';
		gid('mainmenu').style.display='none';gid('bookmarkview').style.display='none';
		gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
		gid('tabviews').onscroll=null;
		gid('content').style.marginLeft='0';
		gid('content').style.zIndex='';
		gid('content').style.width=document.appsettings.cw+'px';
		gid('tabviews').style.left='0';gid('tabviews').style.top='0';gid('tabviews').style.width=document.appsettings.cw+'px';gid('tabviews').style.height=document.appsettings.ch+'px';
		var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='0';qns[qi].style.top='0';}
	}

	// Hide tab titles and remove padding
	gid('tabtitles').style.display='none';
	gid('tabviews').style.paddingTop='0';
	var rls=document.querySelectorAll('.reloader[id^="tabreloader_"]');
	for(var ri=0;ri<rls.length;ri++){rls[ri].style.top='';}

	// Update all dock buttons to contract icon
	var dockBtns=document.querySelectorAll('.tabdock_btn');
	for (var i=0;i<dockBtns.length;i++) {
		dockBtns[i].className=dockBtns[i].className.replace(/\s*afloat\s*/g,'')+' afloat';
	}

	// Apply afloat body class for CSS overrides
	document.body.className=document.body.className.replace(/\s*tabafloat_\w+/g,'');
	document.body.className+=(document.iphone_portrait?' tabafloat_portrait':' tabafloat_landscape');

	reposition_bar_views();
	scaleall(document.body);
	setTimeout(function(){update_qnav_padding();},0);
}

function redocktab(){
	if (document.currenttab==null) return;
	if (document.currenttab>=document.tabcount) return;

	document.tabafloat=null;
	var tab=document.tabviews[document.currenttab];
	if (tab) tab.afloat=null;

	// Remove afloat body class
	document.body.className=document.body.className.replace(/\s*tabafloat_\w+/g,'');

	// Restore tab titles (only in landscape) and padding
	if (!document.iphone_portrait) gid('tabtitles').style.display='block';
	if (!document.iphone_portrait) gid('tabviews').style.paddingTop=(26*document.rowcount)+'px';
	else gid('tabviews').style.paddingTop='0';
	var rls=document.querySelectorAll('.reloader[id^="tabreloader_"]');
	for(var ri=0;ri<rls.length;ri++){rls[ri].style.top='';}

	if (!document.iphone_portrait) {
		var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
		var lscape_left=(mobl&&mobl.toolbar_position=='left');
		var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
		// Landscape: restore sidebar
		if (lscape_left&&!lscape_vnp){
			// vnp=false: restore top bar (logo+power) + left panel (menu items)
			gid('leftview').style.display='none';
			gid('mvptr').style.display='none';
			// Check if current tab is dash or non-dash to decide mainmenu vs bookmarkview
			var curTabId=document.currenttab;
			var isDashRedock=(document.tabkeys[curTabId]=='welcome')||(document.tabtitles[curTabId]&&document.tabtitles[curTabId].reloadinfo&&document.tabtitles[curTabId].reloadinfo.opts&&document.tabtitles[curTabId].reloadinfo.opts.tabctx=='dash');
			gid('mainmenu').style.width='180px';gid('mainmenu').style.left='0';gid('mainmenu').style.top='40px';gid('mainmenu').style.height=(ch()-40)+'px';
			var mmi=gid('mainmenuitems');if(mmi) mmi.style.height=(ch()-40)+'px';
			gid('toolbg').style.width='100%';gid('toolbg').style.height='40px';
			gid('toolicons').style.width='100%';gid('toolicons').style.height='40px';
			gid('bookmarkview').style.height=(ch()-40)+'px';
			if (isDashRedock) {
				// Dash tab: show menu, full left panel, tab content at 180px offset
				gid('mainmenu').style.display='block';gid('mainmenu').style.visibility='visible';gid('bookmarkview').style.display='none';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
				gid('content').style.marginLeft='180px';
				gid('content').style.width=(document.appsettings.cw-180)+'px';gid('content').style.zIndex='2000';
				gid('tabtitles').style.top='17px';gid('tabtitles').style.left='180px';gid('tabtitles').style.width=(document.appsettings.cw-180)+'px';gid('tabtitles').style.marginLeft='';
				gid('tabviews').style.left='180px';gid('tabviews').style.width=(document.appsettings.cw-180)+'px';gid('tabviews').style.top='';gid('tabviews').style.height='';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='183px';qns[qi].style.top='';}
			} else {
				// Non-dash tab: hide menu, show bookmarkview, tab content at 180px offset
				gid('mainmenu').style.display='block';gid('mainmenu').style.visibility='hidden';gid('bookmarkview').style.display='block';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'')+' boundless';
				gid('content').style.marginLeft='180px';
				gid('content').style.width=(document.appsettings.cw-180)+'px';gid('content').style.zIndex='2000';
				gid('tabtitles').style.top='17px';gid('tabtitles').style.left='180px';gid('tabtitles').style.width=(document.appsettings.cw-180)+'px';gid('tabtitles').style.marginLeft='';
				gid('tabviews').style.left='180px';gid('tabviews').style.width=(document.appsettings.cw-180)+'px';gid('tabviews').style.top='';gid('tabviews').style.height='';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='183px';qns[qi].style.top='';}
				tab_loadbookmark(document.tabviews[curTabId]);
				gid('tabviews').onscroll=tab_syncbookmarks;
				setTimeout(tab_syncbookmarks,100);
			}
		} else if (lscape_left && lscape_vnp) {
			// vnp=true: restore 40px top bar + collapsible icon sidebar
			gid('mainmenu').style.display='block';gid('mainmenu').style.visibility='visible';
			gid('bookmarkview').style.display='none';
			gid('toolbg').style.width='100%';gid('toolbg').style.height='40px';
			gid('toolicons').style.width='100%';gid('toolicons').style.height='40px';
			gid('mainmenu').style.height=(ch()-40)+'px';
				var vnav_acct=gid('vnav_account');var vnav_h=(vnav_acct?vnav_acct.offsetHeight:0);
				var mmi=gid('mainmenuitems');if(mmi) mmi.style.height=(ch()-40-vnav_h)+'px';
			if (document.appsettings.quicklist) {
				gid('mainmenu').className='';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
				gid('tabviews').style.left='210px';gid('tabviews').style.width=(cw()-210)+'px';
				gid('content').style.marginLeft='210px';gid('content').style.width=(cw()-210)+'px';
				gid('tabtitles').style.left='210px';gid('tabtitles').style.width=(cw()-210)+'px';gid('tabtitles').style.marginLeft='';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='210px';qns[qi].style.top='';}
				if(gid('vnavside_hide'))gid('vnavside_hide').style.display='block';if(gid('vnavside_show'))gid('vnavside_show').style.display='none';
			} else {
				gid('mainmenu').className='collapsed';
				gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
				gid('tabviews').style.left='54px';gid('tabviews').style.width=(cw()-54)+'px';
				gid('content').style.marginLeft='54px';gid('content').style.width=(cw()-54)+'px';
				gid('tabtitles').style.left='54px';gid('tabtitles').style.width=(cw()-54)+'px';gid('tabtitles').style.marginLeft='';
				var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='54px';qns[qi].style.top='';}
				if(gid('vnavside_hide'))gid('vnavside_hide').style.display='none';if(gid('vnavside_show'))gid('vnavside_show').style.display='block';
			}
			gid('content').style.zIndex='2000';
		} else {
			gid('leftview').style.display='block';
			gid('mvptr').style.display='block';
			// Clear inline styles set by undocktab so CSS/setquicklist/scaleall take over
			gid('tabviews').style.left='';
			gid('tabviews').style.top='';
			gid('tabviews').style.width='';
			gid('tabviews').style.height='';
			gid('content').style.zIndex='';
		}
		setquicklist(document.preAfloatQuicklist!=null?document.preAfloatQuicklist:document.appsettings.quicklist,true);
	}

	// Update all dock buttons to expand icon
	var dockBtns=document.querySelectorAll('.tabdock_btn');
	for (var i=0;i<dockBtns.length;i++) {
		dockBtns[i].className=dockBtns[i].className.replace(/\s*afloat/g,'');
	}

	reposition_bar_views();
	scaleall(document.body);
	setTimeout(function(){update_qnav_padding();},0);
}

function toggletabdock(){
	if (document.tabafloat) redocktab(); else undocktab();
}

function update_qnav_padding(){
	var qnavs=document.querySelectorAll('.qnav');
	if (!qnavs.length) return;
	if (document.tabafloat) {
		// In afloat mode, let CSS !important override handle it (35px)
		for(var i=0;i<qnavs.length;i++) qnavs[i].style.paddingTop='';
		return;
	}
	// Find a reference button for the current tab (or any visible tab)
	var refBtn=null;
	if (document.currenttab!=null&&document.currenttab>=0&&document.tabkeys&&document.tabkeys[document.currenttab]) {
		refBtn=document.getElementById('tabdock_btn_'+document.tabkeys[document.currenttab]);
	}
	if (!refBtn||refBtn.getBoundingClientRect().height===0) {
		// Fallback: find any visible reload button
		var allBtns=document.querySelectorAll('.tabreload_btn');
		for(var bi=0;bi<allBtns.length;bi++){
			if (allBtns[bi].getBoundingClientRect().height>0){refBtn=allBtns[bi];break;}
		}
	}
	// Fallback: use rowcount-based formula if no visible button found
	if (!refBtn||refBtn.getBoundingClientRect().height===0) {
		var base=document.iphone_portrait?(document.appsettings.uiconfig&&document.appsettings.uiconfig.mobile&&document.appsettings.uiconfig.mobile.toolbar_position=='bottom'?60:80):75;
		var pt=(base+26*((document.rowcount||1)-1))+'px';
		for(var i=0;i<qnavs.length;i++) qnavs[i].style.paddingTop=pt;
		return;
	}
	// Measure button bottom and qnav container top to align qnav with button bottom
	var btnRect=refBtn.getBoundingClientRect();
	var qnavContainer=qnavs[0].parentElement;
	var containerRect=qnavContainer.getBoundingClientRect();
	var pt=Math.round(btnRect.bottom-containerRect.top);
	if (pt<20) pt=20; // minimum padding
	for(var i=0;i<qnavs.length;i++) qnavs[i].style.paddingTop=pt+'px';
}

// Corner tab (Picture-in-Picture style) functions
// document.cornertabkey - key of the tab currently mounted in the corner
// document.cornerminimized - true if corner tab is minimized to button
// document.corner_on_init - initialization function called when corner session ends (restore/close/button) or button is clicked
// document.corner_closemode - determines corner fate when X is clicked ('resume', 'close', 'button')
// mode: 'remove' - remove any existing corner tab, 'restore' - restore existing corner tab to main tab list

function tab_dock_corner(tabkey, mode) {
	if (!tabkey) return false;
	if (!gid('cornertab')) return false;

	var tabid = gettabid(tabkey);
	if (tabid == -1) return false;

	// Initialize focus tracking on first use
	if (self.corner_focus_init) corner_focus_init();

	var existingKey = null;

	// Handle existing corner tab
	if (document.cornertabkey) {
		existingKey = document.cornertabkey;
		var existingId = gettabid(existingKey);

		if (mode == 'restore' && existingId != -1) {
			// Restore existing corner tab to main tab list by moving DOM nodes
			var cornerContent = gid('cornertab_content');
			var existingTab = document.tabviews[existingId];
			while (cornerContent.firstChild) {
				existingTab.appendChild(cornerContent.firstChild);
			}
			existingTab.style.display = 'none';
			existingTab.cornered = null;
			// Show the tab title again
			document.tabtitles[existingId].style.display = '';
		}
		// else mode == 'remove' or default - just unmount, content stays in tabviews

		document.cornertabkey = null;
	}

	// Don't corner the same tab twice
	if (tabkey == existingKey && mode != 'restore') return false;

	var tab = document.tabviews[tabid];
	var tabTitle = document.tabtitles[tabid];

	// Capture scroll position before moving (proportional to scrollable height)
	// On iPhone, the scroll container varies - check the tab itself
	var scrollRatio = 0;
	if (tab.scrollHeight > tab.clientHeight) {
		scrollRatio = tab.scrollTop / (tab.scrollHeight - tab.clientHeight);
	}

	// Get tab title text - try innerText of the .tt element first
	var ttElem = tabTitle.querySelector('.tt');
	var titleText = ttElem ? ttElem.innerText : (tabTitle.tabname || tabkey);

	// Move tab content to corner container by moving DOM nodes (not innerHTML copy)
	// This preserves event listeners, chart instances, and other JavaScript state
	gid('cornertab_title').innerHTML = titleText;
	var cornerContent = gid('cornertab_content');
		cornerContent.innerHTML = ''; // Clear any existing content
	while (tab.firstChild) {
		cornerContent.appendChild(tab.firstChild);
	}
	tab.cornered = true;

		// Reset scroll position after DOM settles (preserves scroll if source tab was scrolled)
		setTimeout(function() {
			if (scrollRatio > 0) {
				var newScrollable = cornerContent.scrollHeight - cornerContent.clientHeight;
				if (newScrollable > 0) {
					cornerContent.scrollTop = Math.round(scrollRatio * newScrollable);
				}
			} else {
				cornerContent.scrollTop = 0;
			}
		}, 10);

	// Hide the tab title from the tab bar
	tabTitle.style.display = 'none';

	// Switch to another tab if this one was current
	// Use tabseq stack like closetab does for proper tab navigation (iphone uses tabseq instead of tabhistory)
	if (document.currenttab == tabid) {
		var nextTab = null;

		// Try to get the last tab from tabseq stack (like closetab)
		if (document.tabseq) {
			// First nullify this tab in the seq
			for (var i = 0; i < document.tabseq.length; i++) {
				if (document.tabseq[i] == tabkey) document.tabseq[i] = null;
			}
			// Pop from seq until we find a valid non-cornered tab
			while (nextTab == null && document.tabseq.length > 0) {
				var candidate = document.tabseq.pop();
				if (candidate != null) {
					var candidateId = gettabid(candidate);
					if (candidateId != -1 && document.tabviews[candidateId] && !document.tabviews[candidateId].cornered) {
						nextTab = candidate;
					}
				}
			}
		}

		// Fallback: find first non-cornered tab
		if (!nextTab) {
			for (var i = 0; i < document.tabcount; i++) {
				if (i != tabid && document.tabviews[i] && !document.tabviews[i].cornered) {
					nextTab = document.tabkeys[i];
					break;
				}
			}
		}

		if (nextTab) showtab(nextTab);
	}

	// Hide corner button if visible, show corner tab
	if (gid('cornerbutton')) gid('cornerbutton').style.display = 'none';
	document.cornerminimized = false;

	// Show corner container with animation
	var corner = gid('cornertab');
	corner.style.display = 'block';
	corner.style.opacity = '0';
	corner.style.transform = 'scale(0.8)';
	corner.style.transition = 'opacity 200ms, transform 200ms';

	setTimeout(function() {
		corner.style.opacity = '1';
		corner.style.transform = 'scale(1)';
	}, 10);

	document.cornertabkey = tabkey;

	// Resize corner tab
	if (self.resizeall_corner) resizeall_corner();

	return true;
}

function tab_undock_corner(tabkey, force_restore) {
	// If no tabkey provided, undock the current corner tab
	if (!tabkey && document.cornertabkey) tabkey = document.cornertabkey;
	if (!tabkey) return false;
	if (!gid('cornertab')) return false;

	// Only undock if it matches the current corner tab
	if (document.cornertabkey != tabkey) return false;

	// force_restore: if true, restore tab to main tab list (used by toggle hotkey)
	// Otherwise (X button): always close/destroy the tab
	//   - closemode 'button': show corner button after closing
	//   - closemode 'close' or default: hide corner button after closing

	if (force_restore) {
		// Restore tab to main tab list (toggle hotkey behavior)
		var tabid = gettabid(tabkey);
		if (tabid == -1) {
			// Tab was closed while cornered, just hide corner
			gid('cornertab').style.display = 'none';
			if (gid('cornerbutton')) gid('cornerbutton').style.display = 'none';
			var cc=gid('cornertab_content'); cc.scrollTop=0; cc.innerHTML='';
			document.cornertabkey = null;
			document.cornerminimized = false;
			return true;
		}

		var tab = document.tabviews[tabid];
		var tabTitle = document.tabtitles[tabid];

		// Capture scroll position from corner before moving (proportional to scrollable height)
		var cornerContent = gid('cornertab_content');
		var scrollRatio = 0;
		if (cornerContent.scrollHeight > cornerContent.clientHeight) {
			scrollRatio = cornerContent.scrollTop / (cornerContent.scrollHeight - cornerContent.clientHeight);
		}

		// Restore content to main tab by moving DOM nodes (not innerHTML copy)
		// This preserves event listeners, chart instances, and other JavaScript state
		while (cornerContent.firstChild) {
			tab.appendChild(cornerContent.firstChild);
		}
		tab.cornered = null;

		// Show the tab title again
		tabTitle.style.display = '';

		// Hide corner container with animation
		var corner = gid('cornertab');
		corner.style.opacity = '0';
		corner.style.transform = 'scale(0.8)';

		setTimeout(function() {
			corner.style.display = 'none';
			corner.style.transform = 'scale(1)';
		}, 200);

		// Hide corner button too
		if (gid('cornerbutton')) gid('cornerbutton').style.display = 'none';

		document.cornertabkey = null;
		document.cornerminimized = false;

		// Show the tab
		showtab(tabkey);

		// Apply proportional scroll position to restored tab after DOM settles
		if (scrollRatio > 0) {
			setTimeout(function() {
				var newScrollable = tab.scrollHeight - tab.clientHeight;
				if (newScrollable > 0) {
					tab.scrollTop = Math.round(scrollRatio * newScrollable);
				}
			}, 50);
		}

		return true;
	}

	// X button behavior: always close/destroy the tab
	var closemode = document.corner_closemode;

	var corner = gid('cornertab');
		gid('cornertab_content').scrollTop = 0;
	corner.style.opacity = '0';
	corner.style.transform = 'scale(0.8)';
	setTimeout(function() {
		corner.style.display = 'none';
		corner.style.transform = 'scale(1)';
		var cc=gid('cornertab_content'); cc.scrollTop=0; cc.innerHTML='';
	}, 200);

	// Show or hide corner button based on closemode
	if (closemode == 'button' && gid('cornerbutton')) {
		var btn = gid('cornerbutton');
		btn.style.display = 'block';
		btn.style.opacity = '0';
		btn.style.transform = 'scale(0.5)';
		btn.style.transition = 'opacity 200ms, transform 200ms';
		setTimeout(function() {
			btn.style.opacity = '1';
			btn.style.transform = 'scale(1)';
		}, 10);
	} else if (gid('cornerbutton')) {
		gid('cornerbutton').style.display = 'none';
	}

	// Close the actual tab
	var savedTabkey = tabkey;
	document.cornertabkey = null;
	document.cornerminimized = false;
	// Keep corner_closemode and corner_on_init for button mode
	if (closemode != 'button') {
		document.corner_closemode = null;
		document.corner_on_init = null;
	}
	closetab(savedTabkey);

	return true;
}

function toggletabcorner(tabkey) {
	// If no tabkey specified: toggle the corner state
	// - If corner exists, undock it (restore to main tab list)
	// - If no corner, dock the current tab
	if (!tabkey) {
		if (document.cornertabkey) {
			// Corner exists - undock it (force_restore bypasses closemode)
			tab_undock_corner(null, true);
			return;
		}
		// No corner - dock current tab
		if (document.currenttab == null || document.currenttab >= document.tabcount) return;
		tabkey = document.tabkeys[document.currenttab];
	}

	// Check if tab has noclose or nocorner attribute - ignore the request
	var tabid = gettabid(tabkey);
	if (tabid != -1) {
		var tabTitle = document.tabtitles[tabid];
		if (tabTitle && (tabTitle.noclose || tabTitle.nocorner)) return;
	}

	// If we came from the no-tabkey branch, dock current tab
	if (document.cornertabkey != tabkey) {
		tab_dock_corner(tabkey, 'restore');
	} else {
		// Tabkey specified and matches corner: undock (force_restore bypasses closemode)
		tab_undock_corner(tabkey, true);
	}
}

// Minimize corner tab to a small button
function corner_minimize() {
	if (!gid('cornertab') || !gid('cornerbutton')) return;
	if (!document.cornertabkey) return;

	var corner = gid('cornertab');
	var btn = gid('cornerbutton');

	corner.style.opacity = '0';
	corner.style.transform = 'scale(0.5)';

	setTimeout(function() {
		corner.style.display = 'none';
		btn.style.display = 'block';
		btn.style.opacity = '0';
		btn.style.transform = 'scale(0.5)';
		btn.style.transition = 'opacity 200ms, transform 200ms';
		setTimeout(function() {
			btn.style.opacity = '1';
			btn.style.transform = 'scale(1)';
		}, 10);
	}, 150);

	document.cornerminimized = true;
}

// Maximize corner tab from button
function corner_maximize() {
	if (!gid('cornertab') || !gid('cornerbutton')) return;
	if (!document.cornertabkey) return;

	var corner = gid('cornertab');
	var btn = gid('cornerbutton');

	btn.style.opacity = '0';
	btn.style.transform = 'scale(0.5)';

	setTimeout(function() {
		btn.style.display = 'none';
		corner.style.display = 'block';
		corner.style.opacity = '0';
		corner.style.transform = 'scale(0.8)';
		corner.style.transition = 'opacity 200ms, transform 200ms';
		setTimeout(function() {
			corner.style.opacity = '1';
			corner.style.transform = 'scale(1)';
		}, 10);
	}, 150);

	document.cornerminimized = false;

	// Resize corner tab
	if (self.resizeall_corner) resizeall_corner();
}

// Corner button click handler
function corner_button_click() {
	// If corner is minimized, maximize it (restore existing session)
	if (document.cornerminimized && document.cornertabkey) {
		corner_maximize();
		return;
	}
	// No existing session - if there's an on_init handler, use it to start a new corner session
	if (document.corner_on_init) {
		document.corner_on_init();
		return;
	}
}

// Show corner button with optional initialization handler
// icon: optional icon character/HTML for the button
// on_init: initialization function called when:
//   - button is clicked (to start a new corner session)
//   - corner X is clicked and closemode is 'resume', 'close', or 'button'
//   - NOT called when corner is minimized (session continues)
// closemode: determines corner fate when X is clicked:
//   - null/undefined/'resume' (default): restore tab to main tab list, call on_init
//   - 'close': permanently close the tab and corner, call on_init
//   - 'button': minimize to corner button, call on_init, preserve button settings
function show_corner_button(icon, on_init, closemode) {
	if (!gid('cornerbutton')) return;

	var btn = gid('cornerbutton');

	if (icon) {
		btn.innerHTML = icon;
	}

	if (on_init) {
		document.corner_on_init = on_init;
	} else {
		document.corner_on_init = null;
	}

	// Set corner close mode
	document.corner_closemode = closemode || null;

	// Only show button if corner tab is not visible
	if (gid('cornertab').style.display == 'none' || gid('cornertab').style.display == '') {
		btn.style.display = 'block';
		btn.style.opacity = '0';
		btn.style.transform = 'scale(0.8)';
		btn.style.transition = 'opacity 200ms, transform 200ms';
		setTimeout(function() {
			btn.style.opacity = '1';
			btn.style.transform = 'scale(1)';
		}, 10);
	}
}

// Hide corner button
function hide_corner_button() {
	if (!gid('cornerbutton')) return;

	var btn = gid('cornerbutton');
	btn.style.opacity = '0';
	btn.style.transform = 'scale(0.5)';

	setTimeout(function() {
		btn.style.display = 'none';
	}, 150);

	document.corner_on_init = null;
}

// Corner focus tracking
// document.corneractive - true if corner is the active view for scrolling/interaction
// This is used to determine which view to scroll when both corner and main tab are visible

// Initialize corner focus tracking - call once on page load
function corner_focus_init() {
	// Skip if already initialized or no corner element
	if (document.corner_focus_initialized) return;
	if (!gid('cornertab')) return;

	document.corner_focus_initialized = true;
	document.corneractive = false;

	// Use delegated event on document for low overhead
	// Only check for corner focus when corner is actually in use
	document.addEventListener('touchstart', function(e) {
		// Skip if no corner tab is mounted - zero overhead when not in use
		if (!document.cornertabkey) return;
		if (document.cornerminimized) return;

		var corner = gid('cornertab');
		if (!corner) return;

		// Check if touch is inside corner
		var inCorner = corner.contains(e.target);
		corner_setfocus(inCorner);
	}, true); // Use capture phase for early detection

	// Also track focus events for keyboard navigation
	document.addEventListener('focusin', function(e) {
		if (!document.cornertabkey) return;
		if (document.cornerminimized) return;

		var corner = gid('cornertab');
		if (!corner) return;

		var inCorner = corner.contains(e.target);
		corner_setfocus(inCorner);
	}, true);
}

// Set corner as active or inactive, with visual indicator
function corner_setfocus(active) {
	if (document.corneractive == active) return; // No change
	document.corneractive = active;

	var corner = gid('cornertab');
	if (!corner) return;

	// Visual indicator: subtle border/shadow change (inline styles for zero CSS overhead)
	if (active) {
		corner.style.boxShadow = '0 0 0 2px #4a90d9, 0 4px 12px rgba(0,0,0,0.15)';
		corner.style.borderColor = '#4a90d9';
	} else {
		corner.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
		corner.style.borderColor = '#cccccc';
	}
}

// Get the correct scroll container based on where the target element is located
// If targetElement is provided and is inside the corner, return corner content
// If targetElement is provided and is in main tab, return main tab
// If no targetElement, use document.corneractive to decide
function getActiveScrollContainer(targetElement) {
	var cornerContent = gid('cornertab_content');

	// If a target element is specified, use its location to determine container
	if (targetElement && cornerContent) {
		if (cornerContent.contains(targetElement)) {
			return cornerContent;
		}
	}

	// If corner is active and has content, use corner
	if (document.corneractive && document.cornertabkey && cornerContent && !document.cornerminimized) {
		return cornerContent;
	}

	// Default: use main tab view (mobile uses different scroll containers)
	if (!document.iphone_portrait) {
		return gid('tabviews');
	} else {
		return document.body;
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

showtab=function(key,opts){
  var i;
  var pasttab=document.currenttab;
  // Save scroll position before rotate() resets it
  if (!document.iphone_portrait) {
    var _tv=gid('tabviews');
    if (_tv && pasttab!=null && pasttab<document.tabcount && document.tabviews[pasttab]) {
      document.tabviews[pasttab]._savedScrollTop=_tv.scrollTop;
    }
  }
  rotate();
  var tabid=gettabid(key);
  if (tabid==-1) return;

  // Check if the tab is in the corner
  if (document.cornertabkey == key) {
    if (document.cornerminimized) {
      // Corner is minimized, restore it
      corner_maximize();
    } else {
      // Corner is visible, use callout to point to it
      if (self.callout_section && gid('cornertab')) {
        callout_section(gid('cornertab'), true);
      }
    }
    return true;
  }

  // onrotate() removed — rotate() is already called above and would cause scroll flicker
  document.currenttab=tabid;

  document.tabseq.push(key);
  document.viewmode=2;

  var closeBtn=gid('tabclose_btn_'+key);
  if (closeBtn) { var noclose=(opts!=null&&opts.noclose==true)||(opts==null&&tabid!=-1&&document.tabtitles[tabid].reloadinfo&&document.tabtitles[tabid].reloadinfo.opts&&document.tabtitles[tabid].reloadinfo.opts.noclose==true); closeBtn.className=noclose?'tabclose_btn tabclose_btn_hidden':'tabclose_btn'; }
  var dockBtn=gid('tabdock_btn_'+key);
  if (dockBtn) { dockBtn.className='tabdock_btn'+(document.tabafloat?' afloat':''); }
  for (i=0;i<document.tabcount;i++){
	  if (i==tabid) continue;
	  document.tabviews[i].style.display='none';
	  document.tabtitles[i].className='dulltab';
  }	
  document.tabviews[tabid].style.display='block';
  document.tabtitles[tabid].className='activetab';

  // Left-panel management for doc-centric view mode (toolbar_position=left, vnp=false)
  if (!document.tabafloat && !document.iphone_portrait) {
    var mobl=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
    var lscape_left=(mobl&&mobl.toolbar_position=='left');
    var lscape_vnp=(mobl&&mobl.vertical_nav_persist);
    if (lscape_left && !lscape_vnp) {
      var isDashTab = (key=='welcome') || (document.tabtitles[tabid].reloadinfo && document.tabtitles[tabid].reloadinfo.opts && document.tabtitles[tabid].reloadinfo.opts.tabctx=='dash');
      if (isDashTab) {
        // Dash tab: show main menu, hide bookmarkview
        gid('bookmarkview').innerHTML='';
        gid('bookmarkview').bookmarks=null;
        gid('mainmenu').style.visibility='visible';
        gid('bookmarkview').style.display='none';
        gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
        gid('tabviews').onscroll=null;
        // Restore tabviews to left-of-menu positioning
        gid('tabviews').style.left='180px';
        gid('tabviews').style.width=(cw()-180)+'px';
        gid('content').style.marginLeft='180px';
        gid('content').style.width=(cw()-180)+'px';
        gid('content').style.zIndex='2000';
        gid('tabtitles').style.left='180px';
        gid('tabtitles').style.width=(cw()-180)+'px';
      } else {
        // Non-dash tab: hide main menu, show bookmarkview (or blank)
        gid('mainmenu').style.visibility='hidden';
        gid('bookmarkview').style.display='block';
        gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'')+' boundless';
        // Tab content stays at 180px offset (same as dash) — bookmarkview overlays the menu area
        gid('tabviews').style.left='180px';
        gid('tabviews').style.width=(cw()-180)+'px';
        gid('content').style.marginLeft='180px';
        gid('content').style.width=(cw()-180)+'px';
        gid('content').style.zIndex='2000';
        gid('tabtitles').style.left='180px';
        gid('tabtitles').style.width=(cw()-180)+'px';
        tab_loadbookmark(document.tabviews[tabid]);
        // Set scroll sync for bookmark highlighting (scroll container is #tabviews on mobile)
        gid('tabviews').onscroll=tab_syncbookmarks;
        setTimeout(tab_syncbookmarks,100);
        // Size bookmarkview to match mainmenu area
        gid('bookmarkview').style.height=(ch()-40)+'px';
      }
    } else if (lscape_left && lscape_vnp) {
      // vnp=true: 40px top bar + collapsible icon sidebar, mainmenu stays shown, no bookmarkview switching
      gid('mainmenu').style.visibility='visible';
      gid('mainmenu').style.display='block';
      gid('bookmarkview').style.display='none';
      // Highlight active menu item
      var mitems=gid('mainmenuitems');
      if (mitems) {
        var mdivs=mitems.getElementsByTagName('div');
        for (var mi=0;mi<mdivs.length;mi++){
          mdivs[mi].className=mdivs[mi].className.replace(/\s*current/g,'');
        }
      }
      // Content positioning depends on quicklist state
      if (document.appsettings.quicklist) {
        gid('mainmenu').className='';
        gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
        gid('tabviews').style.left='210px';
        gid('tabviews').style.width=(cw()-210)+'px';
        gid('content').style.marginLeft='210px';
        gid('content').style.width=(cw()-210)+'px';
        gid('tabtitles').style.left='210px';
        gid('tabtitles').style.width=(cw()-210)+'px';gid('tabtitles').style.marginLeft='';
	        var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='210px';qns[qi].style.top='';}
        if(gid('vnavside_hide'))gid('vnavside_hide').style.display='block';if(gid('vnavside_show'))gid('vnavside_show').style.display='none';
      } else {
        gid('mainmenu').className='collapsed';
        gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
        gid('tabviews').style.left='54px';
        gid('tabviews').style.width=(cw()-54)+'px';
        gid('content').style.marginLeft='54px';
        gid('content').style.width=(cw()-54)+'px';
        gid('tabtitles').style.left='54px';
        gid('tabtitles').style.width=(cw()-54)+'px';gid('tabtitles').style.marginLeft='';
	        var qns=document.getElementsByClassName('qnav_');for(var qi=0;qi<qns.length;qi++){qns[qi].style.left='54px';qns[qi].style.top='';}
        if(gid('vnavside_hide'))gid('vnavside_hide').style.display='none';if(gid('vnavside_show'))gid('vnavside_show').style.display='block';
      }
      gid('content').style.zIndex='2000';
    }
  }

// Wrap tabs to avoid overlapping toolbar actions in landscape left mode
tab_reflow();

//wrapping
  var t=document.tabtitles[document.tabcount-1];
  var topmargin=0;
  
      document.rowcount=(t.offsetTop-topmargin)/26+1;
      if(document.rowcount<1) document.rowcount=1;
      if (!document.lastrowcount) document.lastrowcount=1;
      if (document.lastrowcount!=document.rowcount) {
        //gid('tabtitles').style.height=30*document.rowcount+'px';
        //gid('tabviews').style.top=80+30*(document.rowcount-1)+'px';
        //gid('tabviews').setAttribute("scale:ch",105+30*(document.rowcount-1));
        gid('tabviews').scalech=105+26*(document.rowcount-1);
        if (!document.iphone_portrait) { gid('tabviews').style.paddingTop=(26*document.rowcount)+'px'; var rl=gid('tabreloader_'+key); if (rl) rl.style.top=''; }
        // Reposition changebar/savebar below tab rows (both orientations)
        var _rc=document.rowcount||1; var _barTop=17+26*_rc+2; var bvs=gid('tabviews').querySelectorAll('.changebar_view,.savebar_view'); for(var bi=0;bi<bvs.length;bi++) bvs[bi].style.top=_barTop+'px';
        
        scaleall(document.body);
        setTimeout(function(){update_qnav_padding();},0);
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

	// Restore scroll position after layout has settled
	if (!document.iphone_portrait) {
		var _sti=tabid;
		setTimeout(function(){
			var tv=gid('tabviews');
			if (tv && document.tabviews[_sti] && document.tabviews[_sti]._savedScrollTop!=null) {
				tv.scrollTop=document.tabviews[_sti]._savedScrollTop;
			}
		},100);
	}

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

	// Check if this tab is cornered
	var isCornered = (document.cornertabkey == key);

	if (document.tabtitles[tabid].conflicted){
	  if (!sconfirm("Are you sure you want to override the edit conflict\n and save your version regardless?")){
		  return;
	  }
	}


	if (document.tabtitles[tabid].tablock) return;
	document.tabtitles[tabid].tablock=1;

  if (opts&&opts.bexit) opts.bexit();

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

 	var slowtimeout=1200;
	if (opts&&opts.slowtimeout) slowtimeout=opts.slowtimeout;

	// Use corner content if tab is cornered, otherwise use tabviews
	var ct = isCornered && gid('cornertab_content') ? gid('cornertab_content') : document.tabviews[tabid];
	ct.slowtimer=setTimeout(function(){
		var first=ct.firstChild;
		if (ct.gswi) return;
		var wi=document.createElement('img'); wi.src='imgs/hourglass.gif'; ct.gswi=wi;
		if (gid('statusc')!=ct) wi.style.margin='10px';
		if (first==null) ct.appendChild(wi); else ct.insertBefore(wi,first);
		ct.style.opacity=0.5; ct.style.filter='alpha(50)'; ct.style.color='#999999';
	},slowtimeout);
  

  

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
  
	  if (rq.status==429){
		  ct.style.opacity=0.5;
		  setTimeout(function(){
			ct.style.opacity=1;
			document.tabtitles[tabid].tablock=null;
			reloadtab(key,title,params,loadfunc,data,opts,gskey);
		  },2000);
		  return;
	  }
	  	  
		if (rq.status==504||rq.status==404){
			if (self.flashsticker) flashsticker('Server error '+rq.status,2);
			document.tabtitles[tabid].tablock=null;
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

		document.tabtitles[tabid].reloadinfo={params:newparams,loadfunc:loadfunc,data:null,opts:document.tabtitles[tabid].reloadinfo?document.tabtitles[tabid].reloadinfo.opts:null};

		document.tabkeys[tabid]=newkey;

		// Update cornertabkey if this tab is cornered
		if (isCornered) {
			document.cornertabkey = newkey;
		}

		if (document.tabseq){
			for (i=0;i<document.tabcount;i++) if (document.tabkeys[i]==key) {console.warn('key collision; new key ignored');newkey=key;}

			for (var i=0;i<document.tabseq.length;i++){
				if (document.tabseq[i]==key) document.tabseq[i]=newkey;
			}
		}
		key=newkey;

	}

	var tabctx=rq.getResponseHeader('tabctx');
	if (tabctx!=null&&tabctx!='') {
		var existingOpts=document.tabtitles[tabid].reloadinfo?document.tabtitles[tabid].reloadinfo.opts:null;
		if (!existingOpts) existingOpts={};
		existingOpts.tabctx=tabctx;
		document.tabtitles[tabid].reloadinfo={params:document.tabtitles[tabid].reloadinfo?document.tabtitles[tabid].reloadinfo.params:params,loadfunc:loadfunc,data:null,opts:existingOpts};
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

    if (title) {
		document.tabtitles[tabid].innerHTML=tabhtml;
		// Update corner tab title if this tab is cornered
		if (isCornered && gid('cornertab_title')) {
			gid('cornertab_title').innerHTML = title.replace(/(<([^>]+)>)/gi,'');
		}
	}

	// Load content into corner or main tab view
  	var nocloseClass2=(opts&&opts.noclose)||(opts==null&&tabid!=-1&&document.tabtitles[tabid].reloadinfo&&document.tabtitles[tabid].reloadinfo.opts&&document.tabtitles[tabid].reloadinfo.opts.noclose)?' tabclose_btn_hidden':'';
  	var reloader="<div class=\"reloader\" id=\"tabreloader_"+key+"\"><a class=\"tabclose_btn"+nocloseClass2+"\" id=\"tabclose_btn_"+key+"\" onclick=\"sconfirm(document.dict['confirm_close_tab'])?closetab('"+key+"'):void(0);\"></a><a class=\"tabdock_btn"+(document.tabafloat?' afloat':'')+"\" id=\"tabdock_btn_"+key+"\" onclick=\"toggletabdock();\"></a><a class=\"tabreload_btn\" onclick=\"refreshtab('"+key+"');\"></a></div>";
	ct.innerHTML=reloader+rq.responseText+'<div class="tab-scroll-pad"></div>';
	if (opts&&opts.evaljs) eval_tab_script(ct,rq,loadfunc);
	if (loadfunc!=null) loadfunc(rq);
	if (opts&&opts.bookmark) gototabbookmark(opts.bookmark);
	// After content loads, update left panel for doc-centric view mode
	var mobl4=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
	if (mobl4&&mobl4.toolbar_position=='left'&&!mobl4.vertical_nav_persist&&!document.iphone_portrait&&!document.tabafloat) {
		if (tabctx=='dash'||key=='welcome') {
			gid('mainmenu').style.visibility='visible';
			gid('bookmarkview').style.display='none';
			gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'');
			gid('tabviews').onscroll=null;
		} else {
			gid('mainmenu').style.visibility='hidden';
			gid('bookmarkview').style.display='block';
			gid('bookmarkview').style.height=(ch()-40)+'px';
			gid('tabviews').className=gid('tabviews').className.replace(/\s*boundless\s*/g,'')+' boundless';
			tab_loadbookmark(document.tabviews[tabid]);
			gid('tabviews').onscroll=tab_syncbookmarks;
			setTimeout(tab_syncbookmarks,100);
		}
	} else if (mobl4&&mobl4.toolbar_position=='left'&&mobl4.vertical_nav_persist&&!document.iphone_portrait&&!document.tabafloat) {
		// vnp=true: mainmenu always visible, no bookmarkview switching
		gid('mainmenu').style.visibility='visible';
		gid('mainmenu').style.display='block';
		gid('bookmarkview').style.display='none';
	}
	scaleall(document.body);
		setTimeout(function(){update_qnav_padding();},0);
	}
  }
  rq.send(data);
}

function addtab(key,title,params,loadfunc,data,opts){
  document.viewmode=2;
  var i;
  if (document.tablock!=null) return;
  document.tablock=true;

  // Check if the tab is already in the corner
  if (document.cornertabkey == key) {
    if (document.cornerminimized) {
      // Corner is minimized, restore it
      corner_maximize();
    } else {
      // Corner is visible, use callout to point to it
      if (self.callout_section && gid('cornertab')) {
        callout_section(gid('cornertab'), true);
      }
    }
    document.tablock=null;
    return;
  }

  for (i=0;i<document.tabcount;i++) {
	if (document.tabkeys[i]==key) {
        showtab(key,opts);
        document.tablock=null;
		if (!document.appsettings.uiconfig.mobile.singletab) return;
	} else {
		if (document.appsettings.uiconfig.mobile.singletab)
			if (document.tabkeys[i]!='welcome') closetab(document.tabkeys[i]);
	}
  }
  
  var c=document.createElement('div');
  c.style.display='none';

  // Set boundless class for doc-centric view mode
  var mobl2=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
  if (mobl2&&mobl2.toolbar_position=='left'&&!mobl2.vertical_nav_persist) {
    gid('tabviews').className='boundless bgflash';
    setTimeout(function(){gid('tabviews').className='boundless bgready'},250);
  } else {
    gid('tabviews').className='bgflash';
    setTimeout(function(){gid('tabviews').className='bgready'},250);
  }
  
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
      
      
	  if (rq.status==401||(xtatus|0)==401){
		  ajxjs(self.showgssubscription,'gssubscriptions.js');
		  showgssubscription();
	      return;
	  }            
	  
	  if (rq.status==429){
		  c.style.opacity=0.4;
		  setTimeout(function(){
			c.style.opacity=1;
			document.tablock=null;
			reloadtab(key,title,params,loadfunc,data,opts);
		  },2000);
		  return;
	  }
	  
		if (rq.status==504||rq.status==404){
			
			if (self.flashsticker) flashsticker('Server error '+rq.status,2);
			document.tablock=null;
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
      	  
	  var nocloseClass=(opts&&opts.noclose)?' tabclose_btn_hidden':'';
	  var reloader="<div class=\"reloader\" id=\"tabreloader_"+key+"\"><a class=\"tabclose_btn"+nocloseClass+"\" id=\"tabclose_btn_"+key+"\" onclick=\"sconfirm(document.dict['confirm_close_tab'])?closetab('"+key+"'):void(0);\"></a><a class=\"tabdock_btn"+(document.tabafloat?' afloat':'')+"\" id=\"tabdock_btn_"+key+"\" onclick=\"toggletabdock();\"></a><a class=\"tabreload_btn\" onclick=\"refreshtab('"+key+"');\"></a></div>";
      c.innerHTML=reloader+rq.responseText+'<div class="tab-scroll-pad"></div>';
      if (opts&&opts.evaljs) eval_tab_script(c,rq,loadfunc);

      // After content loads, populate bookmarkview for doc-centric view mode
      var mobl3=document.appsettings.uiconfig?document.appsettings.uiconfig.mobile_landscape:null;
      if (mobl3&&mobl3.toolbar_position=='left'&&!mobl3.vertical_nav_persist&&!document.iphone_portrait&&!document.tabafloat) {
        var tabid3=gettabid(key);
        if (tabid3>=0 && key!='welcome' && !(document.tabtitles[tabid3].reloadinfo&&document.tabtitles[tabid3].reloadinfo.opts&&document.tabtitles[tabid3].reloadinfo.opts.tabctx=='dash')) {
          tab_loadbookmark(document.tabviews[tabid3]);
        }
      } else if (mobl3&&mobl3.toolbar_position=='left'&&mobl3.vertical_nav_persist&&!document.iphone_portrait&&!document.tabafloat) {
        // vnp=true: mainmenu always visible, no bookmarkview loading
        gid('mainmenu').style.visibility='visible';
        gid('mainmenu').style.display='block';
        gid('bookmarkview').style.display='none';
      }

      document.tablock=null;
      if (loadfunc!=null) loadfunc(rq);
      if (opts&&opts.bookmark) gototabbookmark(opts.bookmark,true);
      setTimeout(function(){update_qnav_padding();},0);
    }
  }
  rq.send(data);
}

eval_tab_script=function(c,rq,func){
	//legacy script tag
	var scripts=c.getElementsByTagName('script');
	for (var i=0;i<scripts.length;i++) eval(scripts[i].innerHTML);
	
	//server response header
	var flag=rq.getResponseHeader('listviewflag');
	var js=rq.getResponseHeader('listviewjs');
	if (flag!=null&&js!=null&&js!=''){
		ajxjs2(flag,js,function(){
			if (func!=null) func();					
		});
	}
					
}

closetab=function(key){
  var tabid=gettabid(key);
  if (tabid==-1) return;

  // If this tab is cornered, undock it first
  if (document.cornertabkey == key) {
    gid('cornertab').style.display = 'none';
    var cc=gid('cornertab_content'); cc.scrollTop=0; cc.innerHTML='';
    document.cornertabkey = null;
  }

  if (document.tabtitles[tabid].autosaver) {clearTimeout(document.tabtitles[tabid].autosaver);document.tabtitles[tabid].autosavertimer=null;}
  if (document.tabtitles[tabid].reloadinfo&&document.tabtitles[tabid].reloadinfo.opts&&document.tabtitles[tabid].reloadinfo.opts.bexit)
	document.tabtitles[tabid].reloadinfo.opts.bexit();

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
			},100);
			
			return;	
		}
	},40);
	
}

function gototabbookmark(id,callout){
	if (!gid(id)) return;

	var d,delta;

	// Check if target is inside the corner tab content
	var cornerContent = gid('cornertab_content');
	if (cornerContent && cornerContent.contains(gid(id))) {
		d = cornerContent;
		delta = 30;
	} else if (!document.iphone_portrait){
		if (!gid('tabviews')) return;
		d=gid('tabviews'); delta=90;
	} else {
		d=gid('tabviews'); delta=30;
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
	    },100);
	}
}

function pullupeditor(d){
	// Check if element is inside corner
	var cornerContent = gid('cornertab_content');
	if (cornerContent && cornerContent.contains(d)) {
		cornerContent.scrollTop = d.parentNode.parentNode.offsetTop - 30;
		return;
	}

	if (!document.iphone_portrait){
		if (!gid('tabviews')) return;
		gid('tabviews').scrollTop=d.parentNode.parentNode.offsetTop-120;
	} else {
		gid('tabviews').scrollTop=d.parentNode.parentNode.offsetTop-30;
	}
}

function marktabchanged(tabkey,hide){
	var mode='block';
	if (hide) mode='none';
	if (gid('changebar_'+tabkey)) gid('changebar_'+tabkey).style.display=mode;
	else return;
	reposition_bar_views();
	
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
	reposition_bar_views();
	tab.timer=setTimeout(function(){
		tab.style.display='none';
	},1200);	
}
function tab_loadbookmark(d){
	if (d==null) return;
	var bv=gid('bookmarkview');
	if (!bv) return;
	// Check if this tab's bookmarks are already cached
	if (d._bookmarkhtml) {
		bv.innerHTML=d._bookmarkhtml;
		bv.style.display='block';
		// Re-attach onclick handlers and rebuild bookmarks array from cached DOM
		var rawitems=bv.getElementsByClassName('qnavitem');
		var items=[];
		var pattern=/gototabbookmark\((\S+?)\)/g;
		for (var i=0;i<rawitems.length;i++){
			var item=rawitems[i];
			var target='';
			while(matches=pattern.exec(item.onclick+'')) target=matches[1].replace(/'/g,'');
			if (target!='') items.push({ref:item,target:target});
		}
		bv.bookmarks=items;
		for (var i=0;i<items.length;i++){
			(function(oc){
				items[i].ref.onclick=function(){document.bookmarklock=true;if(oc)oc();};
			})(items[i].ref.onclick);
		}
		return;
	}
	var qnavs=d.getElementsByClassName('qnav_');
	if (qnavs.length>0){
		bv.innerHTML=qnavs[0].innerHTML;
		bv.style.display='block';
		var pattern=/gototabbookmark\((\S+?)\)/g;
		var rawitems=bv.getElementsByClassName('qnavitem');
		var items=[];
		for (var i=0;i<rawitems.length;i++){
			var item=rawitems[i];
			var target='';
			while(matches=pattern.exec(item.onclick+'')) target=matches[1].replace(/'/g,'');
			if (target!='') items.push({ref:item,target:target});
		}
		// sort by target position in the tab content
		items.sort(function(a,b){
			var oa=gid(a.target);
			var ob=gid(b.target);
			var va=oa?oa.offsetTop:0;
			var vb=ob?ob.offsetTop:0;
			if (va==vb) return 0; if (va<vb) return -1; else return 1;
		});
		bv.bookmarks=items;
		for (var i=0;i<items.length;i++){
			items[i].ref.parentNode.appendChild(items[i].ref.parentNode.removeChild(items[i].ref));
			// Set bookmarklock on click so scroll sync doesn't override during animation
			(function(oc){
				items[i].ref.onclick=function(){document.bookmarklock=true;if(oc)oc();};
			})(items[i].ref.onclick);
		}
		// Cache the bookmark HTML on the tab element for reuse
		d._bookmarkhtml=bv.innerHTML;
	} else {
		bv.bookmarks=null;
		bv.innerHTML='';
	}
}

function tab_syncbookmarks(){
	if (document.bookmarklock){
		document.bookmarklock=null;
		return;
	}
	var bv=gid('bookmarkview');
	if (!bv) return;
	var bookmarks=bv.bookmarks;
	if (bookmarks==null) return;
	if (bookmarks.length==0) return;

	// On mobile landscape, scrolling happens on #tabviews, not individual tab divs
	var top=gid('tabviews').scrollTop;

	var cur=null;
	var last=null;

	for (var i=0;i<bookmarks.length;i++){
		var k=bookmarks[i].ref;
		var mark=gid(bookmarks[i].target);
		if (!mark) continue;
		if (k.offsetHeight<5) continue;
		if (mark.offsetHeight<5) continue;
		if (top<mark.offsetTop) {cur=k;break;}
		last=k;
	}

	if (!cur) cur=last;

	for (var i=0;i<bookmarks.length;i++){
		if (cur==bookmarks[i].ref) cur.className='qnavitem infocus';
		else bookmarks[i].ref.className='qnavitem';
	}
}
