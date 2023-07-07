wss_init=function(userid,wsuri,wsskey,gsid){
if (window.WebSocket){
	if (document.wsskey) wsskey=document.wsskey;
	document.websocket=new WebSocket(wsuri+'?WSS'+wsskey+'=');	
	
	document.websocket.onopen=function(e){
		console.log('web socket connected');	
		document.wssready=true;
		if (!document.nomoresocket) document.nomoresocket=0;
		if (gid('wsswarn')) gid('wsswarn').style.display='none';
	}
	
	document.websocket.onmessage=function(e){
		var msg = JSON.parse(e.data); //PHP sends JSON
		if (msg.type=='getsid'){
			if (!document.wssid) {
				document.wssid=msg.sid;
				document.gsid=msg.gsid;
				document.userid=msg.userid;
				document.nomoresocket=0;
				console.log('sid: '+msg.sid);
			}
			return;	
		}
		
		if (!document.wssid) return;

		if (msg.type=='chat'){
			ajxjs(self.showchat,'chats.js');
			if (gid('chatlines_'+msg.chatid)){
				var atabid=gettabid('chat_'+msg.chatid);
				if (atabid>0&&document.userid!=msg.fromagentid) document.tabtitles[atabid].style.color='#00aa00';

				ajxpgn('chattransport_'+msg.chatid,document.appsettings.binpages['1']+'?cmd=getchatmsgs&chatid='+msg.chatid+'&from='+(gid('chatlines_'+msg.chatid).maxmsgid||0),0,0,null,function(rq){
					gid('chatlines_'+msg.chatid).innerHTML+=rq.responseText;
					gid('chatlines_'+msg.chatid).maxmsgid=parseInt(rq.getResponseHeader('maxmsgid'),10);
					
				});	
			} else showchat(msg.chatid,msg.maxmsgid);
			
			if (document.userid!=msg.fromagentid&&gid('gschatsound_msgin')) gid('gschatsound_msgin').play();
			
			
			return;				
		}
		
		//instead of bringing the chat to their attention, bring the dashboard to their attention instead
		//chatschanged
		if(msg.type=='newchat'){
			markchatchanged();	
			if(document.appsettings.beepnewchat&&gid('gschatsound_newchat')) gid('gschatsound_newchat').play();
		}
		
		//another sound for a new chat event
				
		if (msg.gsid!=gsid&&msg.gsid!=0) return;
		
		if (document.wssid==msg.sid){
			console.log('ignore self');
			wss_markchanges(msg.rectype,msg.recid,1,msg);
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
		if (document.nomoresocket&&document.nomoresocket>20) {
			console.log('no more reconnection');
			
			return;
		}
		var span=300; //adjust variability if needed
		var rest=Math.round(Math.random()*span)+500; //

		console.log('web socket closed, restarting in '+rest+'ms. reconnect attempt #'+document.nomoresocket);
		
		if (document.wsskey) wsskey=document.wsskey;		
		setTimeout(function(){if (document.wsskey) wsskey=document.wsskey;document.wssid=null;wss_init(userid,wsuri,wsskey,gsid);if (self.authpump) authpump();},rest);	
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
		case 'chatschanged': markchatchanged(); break;

		case 'templatetypetemplates':
			if (gid('templatetypetemplates_'+recid)) {gid('templatetypetemplates_'+recid).className=corrected?'':'listchanged';hit=1;}
		break;
						
		default:
			
	}	
	
	var tabid=gettabid(rectype+'_'+recid);
	if (tabid&&document.tabtitles[tabid]){
		hit=1;
		if (corrected) {
			document.tabtitles[tabid].conflicted=null;
			document.tabtitles[tabid].style.color=fgcolor;
			if (gid('tabreloader_'+rectype+'_'+recid)) gid('tabreloader_'+rectype+'_'+recid).className='reloader';
		} else {
			document.tabtitles[tabid].conflicted=1;
			document.tabtitles[tabid].style.color=fgcolor;
			if (gid('tabreloader_'+rectype+'_'+recid)) gid('tabreloader_'+rectype+'_'+recid).className='reloader busy';
		}
	}	
	
	if (hit){
		if (tabid&&document.tabviews[tabid]) {
			if (!corrected){
				if (document.tabviews[tabid].afloat) document.tabviews[tabid].className='afloat tabchanged';
				else document.tabviews[tabid].className='tabchanged';
			} else {
				if (document.tabviews[tabid].afloat) document.tabviews[tabid].className='afloat';
				else document.tabviews[tabid].className='';				
			}
		}
	if (!document.orgtitle) document.orgtitle=document.title;
		document.title=document.orgtitle+' *';
		setTimeout(function(){document.title=document.orgtitle;},200);
	} else {
		if (tabid&&document.tabviews[tabid]) {
			if (document.tabviews[tabid].afloat) document.tabviews[tabid].className='afloat';
			else document.tabviews[tabid].className='';
		}
	}
}

markchatchanged=function(){
	if (document.dashchatlock){
		if (gid('dashchatwarning')) gid('dashchatwarning').style.display='block';
		return;
	}
	
	refreshtab('dashchats',1);
	if (document.viewindex=='codegen.chats') reloadview('codegen.chats');
}