showchat=function(chatid,maxmsgid,chatname){
	if(chatname==null) chatname="#"+chatid;
	addtab('chat_'+chatid,'<img src="imgs/t.gif" class="ico-chat">'+chatname,'showchat&chatid='+chatid,function(rq){
		//override the argument id for now
		//sync with showintchat
		var maxmsgid=parseInt(rq.getResponseHeader('maxmsgid'),10);
		gid('chatlines_'+chatid).maxmsgid=maxmsgid;
		gototabbookmark('chatbottom_'+chatid);	
	},null,{bingo:1});
}

gschat_setautotrans=function(chatid){
	var notrans=1;
	if (gid('chatnotrans_'+chatid).checked) notrans=0;
	ajxpgn('statusc',document.appsettings.binpages[1]+'?cmd=setchatnotrans&chatid='+chatid+'&notrans='+notrans);
}

gschat_updatechatsettings=function(){
	
	var omaxchats=gid('maxchats');
	var ochatsperagent=gid('chatsperagent');
	
	var valid=1;
	if(!valint(omaxchats)) {offender=offender||omaxchats; valid=0;}
	if(!valint(ochatsperagent)) {offender=offender||ochatsperagent; valid=0;}
	
	var maxchats=omaxchats.value;
	var chatsperagent=ochatsperagent.value;
	
	reloadtab('chatsettings','', 'updatechatsettings&maxchats='+maxchats+'&chatsperagent='+chatsperagent,null,null,{bingo:1});
	
}
sendchat=function(chatid){
	var omsg=gid('chatsender_'+chatid);
	if (!valstr(omsg)) return;
	var msg=encodeHTML(omsg.value);
	var maxmsgid=gid('chatlines_'+chatid).maxmsgid||0;
	omsg.value='';
	
	var atabid=gettabid('chat_'+chatid);
	if (atabid>0) document.tabtitles[atabid].style.color='#000000';
	
	ajxpgn('chattransport_'+chatid,document.appsettings.binpages[1]+'?cmd=sendchat&chatid='+chatid+'&from='+maxmsgid,0,0,'msg='+msg,function(rq){
		gid('chatlines_'+chatid).innerHTML+=rq.responseText;
		gid('chatlines_'+chatid).maxmsgid=parseInt(rq.getResponseHeader('maxmsgid'),10);
	});			
}

showintchat=function(useridb){
	addtab('intchat_'+useridb,'Internal chat ...','showintchat&useridb='+useridb,function(rq){
		var chatid=rq.getResponseHeader('chatid');
		closetab('intchat_'+useridb);
		addtab('chat_'+chatid,'<img src="imgs/t.gif" class="ico-intchat">'+chatid,'showchat&chatid='+chatid,function(rq){
			//sync with showchat
			var maxmsgid=parseInt(rq.getResponseHeader('maxmsgid'),10);
			gid('chatlines_'+chatid).maxmsgid=maxmsgid;
			gototabbookmark('chatbottom_'+chatid);				
		},null,{bingo:1});
	},null,{bingo:1});	
}

loadchatpre=function(d,chatid,msgto){
	d.parentNode.removeChild(d);
	var omsg='';
	//console.log(omsg);
	ajxpgn('chatpreloader_'+chatid,document.appsettings.binpages[1]+'?cmd=loadchatpre&chatid='+chatid+'&to='+msgto,0,0,null,function(rq){
		gid('chatpre_'+chatid).innerHTML=gid('chatpreloader_'+chatid).innerHTML+gid('chatpre_'+chatid).innerHTML;
	});	
}

claimchat=function(chatid,chatname){
	if (!sconfirm('Are you sure you want to claim this chat?')) return;
			
	ajxpgn('statusc',document.appsettings.binpages[1]+'?cmd=claimchat&chatid='+chatid,0,0,null,function(){
		reloadview('codegen.chats');
		closetab('chat_'+chatid)	
		addtab('dashchats','Chat Sessions','dashchats',null,null,{bingo:1});
		setTimeout(function(){	
		if (!gid('dashchatwarning')) {
		showchat(chatid,0,chatname)
		} else {
		refreshtab('dashchats',1)
		}


		},100);
		
	},null,{bingo:1});
	
}

unclaimchat=function(chatid){
	if (!sconfirm('Are you sure you want to leave this chat without ending the conversation?')) return;
	reloadtab('chat_'+chatid,'','unclaimchat&chatid='+chatid,function(){
		refreshtab('dashchats',1);
	},null,{bingo:1});
	reloadview('codegen.chats');	
}

endchat=function(chatid){
	if (!sconfirm('Are you sure you want to end this chat?')) return;
	var maxmsgid=gid('chatlines_'+chatid).maxmsgid||0;
	maxmsgid++;
	reloadtab('chat_'+chatid,'','endchat&chatid='+chatid,function(){
		gid('chatlines_'+chatid).maxmsgid=maxmsgid;
		//console.log("reinstate maxmsgid:",maxmsgid);
		refreshtab('dashchats',1);
	},null,{bingo:1});
	reloadview('codegen.chats');			
}

setchatagent2=function(chatid,d){
	if (!d.value2) return;
	reloadtab('chat_'+chatid,'','setchatagent2&chatid='+chatid+'&agentid2='+d.value2,function(){
		closetab('chat_'+chatid);
		showchat(chatid);
		refreshtab('dashchats',1);
	},null,{bingo:1});
}

gschat_inpkeydown=function(d,chatid){
	d.onkeyup=function(e){
		var keycode;
		if (e) keycode=e.which; else keycode=event.keyCode;
		
		if (keycode==13) sendchat(chatid);
			
	}	
}

gschat_sethush=function(chatid){
	var hush=gid('chathush_'+chatid).checked?1:0;
	if (gid('chatsendbutton_'+chatid) && gid('chattext_'+chatid)){
		if (hush) {
			gid('chatsendbutton_'+chatid).innerHTML='Send';
			gid('chattext_'+chatid).style.marginLeft="50%"
			gid('chatinternallabel_'+chatid).style.opacity="1";
		}
		else {
			gid('chatsendbutton_'+chatid).innerHTML='Send';
			gid('chattext_'+chatid).style.marginLeft="0"
			gid('chatinternallabel_'+chatid).style.opacity="0";
			
		}
	}
	ajxpgn('statusc',document.appsettings.binpages[1]+'?cmd=setchathush&chatid='+chatid+'&hush='+hush);
}

linkchatcustomer=function(chatid){
	
	var d=gid('chatcustomerid_'+chatid)
	var customerid=d.value2;
	if(!customerid){
		if(d.value!="") return;
		customerid=-1;	
	}
	
	
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=linkchatcustomer&chatid='+chatid+'&customerid='+customerid);
}