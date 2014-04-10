document.keystates={ctrl:false,alt:false,shift:false}; //alt-18; shift-16; ctrl-17
document.onkeydown=function(e){
	var keycode;
	if (e) keycode=e.which; else keycode=event.keyCode;
	switch(keycode){
		case 18:document.keystates.alt=true;break;
		case 16:document.keystates.shift=true;break;
		case 17:document.keystates.ctrl=true;break;
			
	}
}
document.onkeyup=function(e){
	var keycode;
	if (e) keycode=e.which; else keycode=event.keyCode;
	switch(keycode){
		case 18:document.keystates.alt=false;break;
		case 16:document.keystates.shift=false;break;
		case 17:document.keystates.ctrl=false;break;
		case 71: //G
			if (document.keystates.shift&&document.keystates.alt){
				focus_left(); return false;	
			}
		break;
		case 74: //J
			if (document.keystates.shift&&document.keystates.alt){
				focus_right();	return false;
			}
		break;
		case 75: //K
			if (document.keystates.shift&&document.keystates.alt){
				if (document.hotspot) document.hotspot.focus();	
			}
		break;
		case 89: //Y
			if (document.keystates.shift&&document.keystates.alt){
				focus_top(); return false;	
			}
		break;
	}
}

function focus_left(){
	if (gid('lvtab_'+document.viewindex)) gid('lvtab_'+document.viewindex).focus();
}

function focus_right(){
	var tabkey=document.tabkeys[currenttab];
	gid('rightview_'+tabkey).focus();
}

function focus_top(){
	gid('anchor_top').focus();
}
