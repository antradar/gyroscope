
function livechat_init(){
	if (!window.WebSocket) return;
	if (!window['$zopim']||!window['$zopim']['livechat']){
		//if (window.console&&window.console.log) console.log('Waiting for Zopim Chat');
		setTimeout(livechat_init,800);
		return;	
	}
	
	$zopim.livechat.setOnStatus(function(status){
		var indicator=gid('chatindicator');
		var icon=gid('chaticon');
		document.chatstatus=status;
		if (indicator) indicator.style.display='inline';
		if (icon) icon.style.display='inline';
		
		if (status=='offline') {
			if (indicator) indicator.className='offline';
			if (icon) icon.className='offline';
		} else {
			if (indicator) indicator.className='';
			if (icon) icon.className='';
		}
		
	});
	
	$zopim.livechat.button.hide();
	$zopim.livechat.button.setOffsetHorizontalMobile(1000);
	$zopim.livechat.badge.hide();
	$zopim.livechat.mobileNotifications.setDisabled(true);
	$zopim.livechat.endChat();	
	$zopim.livechat.clearAll();	
		
	$zopim.livechat.setOnChatEnd(function(){$zopim.livechat.button.hide();$zopim.livechat.badge.hide();$zopim.livechat.bubble.hide();$zopim.livechat.mobileNotifications.setDisabled(true);});	
	$zopim.livechat.setOnConnected(function(){
		$zopim.livechat.button.hide();
		$zopim.livechat.badge.hide();		
	});
}

function livechat_start(){

	if (document.chatstatus=='offline') return;
	$zopim.livechat.window.toggle();
	livechat_updatesummary();
}

function livechat_updatesummary(){
	if (!$zopim.livechat.window.getDisplay()) return;
	if (document.chatupdater) clearTimeout(document.chatupdater);
	document.chatupdater=setTimeout(function(){
	var tooltitle=gid('tooltitle').innerHTML;
	var tabkey='';
	var tabcount=0;
	if (document.tabcount) tabcount=document.tabcount;
	if (document.currenttab) tabkey=document.tabkeys[document.currenttab];
	var user=gid('labellogin').innerHTML;
	
	var summary="User: "+user+"\r\nTab Count: "+tabcount+"\r\nList View: "+tooltitle+"\r\nCurrent Tab: "+tabkey;
	$zopim.livechat.setName(user);
	$zopim.livechat.setNotes(summary);
	},500);
}