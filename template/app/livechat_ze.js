
function livechat_init(){
	if (!window.WebSocket) return;
	if (!window.zE){
		//if (window.console&&window.console.log) console.log('Waiting for Zopim Chat');
		setTimeout(livechat_init,800);
		return;	
	}
	
	zE('webWidget', 'hide');
	zE('webWidget', 'chat:end');
	zE('webWidget', 'logout');
	
	zE('webWidget:on', 'chat:status', function(status) {
	
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
	
}

function livechat_start(){

	if (gid('launcher')) gid('launcher').style.display='none';
	
	if (document.chatstatus=='offline') return;
	zE('webWidget', 'open');
	zE('webWidget', 'show');
	livechat_updatesummary();
}

function livechat_updatesummary(){
	if (document.chatupdater) clearTimeout(document.chatupdater);
	document.chatupdater=setTimeout(function(){
	var tooltitle=gid('tooltitle').innerHTML;
	var tabkey='';
	var tabcount=0;
	if (document.tabcount) tabcount=document.tabcount;
	if (document.currenttab) tabkey=document.tabkeys[document.currenttab];
	var user=gid('labellogin').innerHTML;
	
	var summary="Tab Count: "+tabcount+" | List View: "+tooltitle+" | Current Tab: "+tabkey;
	
	zE('webWidget', 'identify', {name:user});
 	zE('webWidget', 'updatePath', {
	 	url: 'https://www.antradar.com/blog-zopim-migration#'+hb(),
    	title: summary
  	});
	},500);
}