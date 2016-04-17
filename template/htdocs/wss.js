
function wss_init(userid,wsuri){
if (window.WebSocket){
	
	document.websocket=new WebSocket(wsuri);
	
	document.websocket.onopen=function(e){
		console.log('web socket connected');	
		document.websocket.send('{"type":"getsid","userid":'+userid+'}');
	}
	
	document.websocket.onmessage=function(e){
		var msg = JSON.parse(e.data); //PHP sends Json data
		if (msg.type=='getsid'){
			if (!document.wssid&&msg.userid==userid) {
				document.wssid=msg.sid;
				console.log('registered sid '+msg.sid);
			}
			return;	
		}
		
		if (!document.wssid) return;
		
		if (document.wssid==msg.sid){
			console.log('ignore self message ');
			wss_markchanges(msg.rectype,msg.recid,1);
			return;	
		}
		
		if (msg.type=='update'){
			wss_markchanges(msg.rectype,msg.recid);
			return;	
		}
		
		//console.log(msg);
	}
	
	document.websocket.onerror=function(e){
		document.nomoresocket=1;
		console.log('web socket connection error');	
	}
	
	document.websocket.onclose=function(e){
		if (document.nomoresocket) {
			console.log('no more reconnection');
			if (gid('wsswarn')) gid('wsswarn').style.display='inline';
			return;
		}
		console.log('web socket closed, restarting in a sec');
		setTimeout(function(){document.wssid=null;wss_init(userid,wsuri);},1000);	
	}
	
} else {
	if (gid('wsswarn')) gid('wsswarn').style.display='inline';
}
}

function wss_markchanges(rectype,recid,corrected){
	
	var fgcolor='#ab0200';
	var bgcolor='#ffffcc';
	if (corrected) {
		fgcolor='#000000';
		bgcolor='transparent';
	}
	
	/*
	add custom notifiers here
	*/
	
	switch(rectype){
		case 'filmactors':
			if (gid('filmactors_'+recid)) gid('filmactors_'+recid).style.backgroundColor=bgcolor;
		break;
		
		case 'actorfilms':
			if (gid('actorfilms_'+recid)) gid('actorfilms_'+recid).style.backgroundColor=bgcolor;
		break;		
		
		default:

			
	}	
	
	var tabid=gettabid(rectype+'_'+recid);
	if (tabid&&document.tabtitles[tabid]){
		if (corrected) document.tabtitles[tabid].style.color=fgcolor;
		else document.tabtitles[tabid].style.color=fgcolor;	
	}	
}
	