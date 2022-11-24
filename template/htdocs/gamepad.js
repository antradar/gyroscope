//hold a face button on the controller during page refresh

gamepad_register=function(){
	if (navigator.getGamepads==null) return;

	document.gamepad=null;
	
	var bmap={
		topleft:4,topright:5,
		listup:12,listdown:13,
		tableft:14,tabright:15,
		contleft:6,contright:7,
		a:0,b:1,start:9
	}
	
	var amap={
		list:1,view:3,
		vhat:-1,hhat:-1,
		contleft:-1,contright:-1	
	}
	
	if (!document.gamepadlock){
		var gamepads=navigator.getGamepads();
		for (var i=0;i<gamepads.length;i++){//find the first available gamepad as the primary
			var gamepad=gamepads[i];
			if (gamepad!=null) document.gamepad=gamepad;
		}
	}
	
	if (!document.gamepad) {
		if (gid('gamepadicon')) gid('gamepadicon').className='';
		return;
	} else {
		if (gid('gamepadicon')) gid('gamepadicon').className='spotactive';
	}
	if (!document.gamepad.buttons) return;
	if (!document.gamepad.axes) return;
	
	var buttons=document.gamepad.buttons;
	var axes=[0,0,0,0,0,0,0,0];
	
	var hasaxes=0;
	for (var i=0;i<document.gamepad.axes.length;i++){
		if (Math.abs(document.gamepad.axes[i])>0.2){
			hasaxes=1;
			axes[i]=document.gamepad.axes[i];	
		}
	}
		
	if (document.gamepad.buttons.length==11&&document.gamepad.axes.length==8){
		bmap={
			topleft:4,topright:5,
			listup:-1,listdown:-1,
			tableft:-1,tabright:-1,
			contleft:-1,contright:-1,
			a:0,b:1,start:7
		}		
		amap={
			list:1,view:4,
			vhat:7, hhat:6, 
			contleft:2, contright:5	
		}			
	}
	
	var spot=gid('gamepadspot');
	if ((buttons[bmap.topleft]!=null&&buttons[bmap.topleft].pressed)||(buttons[bmap.topright]!=null&&buttons[bmap.topright].pressed)){
		var elems=gid('topicons').getElementsByTagName('a');
		if (document.appsettings.uiconfig.toolbar_position=='left') {
			showtab('welcome');
			elems=gid('mainmenu').getElementsByTagName('a');
		}
		if (spot&&elems.length>0){
			if (spot.timeout) clearTimeout(spot.timeout);
			if (spot.idx==null) spot.idx=0;
			else {
				if (buttons[bmap.topleft].pressed) spot.idx--;
				if (buttons[bmap.topright].pressed) spot.idx++;	
			}
			if (spot.idx>elems.length-1) spot.idx=elems.length-1;
			if (spot.idx<0) spot.idx=0;
			
			//console.log(spot.idx);
			
			var elem=elems[spot.idx];
			if (elem!=null){
				var left=parseInt(gid('topicons').style.left.replace('px',''),0);
				if (elem.offsetLeft+left+elem.offsetWidth>gid('iconbelt').offsetWidth) beltnext();
				if (elem.offsetLeft+left<0) beltprev();
				var rect=elem.getBoundingClientRect();
				spot.mode='topicons';
				spot.style.left=rect.x+'px';
				spot.style.top=rect.y+'px';
				spot.style.width=rect.width+'px';
				spot.style.height=rect.height+'px';
				
				spot.style.display='block';
				spot.active=1;
				spot.timeout=setTimeout(function(){
					spot.style.display='none';
					spot.active=null;	
				},2000);
			}	
		}	
	}
	
	var vcond=document.viewindex!=null&&document.viewindex!=''&&gid('lv'+document.viewindex);
	var vview=gid('lv'+document.viewindex);
	if (spot.lookupview){
		var vcond=gid('lkvc')!=null;
		var vview=gid('lkvc');	
	}
	if (document.appsettings.uiconfig.toolbar_position=='left'){
		if (gid('bookmarkview').offsetWidth>0) {
			vview=gid('bookmarkview');
		} else vview=gid('mainmenu');
		vcond=true;
	}
		
	if (vcond&&(
		(axes[amap.vhat]!=null&&Math.abs(axes[amap.vhat])>=0.8)
		||
		(buttons[bmap.listup]!=null&&buttons[bmap.listup].pressed)
		||
		(buttons[bmap.listdown]!=null&&buttons[bmap.listdown].pressed)
		)){
		var velems=[];
		var os=vview.getElementsByTagName('a');
		for (var i=0;i<os.length;i++){
			var o=os[i]; if (o.attributes&&o.attributes.onclick!=''&&o.offsetWidth>10) velems.push(o);	
		}
		if (spot&&velems.length>0){
			if (spot.timeout) clearTimeout(spot.timeout);
			if (spot.vidx==null) spot.vidx=0;
			else {
				if (buttons[bmap.listup]!=null&&buttons[bmap.listup].pressed) spot.vidx--;
				if (buttons[bmap.listdown]!=null&&buttons[bmap.listdown].pressed) spot.vidx++;	
				
				if (axes[amap.vhat]!=null&&axes[amap.vhat]<-0.8) spot.vidx--;
				if (axes[amap.vhat]!=null&&axes[amap.vhat]>0.8) spot.vidx++;
				
			}
			if (spot.vidx>velems.length-1) spot.vidx=velems.length-1;
			if (spot.vidx<0) spot.vidx=0;
			
			//console.log(spot.idx);
			
			var elem=velems[spot.vidx];
			if (elem!=null){
				vview.scrollTop=elem.offsetTop-50;
				
				var rect=elem.getBoundingClientRect();
				spot.mode='list';
				spot.style.left=rect.x+'px';
				spot.style.top=rect.y+'px';
				spot.style.width=rect.width+'px';
				spot.style.height=rect.height+'px';
				
				spot.style.display='block';
				spot.active=1;
								
				spot.timeout=setTimeout(function(){
					spot.style.display='none';
					spot.active=null;	
				},2000);
			}	
		}	
	}
	
	if (document.tabcount!=null&&document.tabcount>0&&(
		(axes[amap.hhat]!=null&&Math.abs(axes[amap.hhat])>=0.8)
		||
		(buttons[bmap.tableft]!=null&&buttons[bmap.tableft].pressed)
		||
		(buttons[bmap.tabright]!=null&&buttons[bmap.tabright].pressed)
		)){
		if (spot){
			if (spot.timeout) clearTimeout(spot.timeout);
			if (spot.hidx==null) spot.hidx=0;
			else {
				if (buttons[bmap.tableft]!=null&&buttons[bmap.tableft].pressed) spot.hidx--;
				if (buttons[bmap.tabright]!=null&&buttons[bmap.tabright].pressed) spot.hidx++;	
				
				if (axes[amap.hhat]!=null&&axes[amap.hhat]<-0.8) spot.hidx--;
				if (axes[amap.hhat]!=null&&axes[amap.hhat]>0.8) spot.hidx++;
								
			}
			if (spot.hidx>document.tabcount-1) spot.hidx=document.tabcount-1;
			if (spot.hidx<0) spot.hidx=0;
			
			//console.log(spot.hidx);
			
			var elem=document.tabtitles[spot.hidx];
			if (elem!=null){
				var rect=elem.getBoundingClientRect();
				spot.mode='tab';
				spot.style.left=rect.x+'px';
				spot.style.top=rect.y+'px';
				spot.style.width=rect.width+'px';
				spot.style.height=rect.height+'px';
				
				spot.style.display='block';
				spot.active=1;
				spot.timeout=setTimeout(function(){
					spot.style.display='none';
					spot.active=null;	
				},2000);
			}	
		}	
	}
	
	var wcond=document.currenttab!=null&&document.tabviews[document.currenttab]!=null;
	var wview=document.tabviews[document.currenttab]
	if (document.fsshowing) {
		wcond=gid('fsview')!=null;
		wview=gid('fsview');
	}
	if (wcond&&(
		(axes[amap.contleft]!=null&&axes[amap.contleft]>0.9)
		||
		(axes[amap.contright]!=null&&axes[amap.contright]>0.9)
		||
		(buttons[bmap.contleft]!=null&&buttons[bmap.contleft].pressed&&buttons[bmap.contleft].value>0.8)
		||
		(buttons[bmap.contright]!=null&&buttons[bmap.contright].pressed&&buttons[bmap.contright].value>0.8)
	)){
		var welems=[];
		var os=wview.getElementsByTagName('*');
		for (var i=0;i<os.length;i++){
			var o=os[i]; if ((o.tagName=='BUTTON'||o.tagName=='A'||(o.tagName=='INPUT'&&o.type=='checkbox'))&&o.attributes&&o.attributes.onclick!=''&&o.offsetWidth>10) welems.push(o);	
		}
		if (spot&&welems.length>0){
			if (spot.timeout) clearTimeout(spot.timeout);
			if (spot.widx==null) spot.widx=0;
			else {
				if (buttons[bmap.contleft]!=null&&buttons[bmap.contleft].pressed) spot.widx--;
				if (buttons[bmap.contright]!=null&&buttons[bmap.contright].pressed) spot.widx++;	
				if (axes[amap.contleft]!=null&&axes[amap.contleft]>0.9) spot.widx--;
				if (axes[amap.contright]!=null&&axes[amap.contright]>0.9) spot.widx++;
								
			}
			if (spot.widx>welems.length-1) spot.widx=welems.length-1;
			if (spot.widx<0) spot.widx=0;
			
			//console.log(spot.idx);
			
			var elem=welems[spot.widx];
			if (elem!=null){
				var st=0;
				var p=elem;
				while (p.offsetParent!=null){st+=p.offsetTop;p=p.offsetParent;}
				
				wview.scrollTop=st-180;
				//console.log(elem,st);
				var rect=elem.getBoundingClientRect();
				spot.mode='content';
				spot.style.left=rect.x+'px';
				spot.style.top=rect.y+'px';
				spot.style.width=rect.width+'px';
				spot.style.height=rect.height+'px';
				
				spot.style.display='block';
				spot.active=1;
								
				spot.timeout=setTimeout(function(){
					spot.style.display='none';
					spot.active=null;	
				},2000);
			}	
		}	
	}			
	
	if (buttons[bmap.a]!=null&&buttons[bmap.a].pressed){ //A
	
		var vcond=document.viewindex!=null&&document.viewindex!=''&&gid('lv'+document.viewindex);
		var vview=gid('lv'+document.viewindex);
		if (spot.lookupview){
			var vcond=gid('lkvc')!=null;
			var vview=gid('lkvc');	
		}
		if (document.appsettings.uiconfig.toolbar_position=='left'){
			vcond=true;
			if (gid('bookmarkview').offsetWidth>0) vview=gid('bookmarkview');
			else vview=gid('mainmenu');	
		}
	
		if (document.gamebutton_a_timer) clearTimeout(document.gamebutton_a_timer);
		document.gamebutton_a_timer=setTimeout(function(){
			if (spot&&spot.active){
				switch (spot.mode){
				case 'topicons':
					var elems=gid('topicons').getElementsByTagName('a');
					if (document.appsettings.uiconfig.toolbar_position=='left') elems=gid('mainmenu').getElementsByTagName('a');
					var elem=null;
					if (spot.idx!=null&&spot.idx>=0&&spot.idx<elems.length) elem=elems[spot.idx];
					if (elem){
						var event=document.createEvent('Events');
						event.initEvent('click',true,false);
						elem.dispatchEvent(event);
						
					}
				break;
				case 'list':
					if (vcond){
						var velems=[];
						var os=vview.getElementsByTagName('a');
						for (var i=0;i<os.length;i++){
							var o=os[i]; if (o.attributes&&o.attributes.onclick!=''&&o.offsetWidth>10) velems.push(o);	
						}						
						
						var elem=null;
						if (spot.vidx!=null&&spot.vidx>=0&&spot.vidx<velems.length) elem=velems[spot.vidx];
						if (elem){
							var event=document.createEvent('Events');
							event.initEvent('click',true,false);
							elem.dispatchEvent(event);
							
						}
					}
				break;
				case 'tab':
					if (document.tabcount!=null&&document.tabcount>0){
						
						var elem=null;
						var elems=null;
						if (spot.hidx!=null&&spot.hidx>=0&&spot.hidx<document.tabcount) elems=document.tabtitles[spot.hidx].getElementsByTagName('a');
						if (elems!=null&&elems.length>0) elem=elems[0];
						if (elem){
							var event=document.createEvent('Events');
							event.initEvent('click',true,false);
							elem.dispatchEvent(event);
							
						}
					}
				break;	
				case 'content':
					var wcond=document.currenttab!=null&&document.tabviews[document.currenttab]!=null;
					var wview=document.tabviews[document.currenttab];
					if (document.fsshowing){
						wcond=gid('fsview')!=null;
						wview=gid('fsview');	
					}
					if (wcond){
						var welems=[];
						var os=wview.getElementsByTagName('*');
						for (var i=0;i<os.length;i++){
							var o=os[i]; if ((o.tagName=='BUTTON'||o.tagName=='A'||(o.tagName=='INPUT'&&o.type=='checkbox'))&&o.attributes&&o.attributes.onclick!=''&&o.offsetWidth>10) welems.push(o);	
						}						
						var elem=null;
						if (spot.widx!=null&&spot.widx>=0&&spot.widx<welems.length) elem=welems[spot.widx];
						if (elem){
							if (elem.tagName=='INPUT'&&elem.type=='checkbox'){
								if (elem.checked=!elem.checked);	
							}
							var event=document.createEvent('Events');
							event.initEvent('click',true,false);
							elem.dispatchEvent(event);
							
						}
						
					}
				break;			
				}//switch spot mode
			}	
		},100);	
	}
	
	if (buttons[bmap.b]!=null&&buttons[bmap.b].pressed){//B
		if (document.gamebutton_b_timer) clearTimeout(document.gamebutton_b_timer);
		document.gamebutton_b_timer=setTimeout(function(){
			var bypass=0;
			if (document.fsshowing) {bypass=1;closefs();}
			
			if (!bypass&&spot&&spot.active){
				if (spot.lookupview) {bypass=1;hidelookup();}
				if (!bypass){
					switch (spot.mode){
					case 'tab':
						if (document.tabcount!=null&&document.tabcount>0){
							
							var elem=null;
							var elems=null;
							if (spot.hidx!=null&&spot.hidx>=0&&spot.hidx<document.tabcount) elems=document.tabtitles[spot.hidx].getElementsByTagName('a');
							if (elems!=null&&elems.length>1) elem=elems[1];
							if (elem){
								var event=document.createEvent('Events');
								event.initEvent('click',true,false);
								elem.dispatchEvent(event);
								
							}
						}
					break;				
					}//switch spot mode
				}
			}	
		},100);	
	}
	
	if (buttons[bmap.start]!=null&&buttons[bmap.start].pressed){
		if (document.gamebutton_9_timer) clearTimeout(document.gamebutton_9_timer);
		document.gamebutton_9_timer=setTimeout(function(){
			ajxjs(self.setaccountpass,'accounts.js');reloadtab('account','My Account','showaccount');addtab('account','My Account','showaccount');	
		},100);
	}	
	
	
	

	
	if (!hasaxes) return;
	
	//console.log(axes);
	
	if (axes[amap.list]!=null&&axes[amap.list]){//scroll the left view
		if (document.viewindex!=null&&document.viewindex!=''&&gid('lv'+document.viewindex)){
			gid('lv'+document.viewindex).scrollTop+=axes[amap.list]*50;	
		}
		if (document.appsettings.uiconfig.toolbar_position=='left'){
			if (gid('bookmarkview').offsetWidth>0) gid('bookmarkview').scrollTop+=axes[amap.list]*50;
			else gid('mainmenu').scrollTop+=axes[amap.list]*50;
		}
	
	}
	
	if (axes[amap.view]!=null&&axes[amap.view]){//scroll the main view
		var wcond=document.currenttab!=null&&document.tabviews[document.currenttab]!=null;
		var wview=document.tabviews[document.currenttab];
		if (document.fsshowing){
			wcond=gid('fsview')!=null;
			wview=gid('fsview');	
		}
		if (wcond){
			wview.scrollTop+=axes[amap.view]*50;	
		}
	}
	
}

if (navigator.getGamepads!=null) {
	document.gamepaditr=setInterval(gamepad_register,100);
	if (gid('gamepadicon')) gid('gamepadicon').style.display='inline';
	
	window.onblur=function(){document.gamepadlock=true;document.keyboard=[];} //todo: merge with barcode scanner
	window.onfocus=function(){document.gamepadlock=null;}
	
}
