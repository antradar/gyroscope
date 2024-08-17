


gschat_getcookie=function(cookiename){
	var rawcookies=document.cookie.split(';');
	for (var i=0;i<rawcookies.length;i++){
		var rawcookie=rawcookies[i].replace(/^\s+|\s+$/g, '');
		var parts=rawcookie.split('=');
		if (parts[0]==cookiename){
			return rawcookie.substr(cookiename.length+1);
		}
	}
	
	return null;	
}

//chatd getchatid
//extend chats table to include gsid
//also send gsid=1, gsauth=sha1(gsid) here
gschat_init=function(containerid,portalparams,gsid,gsauth,reauth){ //portalauth: portalid=#&pgsid=#&puserid=#&portalauth=#
	var chatid=gschat_getcookie('chatid');
	var chatauth=gschat_getcookie('chatauth');

	ajxpgn(containerid+'_chattransport',document.gschatpath+'?cmd=getchatid&chatid='+chatid+'&chatauth='+chatauth+'&gsid='+gsid+'&gsauth='+gsauth,0,0,portalparams,function(rq){

		var chatparts=rq.responseText.split('||');
		var chatid=chatparts[0];//rq.getResponseHeader('chatid');
		var chatauth=chatparts[1];//rq.getResponseHeader('chatauth');
		var chatstatus=parseInt(chatparts[2],10);
		var hasagent=parseInt(chatparts[3],10);
		//4-gsid
		var chatagent=parseInt(chatparts[5],10);
		var agentname=chatparts[6];
	
		
		//gsauth included in chatauth for callback
		
		if (parseInt(chatid,10)!=chatid){
			console.log("cannot connect to chat server");
			if (chatid.length<1000) console.log("reason: "+chatid);
			//if (chatid.length<1000) alert(chatid); else alert("cannot connect to chat server");
			return;	
		}
		
		//store chatid and chatauth cookies
		document.cookie='chatid='+chatid+';;path=/';
		document.cookie='chatauth='+chatauth+';;path=/';

		document.chatid=chatid;
		document.chatauth=chatauth;
		document.chatcontainer=containerid;
		document.portalparams=portalparams;
		document.gsid=gsid;
		document.gsauth=gsauth;
		
		document.chattransport=containerid+'_chattransport';
		
		document.hasagent=hasagent;
		
		document.chatagent=chatagent;
		document.agentname=agentname;
		
		gid(containerid+'_chatstatus').className='chatstatus_'+chatstatus;
		gid(containerid+'_chathasagent').className='chathasagent_'+hasagent;
					
		if (chatstatus==1){
			gid(containerid+'_chatinfo').style.display='none';
			gid(containerid+'_chatform').style.display='block';
			gid(containerid+'_chatbox').style.display='block';
			gschat_loadmessages(0);
			gid(containerid+'_hasagent').style.visibility="visible";
		}
		
		var indicator=gid('chatindicator');
		var icon=gid('chaticon');
		
		if (indicator) indicator.style.display='inline';
		if (icon) icon.style.display='inline';				
		if (icon) icon.className='offline';
		document.chatstatus='offline';
	
		if(hasagent==1){
			gid(document.chatcontainer+'_startbutton').disabled="";
			if (chatagent==0) {
				gid(containerid+'_hasagent').innerHTML="start by saying Hi...";
				
			} else {
				gid(containerid+'_hasagent').innerHTML='chatting with '+agentname;	
			}
			
			if (indicator) indicator.className='online';
			if (icon) icon.className='online';
			document.chatstatus='online';			
					
		}else{
			gid(document.chatcontainer+'_startbutton').disabled="disabled";
			gid(containerid+'_hasagent').innerHTML="no agent is available";	
			if (gid(document.chatcontainer+'_sendbutton')) gid(document.chatcontainer+'_sendbutton').disabled='disabled';
			if (indicator) indicator.className='offline';
			if (icon) icon.className='offline';
			document.chatstatus='offline';	
		}		
		
		if (reauth) {
			//console.log("chat rekey: ",document.chatauth);
			return;
		}
				
		if (window.WebSocket){
			//add gsid, gsauth
			chatwss_init(chatid,chatauth,document.wspath,gsid);
		} else {
			//use interval polling	
		}
		
		if (!document.gschatinited){
		document.lastwindowexit=null;
		if (window.onbeforeunload!=null) document.lastwindowexit=window.onbeforeunload;
		window.onbeforeunload=function(){
			ajxpgn(document.chattransport,document.gschatpath+'?cmd=autoleave&chatid='+document.chatid+'&chatauth='+document.chatauth,0,0,'nocache=1');
			if (document.lastwindowexit!=null) return document.lastwindowexit();
		}
		
		/*
		document.lastwindowresize=null;
		if (window.onresize) document.lastwindowresize=window.onresize;
		window.onresize=function(){
			//if (document.lastwindowresize!=null) document.lastwindowresize();
			
			var h=document.documentElement.clientHeight||window.innerHeight;

			var uptake=150; //space taken by other components
			
			gid(containerid+'_chatbox').style.maxHeight=(h-uptake)+'px';
			
				
		}
		
		*/
		
		setInterval(function(){
			gschat_init(containerid,portalparams,gsid,gsauth,true);	
		},900*1000);
				
		document.gschatinited=true;
		
		}
		
		//window.onresize();
		
	},null,null,null,1);
}

//gsid, gsauth
chatwss_init=function(chatid,chatauth,wsuri,gsid){

	if (document.wsskey) wsskey=document.wsskey;
	document.chatsocket=new WebSocket(wsuri+'?GSC'+chatid+'-'+chatauth+'-'+gsid+'=');	
	
	
	document.chatsocket.onopen=function(e){
		console.log('chat socket connected');	
		document.wssready=true;
		if (!document.nomoresocket) document.nomoresocket=0;
		//if (gid('wsswarn')) gid('wsswarn').style.display='none';
		if (gid(document.chatcontainer+'_gschatreconnect')) gid(document.chatcontainer+'_gschatreconnect').style.display='none';
	}
	
	document.chatsocket.onmessage=function(e){
		var msg = JSON.parse(e.data); //PHP sends JSON
		if (msg.type=='getsid'){
			document.nomoresocket=0;
			//console.log('sid: '+msg.sid);		
			return;	
		}
		
		var indicator=gid('chatindicator');
		var icon=gid('chaticon');
		
				
		if (msg.type=='setagent'){
			document.chatagent=parseInt(msg.claimagentid,10);
			document.agentname=msg.claimagentname;
			if (document.chatagent==0) {
				gid(document.chatcontainer+'_hasagent').innerHTML="please wait for an agent to respond...";
			} else {
				gid(document.chatcontainer+'_hasagent').innerHTML='chatting with '+document.agentname;
			}
			
		}
		
		if (msg.type=='message'){
			//console.log(msg);
			gschat_loadmessages(msg.maxmsgid);
			if (gid('gschatsound_msgin')) gid('gschatsound_msgin').play();
			return;	
		}
		
		if (msg.type=='online'){
			document.hasagent=1;
			gid(document.chatcontainer+'_startbutton').disabled="";
			
			if (document.chatagent==0) gid(document.chatcontainer+'_hasagent').innerHTML="please wait for an agent to respond...";
			else {
				gid(document.chatcontainer+'_hasagent').innerHTML='chatting with '+document.agentname;
			}	
			if (gid(document.chatcontainer+'_sendbutton')) gid(document.chatcontainer+'_sendbutton').disabled='';
			
			if (icon) icon.className='online';
			if (indicator) indicator.className='online';
			document.chatstatus='online';
			
		}
		
		if (msg.type=='offline'){
			document.hasagent=0;
			gid(document.chatcontainer+'_startbutton').disabled="disabled";
			
			gid(document.chatcontainer+'_hasagent').innerHTML="no agent is available";	
			if (gid(document.chatcontainer+'_sendbutton')) gid(document.chatcontainer+'_sendbutton').disabled='disabled';
			
			if (icon) icon.className='offline';
			if (indicator) indicator.className='offline';
			document.chatstatus='offline';		
			
		}
		
		if (msg.type=='hold'){
				
			gid(document.chatcontainer+'_hasagent').innerHTML="please hold";	
			if (gid(document.chatcontainer+'_sendbutton')) gid(document.chatcontainer+'_sendbutton').disabled='disabled';
			
			if (icon) icon.className='online';
			if (indicator) indicator.className='online';
			document.chatstatus='online';		
			
		}
			
		
		//gid(document.chatcontainer+'_chatstatus').className='chatstatus_'+document.chatstatus;
		gid(document.chatcontainer+'_chathasagent').className='chathasagent_'+document.hasagent;
		
		
	}
	
	document.chatsocket.onerror=function(e){
		if (!document.nomoresocket) document.nomoresocket=0;
		console.log('chat socket connection error');	
	}
	
	document.chatsocket.onclose=function(e){
		if (!document.nomoresocket) document.nomoresocket=0;
		document.nomoresocket++;
		
		document.wssready=null;
		//if (gid('wsswarn')) gid('wsswarn').style.display='inline';
		if (document.nomoresocket&&document.nomoresocket>10) {
			console.log('no more reconnection');
			if (gid(document.chatcontainer+'_gschatreconnect')) gid(document.chatcontainer+'_gschatreconnect').style.display='block';
			return;
		}
		var span=1500; //adjust variability if needed
		var rest=Math.round(Math.random()*span)+2000; //2-3.5 secs

		console.log('chat socket closed, restarting in '+rest+'ms. reconnect attempt #'+document.nomoresocket);
		//if (self.authpump) authpump();
		//if (document.wsskey) wsskey=document.wsskey;
		
		//hmmm?		
		setTimeout(function(){if (document.chatauth) chatwss_init(chatid,chatauth,wsuri,gsid);},rest);	
	}
	

}

gschat_loadmessages=function(maxmsgid){
		var msgida=gid(document.chatcontainer+'_chatbox').maxmsgid
		if (msgida==null) msgida=0;
		ajxpgn(document.chattransport,document.gschatpath+'?cmd=getmessages&chatid='+document.chatid+'&chatauth='+document.chatauth+'&from='+msgida,0,0,'nocache=1',function(rq){
			var parts=rq.responseText.split('||||');
			gid(document.chatcontainer+'_chatbox').innerHTML+=parts[1];
			gid(document.chatcontainer+'_chatbox').maxmsgid=parseInt(parts[0],10);
			gid(document.chatcontainer+'_chatbox').scrollTop=gid(document.chatcontainer+'_chatbox').scrollHeight;
			//console.log('new max: '+gid(document.chatcontainer+'_chatbox').maxmsgid);
		},null,null,null,1);
		
}

function gschat_start(){
	var ofname=gid(document.chatcontainer+'_chatfname');
	var olname=gid(document.chatcontainer+'_chatlname');
	
	var fname=encodeHTML(ofname.value);
	var lname=encodeHTML(olname.value);
	if (fname==''||lname=='') return;
	
	ajxpgn(document.chattransport,document.gschatpath+'?cmd=startchat&chatid='+document.chatid+'&chatauth='+document.chatauth,0,0,'fname='+fname+'&lname='+lname,function(rq){
		if (rq.responseText!=parseInt(rq.responseText,10)){
			alert(rq.responseText);
			return;	
		}
		gid(document.chatcontainer+'_chatinfo').style.display='none';
		gid(document.chatcontainer+'_chatform').style.display='block';
		gid(document.chatcontainer+'_chatbox').style.display='block';
		gid(document.chatcontainer+'_hasagent').style.visibility='visible';
	},null,null,null,1);
	
}

function gschat_end(){
	if (!confirm('Are you sure you want to end this conversation?')) return;
	
	
	ajxpgn(document.chattransport,document.gschatpath+'?cmd=endchat&chatid='+document.chatid+'&chatauth='+document.chatauth,0,0,'nocache=1',function(rq){
		if (rq.responseText!=parseInt(rq.responseText,10)){
			alert(rq.responseText);
			return;	
		}
		gid(document.chatcontainer+'_chatform').style.display='none';
		gid(document.chatcontainer+'_chatbox').style.display='none';
		gid(document.chatcontainer+'_chatinfo').style.display='block';
		gid(document.chatcontainer+'_chatbox').maxmsgid=0;
		gid(document.chatcontainer+'_chatbox').innerHTML='';
		gid(document.chatcontainer+'_hasagent').style.visibility='hidden';
					
		setTimeout(function(){	
		gschat_init(document.chatcontainer,document.portalparams,document.gsid,document.gsauth);
		},200);
	},null,null,null,1);
	
}

function gschat_send(){
	var omsg=gid(document.chatcontainer+'_chatbox_input');
	var msg=encodeHTML(omsg.value);
	if (msg=='') return;
	omsg.value='';
	ajxpgn(document.chattransport,document.gschatpath+'?cmd=sendchat&chatid='+document.chatid+'&chatauth='+document.chatauth,0,0,'msg='+msg,function(rq){
		var maxmsgid=parseInt(rq.responseText,10);
		if (maxmsgid!=rq.responseText){
			alert(rq.responseText);
			return;
		}
		
		gschat_loadmessages(maxmsgid||0);	
		
	},null,null,null,1);
}

function gschat_setviewstate(state){
	if (!gid(document.chatcontainer+'_chatviewstate')) return;
	gid(document.chatcontainer+'_chatviewstate').className='chatviewstate_'+state;
	
}

function gschat_inpkeydown(d){
	d.onkeyup=function(e){
		var keycode;
		if (e) keycode=e.which; else keycode=event.keyCode;
		
		if (keycode==13) gschat_send();
			
	}	
}
