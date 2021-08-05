wss_init=function(userid,wsuri,wsskey,gsid){
if (window.WebSocket){
	if (document.wsskey) wsskey=document.wsskey;
	document.websocket=new WebSocket(wsuri+'?WSS'+wsskey+'=');	
	
	document.websocket.onopen=function(e){
		console.log('web socket connected');	
		document.websocket.send('{"type":"getsid","userid":'+userid+',"auth":"'+wsskey+'","gsid":'+gsid+'}');
		document.wssready=true;
		if (!document.nomoresocket) document.nomoresocket=0;
		if (gid('wsswarn')) gid('wsswarn').style.display='none';
	}
	
	document.websocket.onmessage=function(e){
		var msg = JSON.parse(e.data); //PHP sends JSON
		if (msg.type=='getsid'){
			if (!document.wssid&&msg.userid==userid) {
				document.wssid=msg.sid;
				document.gsid=msg.gsid;
				document.nomoresocket=0;
				console.log('sid: '+msg.sid);
			}
			return;	
		}
		
		if (!document.wssid) return;
				
		if (msg.gsid!=gsid&&msg.gsid!=0) return;
		
		if (document.wssid==msg.sid){
			console.log('ignore self');
			wss_markchanges(msg.rectype,msg.recid,1,0,msg);
			return;	
		}
		
		if (msg.type=='update'){
			wss_markchanges(msg.rectype,msg.recid,0,msg);
			return;	
		}
		
	}
	
	document.websocket.onerror=function(e){
		if (!document.nomoresocket) document.nomoresocket=0;
		document.nomoresocket++;
		console.log('web socket connection error');	
	}
	
	document.websocket.onclose=function(e){
		if (!document.nomoresocket) document.nomoresocket=0;
		document.nomoresocket++;
		
		document.wssready=null;
		if (gid('wsswarn')) gid('wsswarn').style.display='inline';
		if (document.nomoresocket&&document.nomoresocket>5) {
			console.log('no more reconnection');
			
			return;
		}
		var span=1500; //adjust variability if needed
		var rest=Math.round(Math.random()*span)+2000; //2-3.5 secs

		console.log('web socket closed, restarting in '+rest+'ms. reconnect attempt #'+document.nomoresocket);
		if (self.authpump) authpump();
		if (document.wsskey) wsskey=document.wsskey;		
		setTimeout(function(){if (document.wsskey) wsskey=document.wsskey;document.wssid=null;wss_init(userid,wsuri,wsskey,gsid);},rest);	
	}
	
} else {
	if (gid('wsswarn')) gid('wsswarn').style.display='inline';
}
}

wss_markchanges=function(rectype,recid,corrected,msg){
	
	var fgcolor='#ab0200';
	var bgcolor='#ffffcc';
	if (corrected) {
		fgcolor='#000000';
		bgcolor='transparent';
	}
	
	// add custom notifiers
	
	var hit=0;
	
	switch(rectype){
		case 'reauth': ajxpgn('statusc',document.appsettings.codepage+'?cmd=reauth',0,0,'',function(){flashstatus('User credential updated',1000);authpump();});hit=1; break;

		case 'templatetypetemplates':
			if (gid('templatetypetemplates_'+recid)) {gid('templatetypetemplates_'+recid).style.backgroundColor=bgcolor;hit=1;}
		break;
						
		default:
			
	}	
	
	var tabid=gettabid(rectype+'_'+recid);
	if (tabid&&document.tabtitles[tabid]){
		hit=1;
		if (corrected) {
			document.tabtitles[tabid].conflicted=null;
			document.tabtitles[tabid].style.color=fgcolor;
			if (gid('tabreloader_'+rectype+'_'+recid)) gid('tabreloader_'+rectype+'_'+recid).style.background='#dedede';
		} else {
			document.tabtitles[tabid].conflicted=1;
			document.tabtitles[tabid].style.color=fgcolor;
			if (gid('tabreloader_'+rectype+'_'+recid)) gid('tabreloader_'+rectype+'_'+recid).style.background='#ffcccc';
		}
	}	
	
	if (hit){
		if (!document.orgtitle) document.orgtitle=document.title;
		document.title=document.orgtitle+' *';
		setTimeout(function(){document.title=document.orgtitle;},200);
	}	
}
