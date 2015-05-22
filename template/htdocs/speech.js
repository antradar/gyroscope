function speech_startstop(mobile){
	gid('speechstart').mobile=mobile;
	
	if (!document.recog){
		document.recognition.start();
	} else {
		document.recognition.stop();
	}
}


if (window.webkitSpeechRecognition){
	gid('speechstart').style.display='inline';
	
	var recognition=new webkitSpeechRecognition();
	recognition.continuous=true;
	recognition.interimResults=false;
	recognition.lang='en'; //'de-DE';

	recognition.onstart=function(){
		document.recog=true;
		gid('speechstart').style.opacity=0.5;
	}

	recognition.onend=function(){
		document.recog=false;
		gid('speechstart').style.opacity=1;
	}
	
	recognition.onresult=function(e){
		for (var i=e.resultIndex; i<e.results.length; i++){
			var phrase=e.results[i][0].transcript;
			phrase=phrase.trim();
		
			if (!gid('speechstart').mobile){
				gid('speechstart').style.marginLeft='10px';
				setTimeout(function(){gid('speechstart').style.marginLeft=0;},300);
			}

			speech_process(phrase);
			if (phrase.indexOf('cancel')==0) document.recognition.stop();
		}
	}

	document.recognition=recognition;	

}

function speech_process(phrase){
	console.log(phrase);
	phrase=phrase.replace('go to','goto').toLowerCase();
	phrase=phrase.replace('look up', 'lookup');
	phrase=phrase.replace('number one','number 1');
	
	var parts=phrase.split(' ');
	var cmd=parts[0];
	target='';
	
	if (parts.length>10) return;
		
	for (var i=1;i<parts.length;i++){
		if (parts[i]=='') continue;
		target+=' '+parts[i];
	}
	
	target=target.trim();
	
	/* enter look up keys here, activated by "Goto" voice command */
	var lookupkeys={
		lv0:'', lv1:'customerkey'
	};
	
	switch (cmd){
		case 'goto':
			switch(target){
				case 'report': case 'reports': showview(0,null,1); break;
				default: console.log('Unknown target: '+target);	
			}			
		break;
		case 'lookup':
			var lookup=lookupkeys['lv'+document.viewindex];
			if (lookup==null) return;
			
			if (target!='') target=target+'?';
			gid(lookup).value=target;
			var event=document.createEvent('Events');
			event.initEvent('keyup',true,false);
			gid(lookup).dispatchEvent(event);
		
		break;
		
		case 'open':
			var stem=target.replace(/number (\d+)/g,'x');
			if (stem!='x') return;
			
			var idx=parseInt(target.replace(/number /,''),10);
			var oidx=0;
			
			if (document.viewindex==null||!gid('lv'+document.viewindex)) break;
			var lv=gid('lv'+document.viewindex);
			var os=lv.getElementsByTagName('div');
			for (var i=0;i<os.length;i++){
				var o=os[i];
				if (o.className&&o.className=='listitem'){
					oidx++;
					if (idx==oidx){
						var event=document.createEvent('Events');
						event.initEvent('click',true,false);
						o.getElementsByTagName('a')[0].dispatchEvent(event);
					}	
				}
			}
			
			console.log(idx);
		break;
					
	}
}




